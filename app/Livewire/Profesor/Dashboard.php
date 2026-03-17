<?php

namespace App\Livewire\Profesor;

use App\Models\Horario;
use App\Models\Profesor;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    // Datos principales del profesor.
    public string $nombre_profesor = 'Sin profesor';
    public string $telefono = 'Sin teléfono';
    public string $perfil = 'Profesor';
    public string $estado_profesor = 'Inactivo';
    public string $color_profesor = '#3b82f6';
    public string $dia_actual = '';

    // Arreglos del tablero.
    public array $resumen = [];
    public array $clases_hoy = [];
    public array $proximas_clases = [];
    public array $resumen_academico = [];
    public array $accesos_rapidos = [];

    // Registro actual.
    public ?Profesor $profesor = null;

    public function mount(): void
    {
        Carbon::setLocale('es');

        $this->dia_actual = mb_strtoupper(Carbon::now()->translatedFormat('l'));

        $this->cargarProfesor();
        $this->cargarTablero();
    }

    protected function cargarProfesor(): void
    {
        $this->profesor = Profesor::query()
            ->where('user_id', Auth::id())
            ->first();

        if (!$this->profesor) {
            return;
        }

        $nombreCompleto = collect([
            $this->profesor->nombre,
            $this->profesor->apellido_paterno,
            $this->profesor->apellido_materno,
        ])->filter()->implode(' ');

        $this->nombre_profesor = $nombreCompleto !== '' ? $nombreCompleto : 'Profesor';
        $this->telefono = $this->profesor->telefono ?: 'Sin teléfono';
        $this->perfil = $this->profesor->perfil ?: 'Profesor';
        $this->estado_profesor = $this->profesor->status === 'true' ? 'Activo' : 'Inactivo';
        $this->color_profesor = $this->profesor->color ?: '#3b82f6';
    }

    protected function cargarTablero(): void
    {
        if (!$this->profesor) {
            $this->resumen = [
                [
                    'titulo' => 'Materias asignadas',
                    'valor' => 0,
                    'icono' => 'book-open',
                    'color' => 'bg-gradient-to-br from-sky-500 via-blue-600 to-indigo-600',
                    'descripcion' => 'Materias activas del profesor',
                ],
                [
                    'titulo' => 'Grupos atendidos',
                    'valor' => 0,
                    'icono' => 'users',
                    'color' => 'bg-gradient-to-br from-fuchsia-500 via-pink-500 to-rose-500',
                    'descripcion' => 'Grupos con carga docente',
                ],
                [
                    'titulo' => 'Bloques asignados',
                    'valor' => 0,
                    'icono' => 'calendar-days',
                    'color' => 'bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-500',
                    'descripcion' => 'Horarios registrados',
                ],
                [
                    'titulo' => 'Clases de hoy',
                    'valor' => 0,
                    'icono' => 'clock',
                    'color' => 'bg-gradient-to-br from-amber-400 via-orange-500 to-red-500',
                    'descripcion' => 'Sesiones programadas hoy',
                ],
            ];

            $this->resumen_academico = [];
            $this->clases_hoy = [];
            $this->proximas_clases = [];
            $this->accesos_rapidos = [];

            return;
        }

        $asignaciones = $this->profesor->asignacionMaterias()
            ->with(['materia', 'cuatrimestre', 'licenciatura'])
            ->get();

        $horarios = Horario::query()
            ->with([
                'dia',
                'cuatrimestre',
                'licenciatura',
                'generacion',
                'asignacionMateria.materia',
            ])
            ->whereHas('asignacionMateria', function ($consulta) {
                $consulta->where('profesor_id', $this->profesor->id);
            })
            ->get();

        $clasesHoyColeccion = $horarios
            ->filter(function ($horario) {
                return optional($horario->dia)->dia === $this->dia_actual;
            })
            ->sortBy(function ($horario) {
                return $this->convertirHoraInicioAMinutos($horario->hora);
            })
            ->values();

        $proximasClasesColeccion = $horarios
            ->sortBy(function ($horario) {
                return ($this->obtenerOrdenDia(optional($horario->dia)->dia) * 10000)
                    + $this->convertirHoraInicioAMinutos($horario->hora);
            })
            ->take(5)
            ->values();

        $totalMaterias = $asignaciones->pluck('materia_id')->filter()->unique()->count();
        $totalGrupos = $horarios->pluck('generacion_id')->filter()->unique()->count();
        $totalBloques = $horarios->count();
        $totalClasesHoy = $clasesHoyColeccion->count();

        $this->resumen = [
            [
                'titulo' => 'Materias asignadas',
                'valor' => $totalMaterias,
                'icono' => 'book-open',
                'color' => 'bg-gradient-to-br from-sky-500 via-blue-600 to-indigo-600',
                'descripcion' => 'Materias activas del profesor',
            ],
            [
                'titulo' => 'Grupos atendidos',
                'valor' => $totalGrupos,
                'icono' => 'users',
                'color' => 'bg-gradient-to-br from-fuchsia-500 via-pink-500 to-rose-500',
                'descripcion' => 'Grupos con carga docente',
            ],
            [
                'titulo' => 'Bloques asignados',
                'valor' => $totalBloques,
                'icono' => 'calendar-days',
                'color' => 'bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-500',
                'descripcion' => 'Horarios registrados',
            ],
            [
                'titulo' => 'Clases de hoy',
                'valor' => $totalClasesHoy,
                'icono' => 'clock',
                'color' => 'bg-gradient-to-br from-amber-400 via-orange-500 to-red-500',
                'descripcion' => 'Sesiones programadas hoy',
            ],
        ];

        $this->clases_hoy = $clasesHoyColeccion->map(function ($horario) {
            return [
                'materia' => optional(optional($horario->asignacionMateria)->materia)->nombre ?: 'Sin materia',
                'clave' => optional(optional($horario->asignacionMateria)->materia)->clave ?: 'Sin clave',
                'grupo' => optional($horario->generacion)->generacion ?: 'Sin grupo',
                'cuatrimestre' => optional($horario->cuatrimestre)->nombre_cuatrimestre ?: 'Sin cuatrimestre',
                'hora' => $horario->hora ?: 'Sin horario',
                'licenciatura' => optional($horario->licenciatura)->nombre ?: 'Sin licenciatura',
            ];
        })->toArray();

        $this->proximas_clases = $proximasClasesColeccion->map(function ($horario) {
            return [
                'materia' => optional(optional($horario->asignacionMateria)->materia)->nombre ?: 'Sin materia',
                'dia' => optional($horario->dia)->dia ?: 'Sin día',
                'hora' => $horario->hora ?: 'Sin horario',
            ];
        })->toArray();

        $this->resumen_academico = [
            [
                'titulo' => 'Materias activas',
                'valor' => $totalMaterias,
                'descripcion' => 'Carga docente actual',
                'clase' => 'text-sky-600 dark:text-sky-400',
            ],
            [
                'titulo' => 'Grupos',
                'valor' => $totalGrupos,
                'descripcion' => 'Generaciones atendidas',
                'clase' => 'text-fuchsia-600 dark:text-fuchsia-400',
            ],
            [
                'titulo' => 'Sesiones hoy',
                'valor' => $totalClasesHoy,
                'descripcion' => 'Clases del día actual',
                'clase' => 'text-emerald-600 dark:text-emerald-400',
            ],
            [
                'titulo' => 'Bloques totales',
                'valor' => $totalBloques,
                'descripcion' => 'Carga acumulada en horario',
                'clase' => 'text-amber-600 dark:text-amber-400',
            ],
        ];

        // Aquí no inventé rutas.
        // Si ya las tienes creadas, solo reemplaza url por route('...').
        $this->accesos_rapidos = [
            [
                'titulo' => 'Mi horario',
                'descripcion' => 'Consulta tus horarios asignados.',
                'url' => '#',
                'icono' => 'calendar-days',
                'clase' => 'from-sky-500 to-blue-600',
            ],
            [
                'titulo' => 'Mis materias',
                'descripcion' => 'Revisa tus materias asignadas.',
                'url' => '#',
                'icono' => 'book-open',
                'clase' => 'from-fuchsia-500 to-pink-500',
            ],
            [
                'titulo' => 'Mi perfil',
                'descripcion' => 'Visualiza tus datos personales.',
                'url' => '#',
                'icono' => 'user-circle',
                'clase' => 'from-emerald-500 to-teal-500',
            ],
        ];
    }

    protected function obtenerOrdenDia(?string $dia): int
    {
        return match (mb_strtoupper((string) $dia)) {
            'LUNES' => 1,
            'MARTES' => 2,
            'MIERCOLES' => 3,
            'MIÉRCOLES' => 3,
            'JUEVES' => 4,
            'VIERNES' => 5,
            default => 99,
        };
    }

    protected function convertirHoraInicioAMinutos(?string $rangoHora): int
    {
        if (!$rangoHora) {
            return 9999;
        }

        $partes = explode('-', $rangoHora);
        $horaInicio = trim($partes[0] ?? '');

        if ($horaInicio === '') {
            return 9999;
        }

        $horaInicio = str_replace([' ', '.'], '', mb_strtolower($horaInicio));

        if (preg_match('/^(\d{1,2}):(\d{2})(am|pm)$/', $horaInicio, $coincidencias)) {
            $hora = (int) $coincidencias[1];
            $minutos = (int) $coincidencias[2];
            $periodo = $coincidencias[3];

            if ($periodo === 'pm' && $hora !== 12) {
                $hora += 12;
            }

            if ($periodo === 'am' && $hora === 12) {
                $hora = 0;
            }

            return ($hora * 60) + $minutos;
        }

        return 9999;
    }

    public function render()
    {
        return view('livewire.profesor.dashboard');
    }
}
