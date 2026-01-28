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

        $inscripcion = \App\Models\Inscripcion::with(['alumno'])->find($id);

        $alumno = $inscripcion->alumno;

        dd($alumno);


        if (!$alumno) {
            abort(404);
        }

        $data = [
            'alumno' => $alumno,
        ];

        $pdf = Pdf::loadView('admin.pdf.expedienteAlumnoPDF', $data)->setPaper('letter', 'portrait');
        return $pdf->stream("Expediente_{$alumno->nombre}.pdf");
    }
}
