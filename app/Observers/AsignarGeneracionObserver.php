<?php

namespace App\Observers;

use App\Models\AsignarGeneracion;

class AsignarGeneracionObserver
{
    public function creating(AsignarGeneracion $asignarGeneracion): void
    {
        $asignarGeneracion->orden = AsignarGeneracion::max('orden') + 1;
    }


    public function deleted(AsignarGeneracion $asignarGeneracion)
    {
        // Actualizar los estudiantes
        AsignarGeneracion::where('orden', '>', $asignarGeneracion->orden)
            ->decrement('orden');
    }
}
