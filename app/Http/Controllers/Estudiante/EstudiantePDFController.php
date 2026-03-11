<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Cuatrimestre;
use App\Models\Generacion;
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
}
