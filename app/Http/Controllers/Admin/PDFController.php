<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}
