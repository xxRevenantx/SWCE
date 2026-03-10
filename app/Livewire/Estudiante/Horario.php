<?php

namespace App\Livewire\Estudiante;

use App\Models\Alumno;
use App\Models\Horario as HorarioModel;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Horario extends Component
{
    // Datos del estudiante.
    public string $nombre_estudiante = '';
    public string $matricula = 'Sin matrícula';
    public string $licenciatura = 'Sin licenciatura';
    public string $cuatrimestre = 'Sin cuatrimestre';
    public string $generacion = 'Sin generación';

    // Búsqueda del horario.
    public string $search = '';

    // Días y bloques del horario.
    public array $dias = ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES'];
    public array $horas = [];
    public array $horario = [];

    // Resumen.
    public int $total_materias = 0;
    public int $total_bloques = 0;
    public int $materias_hoy = 0;
    public string $dia_actual = '';

    public function mount(): void
    {
        $this->cargarHorario();
    }

    // Cuando cambia la búsqueda, vuelvo a construir el horario.
    public function updatedSearch(): void
    {
        $this->cargarHorario();
    }

    public function cargarHorario(): void
    {
        $usuario = Auth::user();

        if (!$usuario) {
            $this->reiniciarDatos();
            return;
        }

        $alumno = Alumno::query()
            ->with('datosEscolares')
            ->where('user_id', $usuario->id)
            ->first();

        if (!$alumno) {
            $this->reiniciarDatos();
            return;
        }

        $this->nombre_estudiante = trim(
            ($alumno->nombre ?? '') . ' ' .
            ($alumno->apellido_paterno ?? '') . ' ' .
            ($alumno->apellido_materno ?? '')
        );

        $this->matricula = $alumno->datosEscolares->matricula ?? 'Sin matrícula';

        $inscripcion = Inscripcion::query()
            ->with(['licenciatura', 'cuatrimestre', 'generacion'])
            ->where('alumno_id', $alumno->id)
            ->where('status', 1)
            ->latest('id')
            ->first();

        if (!$inscripcion) {
            $this->reiniciarHorario();
            return;
        }

        $this->licenciatura = $inscripcion->licenciatura->nombre ?? 'Sin licenciatura';
        $this->cuatrimestre = $inscripcion->cuatrimestre->nombre_cuatrimestre ?? 'Sin cuatrimestre';
        $this->generacion = $inscripcion->generacion->generacion ?? 'Sin generación';

        $registros = HorarioModel::query()
            ->with([
                'dia',
                'asignacionMateria.materia',
                'asignacionMateria.profesor',
            ])
            ->where('licenciatura_id', $inscripcion->licenciatura_id)
            ->where('cuatrimestre_id', $inscripcion->cuatrimestre_id)
            ->where('generacion_id', $inscripcion->generacion_id)
            ->get();

        // Aquí se aplica la búsqueda por profesor, clave, materia, hora y día.
        if (filled($this->search)) {
            $busqueda = mb_strtolower(trim($this->search));

            $registros = $registros->filter(function ($registro) use ($busqueda) {
                $materia = $registro->asignacionMateria?->materia;
                $profesor = $registro->asignacionMateria?->profesor;
                $dia = strtoupper($registro->dia->dia ?? '');

                $nombreProfesor = trim(
                    ($profesor->nombre ?? '') . ' ' .
                    ($profesor->apellido_paterno ?? '') . ' ' .
                    ($profesor->apellido_materno ?? '')
                );

                $texto = mb_strtolower(implode(' ', array_filter([
                    $materia->nombre ?? '',
                    $materia->clave ?? '',
                    $nombreProfesor,
                    $registro->hora ?? '',
                    $dia,
                    $this->formatearDia($dia),
                ])));

                return str_contains($texto, $busqueda);
            })->values();
        }

        $registros = $registros
            ->sortBy(fn($item) => $this->obtenerMinutosInicio($item->hora))
            ->values();

        $this->horas = $registros
            ->pluck('hora')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $this->horario = [];

        foreach ($this->horas as $hora) {
            foreach ($this->dias as $dia) {
                $this->horario[$hora][$dia] = null;
            }
        }

        foreach ($registros as $registro) {
            $nombreDia = strtoupper($registro->dia->dia ?? '');

            if (!in_array($nombreDia, $this->dias, true)) {
                continue;
            }

            $materia = $registro->asignacionMateria?->materia;
            $profesor = $registro->asignacionMateria?->profesor;

            $nombreProfesor = 'Sin profesor';

            if ($profesor) {
                $nombreProfesor = trim(
                    ($profesor->nombre ?? '') . ' ' .
                    ($profesor->apellido_paterno ?? '') . ' ' .
                    ($profesor->apellido_materno ?? '')
                );
            }

            $this->horario[$registro->hora][$nombreDia] = [
                'hora' => $registro->hora,
                'materia' => $materia->nombre ?? 'Sin materia',
                'clave' => $materia->clave ?? 'Sin clave',
                'profesor' => $nombreProfesor,
                'color' => $profesor->color ?? '#2563eb',
            ];
        }

        $this->dia_actual = $this->obtenerDiaActual();

        $this->total_bloques = $registros->count();

        $this->total_materias = $registros
            ->pluck('asignacionMateria.materia_id')
            ->filter()
            ->unique()
            ->count();

        $this->materias_hoy = $registros
            ->filter(fn($item) => strtoupper($item->dia->dia ?? '') === $this->dia_actual)
            ->count();
    }

    public function limpiarBusqueda(): void
    {
        $this->search = '';
        $this->cargarHorario();
    }

    public function reiniciarDatos(): void
    {
        $this->nombre_estudiante = '';
        $this->matricula = 'Sin matrícula';
        $this->licenciatura = 'Sin licenciatura';
        $this->cuatrimestre = 'Sin cuatrimestre';
        $this->generacion = 'Sin generación';
        $this->dia_actual = '';
        $this->search = '';
        $this->reiniciarHorario();
    }

    public function reiniciarHorario(): void
    {
        $this->horas = [];
        $this->horario = [];
        $this->total_materias = 0;
        $this->total_bloques = 0;
        $this->materias_hoy = 0;
    }

    public function obtenerDiaActual(): string
    {
        $diaHoy = strtoupper(now()->locale('es')->dayName);

        return match ($diaHoy) {
            'LUNES' => 'LUNES',
            'MARTES' => 'MARTES',
            'MIÉRCOLES', 'MIERCOLES' => 'MIERCOLES',
            'JUEVES' => 'JUEVES',
            'VIERNES' => 'VIERNES',
            default => '',
        };
    }

    public function obtenerMinutosInicio(?string $rangoHora): int
    {
        if (!$rangoHora || !str_contains($rangoHora, '-')) {
            return 999999;
        }

        $inicio = trim(explode('-', $rangoHora)[0]);

        return $this->convertirHoraAMinutos($inicio);
    }

    public function convertirHoraAMinutos(string $hora): int
    {
        $hora = strtolower(trim($hora));

        preg_match('/(\d{1,2}):(\d{2})(am|pm)/', $hora, $coincidencias);

        if (!isset($coincidencias[1], $coincidencias[2], $coincidencias[3])) {
            return 999999;
        }

        $horas = (int) $coincidencias[1];
        $minutos = (int) $coincidencias[2];
        $periodo = $coincidencias[3];

        if ($periodo === 'pm' && $horas !== 12) {
            $horas += 12;
        }

        if ($periodo === 'am' && $horas === 12) {
            $horas = 0;
        }

        return ($horas * 60) + $minutos;
    }

    public function obtenerColorTexto(string $colorHex): string
    {
        $colorHex = ltrim($colorHex, '#');

        if (strlen($colorHex) !== 6) {
            return '#ffffff';
        }

        $rojo = hexdec(substr($colorHex, 0, 2));
        $verde = hexdec(substr($colorHex, 2, 2));
        $azul = hexdec(substr($colorHex, 4, 2));

        $luminosidad = (($rojo * 299) + ($verde * 587) + ($azul * 114)) / 1000;

        return $luminosidad > 155 ? '#111827' : '#ffffff';
    }

    public function formatearDia(string $dia): string
    {
        return match ($dia) {
            'LUNES' => 'Lunes',
            'MARTES' => 'Martes',
            'MIERCOLES' => 'Miércoles',
            'JUEVES' => 'Jueves',
            'VIERNES' => 'Viernes',
            default => ucfirst(strtolower($dia)),
        };
    }

    public function render()
    {
        return view('livewire.estudiante.horario');
    }
}
