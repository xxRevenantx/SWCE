<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class CalificacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $calificaciones;
    public $inscripcion;
    public $licenciatura;
    public $generacion;
    public $cuatrimestre;
    public $ciclo_escolar;


    public function __construct($calificaciones, $inscripcion, $licenciatura, $generacion, $cuatrimestre)
    {
        $this->calificaciones = $calificaciones;
        $this->inscripcion = $inscripcion;
        $this->licenciatura = $licenciatura;
        $this->generacion = $generacion;
        $this->cuatrimestre = $cuatrimestre;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Calificaciones del ' . $this->cuatrimestre->no_cuatrimestre . '° Cuatrimestre | ' .
            $this->inscripcion->nombre . ' ' . $this->inscripcion->apellido_paterno . ' ' . $this->inscripcion->apellido_materno,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'admin.emails.calificaciones',
            // Si prefieres explícito:
            // with: [
            //     'calificaciones' => $this->calificaciones,
            //     ...
            // ],
        );
    }

    public function attachments(): array
    {

        $pdf = Pdf::loadView('admin.pdf.boletaCalificacionPDF', [
            'calificaciones' => $this->calificaciones,
            'inscripcion' => $this->inscripcion,
            'licenciatura' => $this->licenciatura,
            'generacion' => $this->generacion,
            'cuatrimestre' => $this->cuatrimestre,
        ])->setPaper('letter', 'portrait');

        $nombreAlumno = trim(
            ($this->inscripcion->nombre ?? '') . '_' .
            ($this->inscripcion->apellido_paterno ?? '') . '_' .
            ($this->inscripcion->apellido_materno ?? '')
        );

        $nombrePdf = 'CALIFICACIONES_' .
            ($this->cuatrimestre->cuatrimestre ?? '') . '°_CUATRIMESTRE_' .
            ($nombreAlumno !== '' ? $nombreAlumno : 'ALUMNO') . '.pdf';

        return [
            Attachment::fromData(fn() => $pdf->output(), $nombrePdf)
                ->withMime('application/pdf'),
        ];
    }
}
