<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
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
}
