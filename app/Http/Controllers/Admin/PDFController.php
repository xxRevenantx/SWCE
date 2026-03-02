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

        $pdf = Pdf::loadView('admin.pdf.listaMatriculaPDF', $data)->setPaper('letter', 'landscape');
        return $pdf->stream("LISTA_MATRICULA.pdf");
    }

    // BOLETA DE CALIFICACIONES
    public function boletaCalificacion($id)
    {
        $calificaciones = \App\Models\Calificacion::query()
            ->where('inscripcion_id', $id)
            ->join('asignacion_materias', 'asignacion_materias.id', '=', 'calificaciones.asignacion_materia_id')
            ->join('materias', 'materias.id', '=', 'asignacion_materias.materia_id')
            ->orderByRaw("COALESCE(materias.clave,'') ASC")
            ->select('calificaciones.*')
            ->get();

        $cuatrimestre = Inscripcion::where('id', $id)->with('cuatrimestre')->first()->cuatrimestre;

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

        /**
         * 2) Calificaciones SOLO de esas inscripciones
         */
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

        /**
         * 3) Materias (columnas)
         *    Si no hay ninguna calificación todavía, aquí quedaría vacío.
         *    En ese caso, mandamos un arreglo vacío y el PDF mostrará solo alumnos + promedio.
         */
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

        /**
         * 4) Matriz: [inscripcion_id][asignacion_materia_id] = calificacion
         */
        $matriz = [];
        foreach ($calificacionesRaw as $r) {
            $matriz[$r->inscripcion_id][$r->asignacion_materia_id] = $r->calificacion;
        }

        /**
         * 5) Promedio por alumno (solo considera numéricos)
         *    Si no tiene calificaciones, queda null
         */
        $promedios = [];
        foreach ($alumnos as $a) {
            $valores = [];

            foreach ($materias as $m) {
                $valor = $matriz[$a->inscripcion_id][$m->asignacion_materia_id] ?? null;
                if (is_numeric($valor)) {
                    $valores[] = (float) $valor;
                }
            }

            $promedios[$a->inscripcion_id] = count($valores)
                ? round(array_sum($valores) / count($valores), 1)
                : null;
        }

        /**
         * 6) Datos generales
         */
        $lic = Licenciatura::query()->select('id', 'nombre')->find($licenciatura);
        $gen = Generacion::query()->select('id', 'generacion')->find($generacion);
        $cuat = Cuatrimestre::query()->select('id', 'no_cuatrimestre')->find($cuatrimestre);

        $nombreLicenciatura = $lic?->nombre ?? 'LICENCIATURA_DESCONOCIDA';
        $nombreGeneracion = $gen?->generacion ?? 'GEN_DESCONOCIDA';
        $nombreCuatrimestre = $cuat?->no_cuatrimestre ?? 'CUATRIMESTRE_DESCONOCIDO';

        /**
         * 7) PDF
         */
        $data = [
            'materias' => $materias,
            'alumnos' => $alumnos,
            'matriz' => $matriz,
            'promedios' => $promedios,

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
}
