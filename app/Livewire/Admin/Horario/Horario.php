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

    public function mount(): void
    {
        // Se cargan licenciaturas para el primer filtro
        $this->licenciaturas = Licenciatura::query()
            ->orderBy('id')
            ->get();

        // Se definen las horas fijas de la tabla
        $this->horasDisponibles = [
            "8:00am-9:00am",
            "9:00am-10:00am",
            "10:00am-10:30am",
            "10:30am-11:30am",
            "11:30am-12:30pm",
            "12:30pm-1:30pm",
            "1:30pm-2:30pm",
            "2:30pm-3:30pm",
        ];

        // Se cargan los días y se ordenan de lunes a viernes
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

        // Se cargan cuatrimestres (catálogo completo)
        $this->cuatrimestres = Cuatrimestre::query()
            ->orderBy('no_cuatrimestre')
            ->get();

        // Se inicia la tabla en blanco
        $this->llenarHorarioEnBlanco();
    }

    public function llenarHorarioEnBlanco(): void
    {
        // Se prepara la tabla con "0" para indicar sin asignar
        $this->horario = [];

        foreach ($this->dias as $dia) {
            foreach ($this->horasDisponibles as $hora) {
                $this->horario[$dia->id][$hora] = "0";
            }
        }
    }

    public function updatedLicenciaturaId(): void
    {
        // Al cambiar licenciatura, se limpian filtros dependientes
        $this->generacion_id = null;
        $this->cuatrimestre_id = null;

        // Se limpian materias y se reinicia la tabla
        $this->materias = [];
        $this->llenarHorarioEnBlanco();

        // Si no hay licenciatura, no se cargan generaciones
        if (!$this->licenciatura_id) {
            $this->generaciones = [];
            return;
        }

        // Se obtienen las generaciones disponibles para esa licenciatura
        $idsGeneracion = AsignarGeneracion::query()
            ->where('licenciatura_id', $this->licenciatura_id)
            ->pluck('generacion_id')
            ->unique()
            ->values();

        // Se cargan los datos de generación para el select
        $this->generaciones = Generacion::query()
            ->whereIn('id', $idsGeneracion)
            ->orderBy('generacion')
            ->get();
    }

    public function updatedGeneracionId(): void
    {
        // Al cambiar generación, se limpia cuatrimestre
        $this->cuatrimestre_id = null;

        // Se limpian materias y se reinicia la tabla
        $this->materias = [];
        $this->llenarHorarioEnBlanco();

        // Si ya están listos los filtros, se carga todo
        $this->cargarHorarioSiListo();
    }

    public function updatedCuatrimestreId(): void
    {
        // Al cambiar cuatrimestre, se limpian materias y tabla
        $this->materias = [];
        $this->llenarHorarioEnBlanco();

        // Si ya están listos los filtros, se carga todo
        $this->cargarHorarioSiListo();
    }

    private function cargarHorarioSiListo(): void
    {
        // Solo se carga si los 3 filtros están completos
        if (!$this->licenciatura_id || !$this->generacion_id || !$this->cuatrimestre_id) {
            return;
        }

        // Se cargan materias para mostrar en los selects de la tabla
        $this->materias = AsignacionMateria::query()
            ->with(['materia', 'profesor'])
            ->where('licenciatura_id', $this->licenciatura_id)
            ->where('cuatrimestre_id', $this->cuatrimestre_id)
            ->orderBy('id')
            ->get();

        // Se carga el horario ya guardado en base de datos
        $this->cargarHorario();
    }

    public function cargarHorario(): void
    {
        // Se reinicia la tabla antes de cargar datos
        $this->llenarHorarioEnBlanco();

        // Si faltan filtros, no se consulta nada
        if (!$this->licenciatura_id || !$this->generacion_id || !$this->cuatrimestre_id) {
            return;
        }

        // Se consultan registros del horario en base de datos
        $horariosBD = HorarioModelo::query()
            ->where('licenciatura_id', $this->licenciatura_id)
            ->where('generacion_id', $this->generacion_id)
            ->where('cuatrimestre_id', $this->cuatrimestre_id)
            ->get();

        // Se colocan los valores en la tabla
        foreach ($horariosBD as $h) {
            $this->horario[$h->dia_id][$h->hora] = (string) ($h->asignacion_materia_id ?? "0");
        }
    }

    public function limpiarFiltros(): void
    {
        // Se limpian los filtros
        $this->licenciatura_id = null;
        $this->generacion_id = null;
        $this->cuatrimestre_id = null;

        // Se limpia lo que depende de los filtros
        $this->generaciones = [];
        $this->materias = [];

        // Se reinicia la tabla
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
        // Si faltan filtros, no se guarda nada
        if (!$this->licenciatura_id || !$this->generacion_id || !$this->cuatrimestre_id) {
            return;
        }

        // Si llega "0", significa que se quiere dejar vacío
        $asignacion_materia_id = empty($asignacion_materia_id) || $asignacion_materia_id == "0"
            ? null
            : (int) $asignacion_materia_id;

        // Datos que identifican una celda de la tabla
        $criterios = [
            'licenciatura_id' => $this->licenciatura_id,
            'generacion_id' => $this->generacion_id,
            'cuatrimestre_id' => $this->cuatrimestre_id,
            'dia_id' => $dia_id,
            'hora' => $hora,
        ];

        // Si se dejó vacío, se elimina el registro
        if (is_null($asignacion_materia_id)) {
            HorarioModelo::query()->where($criterios)->delete();
            $this->cargarHorario();
            return;
        }

        // Se busca la asignación para obtener el profesor
        $asignacion = AsignacionMateria::query()->find($asignacion_materia_id);

        // Si no existe, no se guarda
        if (!$asignacion) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'La opción seleccionada no existe',
                'position' => 'top-end',
            ]);
            return;
        }

        // Si no tiene profesor, no se guarda (profesor_id es obligatorio en horarios)
        if (empty($asignacion->profesor_id)) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'La materia seleccionada no tiene profesor',
                'position' => 'top-end',
            ]);
            return;
        }

        // Se guarda o actualiza incluyendo el profesor
        HorarioModelo::query()->updateOrCreate(
            $criterios,
            [
                'asignacion_materia_id' => $asignacion_materia_id,
                'profesor_id' => (int) $asignacion->profesor_id,
            ]
        );

        // Se vuelve a cargar la tabla
        $this->cargarHorario();

        // Mensaje de confirmación
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Horario actualizado',
            'position' => 'top-end',
        ]);
    }

    public function render()
    {
        // Se envían datos a la vista
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
