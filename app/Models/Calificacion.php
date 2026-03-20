<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    /** @use HasFactory<\Database\Factories\CalificacionFactory> */
    use HasFactory;

    protected $table = 'calificaciones';


    protected $fillable = [
        'inscripcion_id',
        'asignacion_materia_id',
        'calificacion',
        'fecha_captura'
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class);
    }

    public function asignacionMateria()
    {
        return $this->belongsTo(AsignacionMateria::class);
    }
}
