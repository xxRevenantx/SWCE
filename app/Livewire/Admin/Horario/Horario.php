<?php

namespace App\Livewire\Admin\Horario;

use App\Models\AsignacionMateria;
use App\Models\AsignarGeneracion;
use App\Models\Cuatrimestre;
use App\Models\Dia;
use App\Models\Generacion;
use App\Models\Licenciatura;
use App\Models\Horario as HorarioModelo;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Horario extends Component
{
    public array $horasDisponibles = [];
    public $dias = [];

    public $licenciaturas = [];
    public $generaciones = [];
    public $cuatrimestres = [];

    public ?int $licenciatura_id = null;
    public ?int $generacion_id = null;
    public ?int $cuatrimestre_id = null;

    public $materias = [];

    public array $horario = [];

    public string $horaReceso = '10:00am-10:30am';

    public function mount(): void
    {
        $this->licenciaturas = Licenciatura::query()
            ->orderBy('id')
            ->get();

        $this->horasDisponibles = [
            '8:00am-9:00am',
            '9:00am-10:00am',
            '10:00am-10:30am',
            '10:30am-11:30am',
            '11:30am-12:30pm',
            '12:30pm-1:30pm',
            '1:30pm-2:30pm',
            '2:30pm-3:30pm',
        ];

        $this->dias = Dia::query()
            ->orderByRaw("
                CASE UPPER(dia)
                    WHEN 'LUNES' THEN 1
                    WHEN 'MARTES' THEN 2
                    WHEN 'MIERCOLES' THEN 3
                    WHEN 'MIÉRCOLES' THEN 3
                    WHEN 'JUEVES' THEN 4
                    WHEN 'VIERNES' THEN 5
                    ELSE 99
                END
            ")
            ->get();

        $this->cuatrimestres = Cuatrimestre::query()
            ->orderBy('no_cuatrimestre')
            ->get();

        $this->llenarHorarioEnBlanco();
    }

    public function llenarHorarioEnBlanco(): void
    {
        $this->horario = [];

        foreach ($this->dias as $dia) {
            foreach ($this->horasDisponibles as $hora) {
                $this->horario[$dia->id][$hora] = '0';
            }
        }
    }

    public function updatedLicenciaturaId($value): void
    {
        $this->licenciatura_id = !empty($value) && (int) $value > 0 ? (int) $value : null;

        $this->generacion_id = null;
        $this->cuatrimestre_id = null;
        $this->generaciones = [];
        $this->materias = [];

        $this->llenarHorarioEnBlanco();

        if (!$this->licenciatura_id) {
            return;
        }

        $idsGeneracion = AsignarGeneracion::query()
            ->where('licenciatura_id', $this->licenciatura_id)
            ->pluck('generacion_id')
            ->unique()
            ->values();

        $this->generaciones = Generacion::query()
            ->whereIn('id', $idsGeneracion)
            ->orderBy('generacion')
            ->get();
    }

    public function updatedGeneracionId($value): void
    {
        $this->generacion_id = !empty($value) && (int) $value > 0 ? (int) $value : null;

        $this->cuatrimestre_id = null;
        $this->materias = [];
        $this->llenarHorarioEnBlanco();

        $this->cargarHorarioSiListo();
    }

    public function updatedCuatrimestreId($value): void
    {
        $this->cuatrimestre_id = !empty($value) && (int) $value > 0 ? (int) $value : null;

        $this->materias = [];
        $this->llenarHorarioEnBlanco();

        $this->cargarHorarioSiListo();
    }

    private function cargarHorarioSiListo(): void
    {
        if (!$this->licenciatura_id || !$this->generacion_id || !$this->cuatrimestre_id) {
            return;
        }

        $this->materias = AsignacionMateria::query()
            ->with(['materia', 'profesor'])
            ->where('licenciatura_id', $this->licenciatura_id)
            ->where('cuatrimestre_id', $this->cuatrimestre_id)
            ->whereNotNull('profesor_id')
            ->orderBy('id')
            ->get();

        $this->cargarHorario();
    }

    public function cargarHorario(): void
    {
        $this->llenarHorarioEnBlanco();

        if (!$this->licenciatura_id || !$this->generacion_id || !$this->cuatrimestre_id) {
            return;
        }

        $horariosBD = HorarioModelo::query()
            ->with(['asignacionMateria.profesor'])
            ->where('licenciatura_id', $this->licenciatura_id)
            ->where('generacion_id', $this->generacion_id)
            ->where('cuatrimestre_id', $this->cuatrimestre_id)
            ->get();

        foreach ($horariosBD as $h) {
            if ($h->hora === $this->horaReceso) {
                continue;
            }

            $this->horario[$h->dia_id][$h->hora] = (string) ($h->asignacion_materia_id ?? '0');
        }
    }

    public function limpiarFiltros(): void
    {
        $this->licenciatura_id = null;
        $this->generacion_id = null;
        $this->cuatrimestre_id = null;

        $this->generaciones = [];
        $this->materias = [];

        $this->llenarHorarioEnBlanco();
    }

    public function getFiltrosListosProperty(): bool
    {
        return !empty($this->licenciatura_id)
            && !empty($this->generacion_id)
            && !empty($this->cuatrimestre_id);
    }

    public function getPdfUrlProperty(): string
    {
        if (!$this->filtrosListos) {
            return '#';
        }

        return route('admin.pdf.horario', [
            $this->licenciatura_id,
            $this->generacion_id,
            $this->cuatrimestre_id,
        ]);
    }

    public function getClasePdfProperty(): string
    {
        $base = 'inline-flex items-center my-2 justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-400 to-indigo-500 text-white px-6 py-3 text-sm font-semibold shadow transition';

        return $this->filtrosListos
            ? $base . ' hover:opacity-95'
            : $base . ' pointer-events-none opacity-60 cursor-not-allowed';
    }

    public function actualizarHorario(int $dia_id, string $hora, $asignacion_materia_id): void
    {
        if ($hora === $this->horaReceso) {
            return;
        }

        if (!$this->licenciatura_id || !$this->generacion_id || !$this->cuatrimestre_id) {
            return;
        }

        $asignacion_materia_id = empty($asignacion_materia_id) || $asignacion_materia_id == '0'
            ? null
            : (int) $asignacion_materia_id;

        $criterios = [
            'licenciatura_id' => $this->licenciatura_id,
            'generacion_id' => $this->generacion_id,
            'cuatrimestre_id' => $this->cuatrimestre_id,
            'dia_id' => $dia_id,
            'hora' => $hora,
        ];

        if (is_null($asignacion_materia_id)) {
            HorarioModelo::query()
                ->where($criterios)
                ->delete();

            $this->cargarHorario();
            return;
        }

        $asignacion = AsignacionMateria::query()
            ->with(['materia', 'profesor'])
            ->find($asignacion_materia_id);

        if (!$asignacion) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'La materia seleccionada no existe',
                'position' => 'top-end',
            ]);
            return;
        }

        if ((int) $asignacion->licenciatura_id !== (int) $this->licenciatura_id) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'La materia no pertenece a la licenciatura seleccionada',
                'position' => 'top-end',
            ]);
            return;
        }

        if ((int) $asignacion->cuatrimestre_id !== (int) $this->cuatrimestre_id) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'La materia no pertenece al cuatrimestre seleccionado',
                'position' => 'top-end',
            ]);
            return;
        }

        if (empty($asignacion->profesor_id)) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'La materia seleccionada no tiene profesor asignado',
                'position' => 'top-end',
            ]);
            return;
        }

        $profesorId = (int) $asignacion->profesor_id;

        $conflictoProfesor = HorarioModelo::query()
            ->where('dia_id', $dia_id)
            ->where('hora', $hora)
            ->whereHas('asignacionMateria', function ($query) use ($profesorId) {
                $query->where('profesor_id', $profesorId);
            })
            ->where(function ($query) use ($criterios) {
                $query->where('licenciatura_id', '!=', $criterios['licenciatura_id'])
                    ->orWhere('generacion_id', '!=', $criterios['generacion_id'])
                    ->orWhere('cuatrimestre_id', '!=', $criterios['cuatrimestre_id']);
            })
            ->exists();

        if ($conflictoProfesor) {
            $nombreProfesor = trim(
                ($asignacion->profesor->nombre ?? '') . ' ' .
                ($asignacion->profesor->apellido_paterno ?? '') . ' ' .
                ($asignacion->profesor->apellido_materno ?? '')
            );

            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Conflicto de horario',
                'text' => 'El profesor ' . $nombreProfesor . ' ya tiene otra asignación en ese mismo día y hora.',
                'position' => 'top-end',
            ]);

            $this->cargarHorario();
            return;
        }

        try {
            DB::beginTransaction();

            HorarioModelo::query()->updateOrCreate(
                $criterios,
                [
                    'asignacion_materia_id' => $asignacion_materia_id,
                ]
            );

            DB::commit();

            $this->cargarHorario();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Horario actualizado',
                'position' => 'top-end',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->cargarHorario();

            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al guardar',
                'text' => 'No se pudo guardar el horario.',
                'position' => 'top-end',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.horario.horario', [
            'dias' => $this->dias,
            'horas' => $this->horasDisponibles,
            'materias' => $this->materias,
            'licenciaturas' => $this->licenciaturas,
            'generaciones' => $this->generaciones,
            'cuatrimestres' => $this->cuatrimestres,
        ]);
    }
}
