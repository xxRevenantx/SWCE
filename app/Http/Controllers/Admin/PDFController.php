<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Generacion;
use App\Models\Inscripcion;
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
}
