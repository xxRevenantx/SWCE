<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Cuatrimestre;
use App\Models\Generacion;
use App\Models\Inscripcion;
use App\Models\Licenciatura;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class EstudiantePDFController extends Controller
{
    public function mi_expediente()
    {
        $id = auth()->user()->alumno->id;

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

    public function mi_horario()
    {
        $id = auth()->user()->alumno->id;

        $alumno = \App\Models\Inscripcion::findOrFail($id);


        $horario = \App\Models\Horario::query()
            ->where('licenciatura_id', $alumno->licenciatura_id)
            ->where('generacion_id', $alumno->generacion_id)
            ->where('cuatrimestre_id', $alumno->cuatrimestre_id)
            ->with(['asignacionMateria.materia', 'dia'])
            ->get();

        $data = [
            'licenciatura' => Licenciatura::find($alumno->licenciatura_id),
            'generacion' => Generacion::find($alumno->generacion_id),
            'cuatrimestre' => Cuatrimestre::find($alumno->cuatrimestre_id),
            'horario' => $horario,
        ];

        $pdf = Pdf::loadView('admin.pdf.horarioPDF', $data)->setPaper('letter', 'portrait');
        return $pdf->stream("HORARIO_" . mb_strtoupper($data['licenciatura']->nombre) . "_" . mb_strtoupper($data['generacion']->generacion) . "_" . mb_strtoupper($data['cuatrimestre']->no_cuatrimestre) . "°_CUATRIMESTRE.pdf");
    }

    public function mi_boleta($cuatrimestre)
    {
        $inscripcionId = auth()->user()->alumno->id;

        $inscripcion = \App\Models\Inscripcion::with(['alumno', 'licenciatura', 'generacion'])
            ->findOrFail($inscripcionId);

        /*
        |------------------------------------------------------------
        | Obtener los cuatrimestres que este alumno sí tiene calificados
        |------------------------------------------------------------
        */
        $cuatrimestresValidos = \App\Models\Calificacion::query()
            ->join('asignacion_materias', 'calificaciones.asignacion_materia_id', '=', 'asignacion_materias.id')
            ->where('calificaciones.inscripcion_id', $inscripcionId)
            ->pluck('asignacion_materias.cuatrimestre_id')
            ->unique()
            ->values();

        /*
        |------------------------------------------------------------
        | Validar que el cuatrimestre de la URL esté permitido
        |------------------------------------------------------------
        */
        if (!$cuatrimestresValidos->contains((int) $cuatrimestre)) {
            abort(404);
            // O redirigir con mensaje, más abajo te dejo esa versión
        }

        $cuatrimestreModelo = \App\Models\Cuatrimestre::findOrFail($cuatrimestre);

        $calificaciones = \App\Models\Calificacion::query()
            ->join('asignacion_materias', 'calificaciones.asignacion_materia_id', '=', 'asignacion_materias.id')
            ->join('materias', 'materias.id', '=', 'asignacion_materias.materia_id')
            ->where('calificaciones.inscripcion_id', $inscripcionId)
            ->where('asignacion_materias.cuatrimestre_id', $cuatrimestre)
            ->orderByRaw("COALESCE(materias.clave, '') ASC")
            ->select(
                'calificaciones.*',
                'materias.nombre as materia_nombre',
                'materias.clave as materia_clave',
                'materias.creditos',
                'materias.calificable'
            )
            ->get();

        if ($calificaciones->isEmpty()) {
            abort(404);
        }

        $nombreAlumno = trim(
            ($inscripcion->alumno->nombre ?? '') . '_' .
            ($inscripcion->alumno->apellido_paterno ?? '') . '_' .
            ($inscripcion->alumno->apellido_materno ?? '')
        );

        $data = [
            'calificaciones' => $calificaciones,
            'cuatrimestre' => $cuatrimestreModelo,
            'licenciatura' => $inscripcion->licenciatura,
            'generacion' => $inscripcion->generacion,
            'alumno' => $inscripcion,
        ];

        $pdf = Pdf::loadView('admin.pdf.boletaCalificacionPDF', $data)
            ->setPaper('letter', 'portrait');

        return $pdf->stream(
            "BOLETA_CALIFICACIONES_" .
            $nombreAlumno . "_" .
            $cuatrimestreModelo->no_cuatrimestre . "°_CUATRIMESTRE.pdf"
        );
    }
}
