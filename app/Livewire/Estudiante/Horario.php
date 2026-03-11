<?php

namespace App\Livewire\Estudiante;

use App\Models\Alumno;
use App\Models\Horario as HorarioModel;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Horario extends Component
{
    // Datos principales del estudiante.
    public string $nombre_estudiante = '';
    public string $matricula = 'Sin matrícula';
    public string $licenciatura = 'Sin licenciatura';
    public string $cuatrimestre = 'Sin cuatrimestre';
    public string $generacion = 'Sin generación';

    // IDs que se usan para construir la ruta del PDF.
    public ?int $licenciatura_id = null;
    public ?int $generacion_id = null;
    public ?int $cuatrimestre_id = null;

    // Texto para buscar dentro del horario.
    public string $search = '';

    // Días y estructura del horario.
    public array $dias = ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES'];
    public array $horas = [];
    public array $horario = [];

    // Datos de resumen.
    public int $total_materias = 0;
    public int $total_bloques = 0;
    public int $materias_hoy = 0;
    public string $dia_actual = '';

    public function mount(): void
    {
        // Al iniciar, se carga toda la información.
        $this->cargarHorario();
    }

    public function updatedSearch(): void
    {
        // Cuando cambia la búsqueda, se vuelve a generar el horario.
        $this->cargarHorario();
    }

    public function cargarHorario(): void
    {
        $usuario = Auth::user();

        // Si no hay sesión, se limpian los datos.
        if (!$usuario) {
            $this->reiniciarDatos();
            return;
        }

        // Se busca el alumno relacionado con el usuario.
        $alumno = Alumno::query()
            ->with('datosEscolares')
            ->where('user_id', $usuario->id)
            ->first();

        // Si no existe el alumno, se limpian los datos.
        if (!$alumno) {
            $this->reiniciarDatos();
            return;
        }

        // Se forma el nombre completo del estudiante.
        $this->nombre_estudiante = trim(
            ($alumno->nombre ?? '') . ' ' .
                ($alumno->apellido_paterno ?? '') . ' ' .
                ($alumno->apellido_materno ?? '')
        );

        // Se obtiene la matrícula.
        $this->matricula = $alumno->datosEscolares->matricula ?? 'Sin matrícula';

        // Se obtiene la inscripción activa más reciente.
        $inscripcion = Inscripcion::query()
            ->with(['licenciatura', 'cuatrimestre', 'generacion'])
            ->where('alumno_id', $alumno->id)
            ->where('status', 1)
            ->latest('id')
            ->first();

        // Si no hay inscripción activa, se limpian IDs y horario.
        if (!$inscripcion) {
            $this->licenciatura_id = null;
            $this->generacion_id = null;
            $this->cuatrimestre_id = null;
            $this->reiniciarHorario();
            return;
        }

        // Se guardan los IDs para la ruta del PDF.
        $this->licenciatura_id = $inscripcion->licenciatura_id;
        $this->generacion_id = $inscripcion->generacion_id;
        $this->cuatrimestre_id = $inscripcion->cuatrimestre_id;

        // Se guardan los textos para mostrar en la vista.
        $this->licenciatura = $inscripcion->licenciatura->nombre ?? 'Sin licenciatura';
        $this->cuatrimestre = $inscripcion->cuatrimestre->nombre_cuatrimestre ?? 'Sin cuatrimestre';
        $this->generacion = $inscripcion->generacion->generacion ?? 'Sin generación';

        // Se cargan los registros del horario.
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

        // Si hay búsqueda, se filtran los registros.
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

        // Se ordenan por la hora inicial.
        $registros = $registros
            ->sortBy(fn($item) => $this->obtenerMinutosInicio($item->hora))
            ->values();

        // Se obtienen las horas únicas.
        $this->horas = $registros
            ->pluck('hora')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // Se reinicia la matriz del horario.
        $this->horario = [];

        foreach ($this->horas as $hora) {
            foreach ($this->dias as $dia) {
                $this->horario[$hora][$dia] = null;
            }
        }

        // Se asigna cada bloque a su día y hora.
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

        // Se calcula el día actual.
        $this->dia_actual = $this->obtenerDiaActual();

        // Se calculan los totales.
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
        // Limpia la búsqueda y vuelve a cargar el horario.
        $this->search = '';
        $this->cargarHorario();
    }

    public function reiniciarDatos(): void
    {
        // Reinicia todos los datos del componente.
        $this->nombre_estudiante = '';
        $this->matricula = 'Sin matrícula';
        $this->licenciatura = 'Sin licenciatura';
        $this->cuatrimestre = 'Sin cuatrimestre';
        $this->generacion = 'Sin generación';

        $this->licenciatura_id = null;
        $this->generacion_id = null;
        $this->cuatrimestre_id = null;

        $this->dia_actual = '';
        $this->search = '';

        $this->reiniciarHorario();
    }

    public function reiniciarHorario(): void
    {
        // Reinicia la estructura del horario y sus totales.
        $this->horas = [];
        $this->horario = [];
        $this->total_materias = 0;
        $this->total_bloques = 0;
        $this->materias_hoy = 0;
    }

    public function obtenerDiaActual(): string
    {
        // Obtiene el día actual en el mismo formato del horario.
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
        // Si el rango no tiene el formato esperado, se manda al final.
        if (!$rangoHora || !str_contains($rangoHora, '-')) {
            return 999999;
        }

        $inicio = trim(explode('-', $rangoHora)[0]);

        return $this->convertirHoraAMinutos($inicio);
    }

    public function convertirHoraAMinutos(string $hora): int
    {
        // Convierte una hora tipo 8:00am en minutos.
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
        // Decide si el texto debe ser claro u oscuro según el color.
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

    public function getFiltrosListosProperty(): bool
    {
        // Igual que en admin: valida que ya existan los 3 IDs.
        return !empty($this->licenciatura_id)
            && !empty($this->generacion_id)
            && !empty($this->cuatrimestre_id);
    }

    public function getPdfUrlProperty(): string
    {

        if (!$this->filtrosListos) {
            return '#';
        }

        return route('estudiante.pdf.horario');
    }

    public function getClasePdfProperty(): string
    {
        // Igual que en admin: misma base visual del botón.
        $base = 'inline-flex items-center my-2 justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-400 to-indigo-500 text-white px-6 py-3 text-sm font-semibold shadow transition';

        return $this->filtrosListos
            ? $base . ' hover:opacity-95'
            : $base . ' pointer-events-none opacity-60 cursor-not-allowed';
    }

    public function formatearDia(string $dia): string
    {
        // Muestra el nombre del día con formato más amigable.
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
