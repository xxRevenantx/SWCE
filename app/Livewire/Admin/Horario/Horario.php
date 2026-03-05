<?php

namespace App\Livewire\Admin\Horario;

use App\Models\AsignacionMateria;
use App\Models\AsignarGeneracion;
use App\Models\Cuatrimestre;
use App\Models\Dia;
use App\Models\Generacion;
use App\Models\Licenciatura;
use App\Models\Horario as HorarioModelo;
use Livewire\Component;

class Horario extends Component
{
    /** Horas fijas que se muestran en la tabla */
    public array $horasDisponibles = [];

    /** Días de la semana para columnas */
    public $dias = [];

    /** Catálogos para los filtros */
    public $licenciaturas = [];
    public $generaciones = [];
    public $cuatrimestres = [];

    /** Valores seleccionados en los filtros */
    public ?int $licenciatura_id = null;
    public ?int $generacion_id = null;
    public ?int $cuatrimestre_id = null;

    /** Materias disponibles para seleccionar en la tabla */
    public $materias = [];

    /** Matriz del horario: [dia_id][hora] = asignacion_materia_id */
    public array $horario = [];

    /** Hora que se maneja como receso (no lleva selects) */
    public string $horaReceso = '10:00am-10:30am';

    public function mount(): void
    {
        // Licenciaturas para el primer filtro
        $this->licenciaturas = Licenciatura::query()
            ->orderBy('id')
            ->get();

        // Horas fijas de la tabla
        $this->horasDisponibles = [
            "8:00am-9:00am",
            "9:00am-10:00am",
            "10:00am-10:30am", // Receso
            "10:30am-11:30am",
            "11:30am-12:30pm",
            "12:30pm-1:30pm",
            "1:30pm-2:30pm",
            "2:30pm-3:30pm",
        ];

        // Días ordenados de lunes a viernes
        $this->dias = Dia::query()
            ->orderByRaw("
                CASE dia
                    WHEN 'Lunes' THEN 1
                    WHEN 'Martes' THEN 2
                    WHEN 'Miércoles' THEN 3
                    WHEN 'Jueves' THEN 4
                    WHEN 'Viernes' THEN 5
                    ELSE 99
                END
            ")
            ->get();

        // Catálogo de cuatrimestres
        $this->cuatrimestres = Cuatrimestre::query()
            ->orderBy('no_cuatrimestre')
            ->get();

        // Tabla en blanco
        $this->llenarHorarioEnBlanco();
    }

    public function llenarHorarioEnBlanco(): void
    {
        $this->horario = [];

        foreach ($this->dias as $dia) {
            foreach ($this->horasDisponibles as $hora) {
                $this->horario[$dia->id][$hora] = "0";
            }
        }
    }

    public function updatedLicenciaturaId(): void
    {
        $this->generacion_id = null;
        $this->cuatrimestre_id = null;

        $this->materias = [];
        $this->llenarHorarioEnBlanco();

        if (!$this->licenciatura_id) {
            $this->generaciones = [];
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

    public function updatedGeneracionId(): void
    {
        $this->cuatrimestre_id = null;

        $this->materias = [];
        $this->llenarHorarioEnBlanco();

        $this->cargarHorarioSiListo();
    }

    public function updatedCuatrimestreId(): void
    {
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
            ->where('licenciatura_id', $this->licenciatura_id)
            ->where('generacion_id', $this->generacion_id)
            ->where('cuatrimestre_id', $this->cuatrimestre_id)
            ->get();

        foreach ($horariosBD as $h) {

            // Receso: no se pinta aunque exista guardado
            if ($h->hora === $this->horaReceso) {
                continue;
            }

            $this->horario[$h->dia_id][$h->hora] = (string) ($h->asignacion_materia_id ?? "0");
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
        return !empty($this->licenciatura_id) && !empty($this->generacion_id) && !empty($this->cuatrimestre_id);
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
        // Receso: no se guarda nada
        if ($hora === $this->horaReceso) {
            return;
        }

        if (!$this->licenciatura_id || !$this->generacion_id || !$this->cuatrimestre_id) {
            return;
        }

        $asignacion_materia_id = empty($asignacion_materia_id) || $asignacion_materia_id == "0"
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
            HorarioModelo::query()->where($criterios)->delete();
            $this->cargarHorario();
            return;
        }

        $asignacion = AsignacionMateria::query()->find($asignacion_materia_id);

        if (!$asignacion) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'La opción seleccionada no existe',
                'position' => 'top-end',
            ]);
            return;
        }

        if (empty($asignacion->profesor_id)) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'La materia seleccionada no tiene profesor',
                'position' => 'top-end',
            ]);
            return;
        }

        HorarioModelo::query()->updateOrCreate(
            $criterios,
            [
                'asignacion_materia_id' => $asignacion_materia_id,
                'profesor_id' => (int) $asignacion->profesor_id,
            ]
        );

        $this->cargarHorario();

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Horario actualizado',
            'position' => 'top-end',
        ]);
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
