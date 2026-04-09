<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calificacion;
use App\Models\Cuatrimestre;
use App\Models\Generacion;
use App\Models\Inscripcion;
use App\Models\Licenciatura;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    // EXPEDIENTE DEL ALUMNO
    public function expedienteAlumno($id)
    {

        $alumno = \App\Models\Inscripcion::findOrFail($id);


        if (!$alumno) {
            abort(404);
        }

        $data = [
            'alumno' => $alumno,
            1
        ];

        $pdf = Pdf::loadView('admin.pdf.expedienteAlumnoPDF', $data)->setPaper('letter', 'portrait');
        return $pdf->stream("EXPEDIENTE_" . mb_strtoupper($alumno->alumno->nombre . "_" . $alumno->alumno->apellido_paterno . "_" . $alumno->alumno->apellido_materno) . ".pdf");
    }

    // CREDENCIAL DEL PROFESOR
    public function credencialProfesor($id)
    {
        $profesor = \App\Models\Profesor::findOrFail($id);

        if (!$profesor) {
            abort(404);
        }

        $data = [
            'profesor' => $profesor,
            1
        ];

        $pdf = Pdf::loadView('admin.pdf.credencialProfesorPDF', $data)->setPaper('letter', 'portrait');
        return $pdf->stream("CREDENCIAL_" . mb_strtoupper($profesor->nombre . "_" . $profesor->apellido_paterno . "_" . $profesor->apellido_materno) . ".pdf");
    }

    // LISTA MATRÍCULA
    public function listaMatricula($filtrar_licenciatura = null, $filtrar_generacion = null, $filtrar_cuatrimestre = null, $search = null)
    {

        // dd($filtrar_licenciatura, $filtrar_generacion, $filtrar_cuatrimestre, $search);

        $inscripciones = \App\Models\Inscripcion::with(['alumno', 'licenciatura', 'generacion', 'cuatrimestre'])
            ->when($filtrar_licenciatura, function ($query) use ($filtrar_licenciatura) {
                $query->where('licenciatura_id', $filtrar_licenciatura);
            })
            ->when($filtrar_generacion, function ($query) use ($filtrar_generacion) {
                $query->where('generacion_id', $filtrar_generacion);
            })
            ->when($filtrar_cuatrimestre, function ($query) use ($filtrar_cuatrimestre) {
                $query->where('cuatrimestre_id', $filtrar_cuatrimestre);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('alumno', function ($q) use ($search) {
                    $q->where('nombre', 'like', "%$search%")
                        ->orWhere('apellido_paterno', 'like', "%$search%")
                        ->orWhere('apellido_materno', 'like', "%$search%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [
            'inscripciones' => $inscripciones,
        ];

        $pdf = Pdf::loadView('admin.pdf.listaMatriculaPDF', $data)->setPaper('letter', 'portrait');
        return $pdf->stream("LISTA_MATRICULA.pdf");
    }

    // BOLETA DE CALIFICACIONES
    public function boletaCalificacion($id, $cuatrimestre_id)
    {
        $calificaciones = \App\Models\Calificacion::query()
            ->join('asignacion_materias', 'calificaciones.asignacion_materia_id', '=', 'asignacion_materias.id')
            ->join('materias', 'materias.id', '=', 'asignacion_materias.materia_id')
            ->where('calificaciones.inscripcion_id', $id)
            ->where('asignacion_materias.cuatrimestre_id', $cuatrimestre_id)
            ->orderByRaw("COALESCE(materias.clave, '') ASC")
            ->select('calificaciones.*')
            ->get();

        $cuatrimestre = Cuatrimestre::where('id', $cuatrimestre_id)->first();


        $licenciatura = Inscripcion::where('id', $id)->with('licenciatura')->first()->licenciatura;

        $generacion = Inscripcion::where('id', $id)->with('generacion')->first()->generacion;
        // dd($cuatrimestre);

        $alumno = Inscripcion::where('id', $id)->with('alumno')->first();


        $nombreAlumno = trim(
            ($alumno->alumno->nombre ?? '') . '_' .
            ($alumno->alumno->apellido_paterno ?? '') . '_' .
            ($alumno->alumno->apellido_materno ?? '')
        );


        if (!$calificaciones) {
            abort(404);
        }
        $data = [
            'calificaciones' => $calificaciones,
            'cuatrimestre' => $cuatrimestre,
            'licenciatura' => $licenciatura,
            'generacion' => $generacion,
            'alumno' => $alumno,
        ];
        $pdf = Pdf::loadView('admin.pdf.boletaCalificacionPDF', $data)->setPaper('letter', 'portrait');
        return $pdf->stream("BOLETA_CALIFICACIONES_" . $nombreAlumno . "_" . $cuatrimestre->no_cuatrimestre . "°_CUATRIMESTRE.pdf");
    }

    public function calificacionesGenerales($licenciatura, $generacion, $cuatrimestre)
    {
        $alumnos = Inscripcion::query()
            ->join('alumnos', 'alumnos.id', '=', 'inscripciones.alumno_id')
            ->leftJoin('datos_escolares', 'datos_escolares.alumno_id', '=', 'alumnos.id')
            ->where('inscripciones.licenciatura_id', $licenciatura)
            ->where('inscripciones.generacion_id', $generacion)
            ->where('inscripciones.cuatrimestre_id', $cuatrimestre)
            ->orderBy('alumnos.apellido_paterno')
            ->orderBy('alumnos.apellido_materno')
            ->orderBy('alumnos.nombre')
            ->select([
                'inscripciones.id as inscripcion_id',
                'alumnos.id as alumno_id',
                'datos_escolares.matricula as matricula',
                'alumnos.nombre as nombre',
                'alumnos.apellido_paterno as apellido_paterno',
                'alumnos.apellido_materno as apellido_materno',
            ])
            ->get()
            ->map(function ($r) {
                return (object) [
                    'inscripcion_id' => $r->inscripcion_id,
                    'alumno_id' => $r->alumno_id,
                    'matricula' => $r->matricula,
                    'nombre_completo' => trim(
                        ($r->apellido_paterno ?? '') . ' ' .
                        ($r->apellido_materno ?? '') . ' ' .
                        ($r->nombre ?? '')
                    ),
                ];
            });

        if ($alumnos->isEmpty()) {
            abort(404);
        }

        $inscripcionIds = $alumnos->pluck('inscripcion_id')->values();

        $calificacionesRaw = Calificacion::query()
            ->join('asignacion_materias', 'asignacion_materias.id', '=', 'calificaciones.asignacion_materia_id')
            ->join('materias', 'materias.id', '=', 'asignacion_materias.materia_id')
            ->whereIn('calificaciones.inscripcion_id', $inscripcionIds)
            ->orderByRaw("COALESCE(materias.clave,'') ASC")
            ->select([
                'calificaciones.inscripcion_id',
                'calificaciones.asignacion_materia_id',
                'calificaciones.calificacion',
                'materias.clave as materia_clave',
                'materias.nombre as materia_nombre',
            ])
            ->get();

        $materias = $calificacionesRaw
            ->map(function ($r) {
                return (object) [
                    'asignacion_materia_id' => $r->asignacion_materia_id,
                    'clave' => $r->materia_clave,
                    'nombre' => $r->materia_nombre,
                ];
            })
            ->unique('asignacion_materia_id')
            ->sortBy(fn($m) => $m->clave ?? '')
            ->values();

        $matriz = [];
        foreach ($calificacionesRaw as $r) {
            $matriz[$r->inscripcion_id][$r->asignacion_materia_id] = $r->calificacion;
        }

        // Promedio truncado a 1 decimal, sin redondear
        $promedios = [];
        foreach ($alumnos as $a) {
            $valores = [];

            foreach ($materias as $m) {
                $valor = $matriz[$a->inscripcion_id][$m->asignacion_materia_id] ?? null;

                if (is_numeric($valor)) {
                    $valores[] = (float) $valor;
                }
            }

            if (count($valores) > 0) {
                $promedioReal = array_sum($valores) / count($valores);

                // Se trunca a 1 decimal, no se redondea
                $promedios[$a->inscripcion_id] = floor($promedioReal * 10) / 10;
            } else {
                $promedios[$a->inscripcion_id] = null;
            }
        }

        $lic = Licenciatura::query()
            ->select('id', 'nombre', 'logo')
            ->find($licenciatura);

        $gen = Generacion::query()
            ->select('id', 'generacion')
            ->find($generacion);

        $cuat = Cuatrimestre::query()
            ->select('id', 'no_cuatrimestre')
            ->find($cuatrimestre);

        $nombreLicenciatura = $lic?->nombre ?? 'LICENCIATURA_DESCONOCIDA';
        $nombreGeneracion = $gen?->generacion ?? 'GEN_DESCONOCIDA';
        $nombreCuatrimestre = $cuat?->no_cuatrimestre ?? 'CUATRIMESTRE_DESCONOCIDO';

        $data = [
            'materias' => $materias,
            'alumnos' => $alumnos,
            'matriz' => $matriz,
            'promedios' => $promedios,
            'licenciatura' => $lic,
            'nombreLicenciatura' => $nombreLicenciatura,
            'nombreGeneracion' => $nombreGeneracion,
            'nombreCuatrimestre' => $nombreCuatrimestre,
        ];

        $pdf = Pdf::loadView('admin.pdf.calificacionesGeneralesPDF', $data)
            ->setPaper('letter', 'landscape');

        $filename = "CALIFICACIONES_GENERALES_" .
            mb_strtoupper($nombreLicenciatura) . "_" .
            mb_strtoupper($nombreGeneracion) . "_" .
            mb_strtoupper($nombreCuatrimestre) . ".pdf";

        return $pdf->stream($filename);
    }


    // HORARIO
    public function horario($licenciatura, $generacion, $cuatrimestre)
    {
        $horario = \App\Models\Horario::query()
            ->where('licenciatura_id', $licenciatura)
            ->where('generacion_id', $generacion)
            ->where('cuatrimestre_id', $cuatrimestre)
            ->with(['asignacionMateria.materia', 'dia'])
            ->get();

        $data = [
            'licenciatura' => Licenciatura::find($licenciatura),
            'generacion' => Generacion::find($generacion),
            'cuatrimestre' => Cuatrimestre::find($cuatrimestre),
            'horario' => $horario,
        ];

        $pdf = Pdf::loadView('admin.pdf.horarioPDF', $data)->setPaper('letter', 'portrait');
        return $pdf->stream("HORARIO_" . mb_strtoupper($data['licenciatura']->nombre) . "_" . mb_strtoupper($data['generacion']->generacion) . "_" . mb_strtoupper($data['cuatrimestre']->no_cuatrimestre) . "°_CUATRIMESTRE.pdf");
    }



    public function kardexAlumno(int $alumno)
    {
        $alumno = \App\Models\Alumno::with([
            'datosEscolares',
            'datosContacto',
        ])->findOrFail($alumno);

        $inscripciones = \App\Models\Inscripcion::query()
            ->with([
                'licenciatura',
                'generacion',
                'cuatrimestre',
                'calificaciones.asignacionMateria.materia',
                'calificaciones.asignacionMateria.profesor',
                'calificaciones.asignacionMateria.cuatrimestre',
            ])
            ->where('alumno_id', $alumno->id)
            ->whereHas('cuatrimestre')
            ->get()
            ->sortBy(fn($inscripcion) => (int) ($inscripcion->cuatrimestre?->no_cuatrimestre ?? 999))
            ->values();

        $licenciatura = $inscripciones->first()?->licenciatura;
        $generacion = $inscripciones->first()?->generacion;

        $cuatrimestres = \App\Models\Cuatrimestre::query()
            ->orderBy('no_cuatrimestre')
            ->get();

        $materiasPorCuatrimestre = collect();

        if ($licenciatura) {
            $materiasPorCuatrimestre = \App\Models\Materia::query()
                ->where('licenciatura_id', $licenciatura->id)
                ->where('calificable', 'si')
                ->orderBy('cuatrimestre_id')
                ->orderBy('clave')
                ->get()
                ->groupBy('cuatrimestre_id');
        }

        // Se juntan todas las calificaciones del alumno sin depender del cuatrimestre de inscripción
        $todasLasCalificaciones = $inscripciones
            ->flatMap(function ($inscripcion) {
                return $inscripcion->calificaciones;
            })
            ->filter(function ($calificacion) {
                return !is_null($calificacion->asignacionMateria?->materia_id)
                    && !is_null($calificacion->asignacionMateria?->cuatrimestre_id);
            });

        // Se agrupan por cuatrimestre de la asignación y luego por materia
        $calificacionesPorCuatrimestreYMateria = $todasLasCalificaciones
            ->sortByDesc(function ($calificacion) {
                return $calificacion->fecha_captura ?? $calificacion->id;
            })
            ->groupBy(function ($calificacion) {
                return $calificacion->asignacionMateria->cuatrimestre_id;
            })
            ->map(function ($calificacionesDelCuatrimestre) {
                return collect($calificacionesDelCuatrimestre)
                    ->groupBy(function ($calificacion) {
                        return $calificacion->asignacionMateria->materia_id;
                    })
                    ->map(function ($grupo) {
                        // Se toma la más reciente de cada materia
                        return $grupo->first();
                    });
            });

        $kardex = $cuatrimestres->map(function ($cuatrimestre) use ($materiasPorCuatrimestre, $calificacionesPorCuatrimestreYMateria) {
            $materiasBase = collect($materiasPorCuatrimestre->get($cuatrimestre->id, collect()));
            $calificacionesDelCuatrimestre = collect($calificacionesPorCuatrimestreYMateria->get($cuatrimestre->id, collect()));

            $materias = $materiasBase->map(function ($materia) use ($calificacionesDelCuatrimestre) {
                $calificacion = $calificacionesDelCuatrimestre->get($materia->id);
                $profesor = $calificacion?->asignacionMateria?->profesor;

                $nombreProfesor = trim(
                    ($profesor->nombre ?? '') . ' ' .
                    ($profesor->apellido_paterno ?? '') . ' ' .
                    ($profesor->apellido_materno ?? '')
                );

                return [
                    'clave' => $materia->clave ?? '---',
                    'materia' => $materia->nombre ?? 'MATERIA',
                    'calificacion' => is_numeric($calificacion?->calificacion)
                        ? number_format((float) $calificacion->calificacion, 1, '.', '')
                        : '---',
                    'profesor' => $nombreProfesor !== '' ? $nombreProfesor : '—',
                ];
            })->values();

            $calificacionesNumericas = $materias
                ->pluck('calificacion')
                ->filter(fn($valor) => is_numeric($valor))
                ->map(fn($valor) => (float) $valor)
                ->values();

            $promedioCuatrimestre = '';

            if ($calificacionesNumericas->count() > 0) {
                $promedioReal = $calificacionesNumericas->avg();
                $promedioCuatrimestre = number_format(floor($promedioReal * 10) / 10, 1, '.', '');
            }

            return [
                'id' => $cuatrimestre->id,
                'numero' => $cuatrimestre->no_cuatrimestre,
                'nombre' => $cuatrimestre->nombre_cuatrimestre,
                'materias' => $materias,
                'promedio' => $promedioCuatrimestre,
            ];
        });

        $promedioGeneral = '';
        $todasLasCalificacionesNumericas = $kardex
            ->flatMap(function ($bloque) {
                return collect($bloque['materias'])
                    ->pluck('calificacion')
                    ->filter(fn($valor) => is_numeric($valor))
                    ->map(fn($valor) => (float) $valor);
            })
            ->values();

        if ($todasLasCalificacionesNumericas->count() > 0) {
            $promedioReal = $todasLasCalificacionesNumericas->avg();
            $promedioGeneral = number_format(floor($promedioReal * 10) / 10, 1, '.', '');
        }

        $escuela = (object) [
            'nombre' => 'Centro Universitario Moctezuma A.C.',
            'CCT' => '12PSU0173I',
        ];

        $rector = (object) [
            'nombre' => 'José Rubén',
            'apellido_paterno' => 'Solórzano',
            'apellido_materno' => 'Carbajal',
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pdf.kardexAlumnoPDF', [
            'alumno' => $alumno,
            'licenciatura' => $licenciatura,
            'generacion' => $generacion,
            'cuatrimestres' => $cuatrimestres,
            'kardex' => $kardex,
            'promedioGeneral' => $promedioGeneral,
            'escuela' => $escuela,
            'rector' => $rector,
        ])->setPaper('legal', 'portrait');

        $matricula = $alumno->datosEscolares?->matricula ?? $alumno->id;

        return $pdf->stream('kardex-' . $matricula . '.pdf');
    }
}
