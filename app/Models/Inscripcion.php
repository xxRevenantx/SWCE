<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    /** @use HasFactory<\Database\Factories\InscripcionFactory> */

    public $table = 'inscripciones';
    use HasFactory;

    protected $fillable = [
        'alumno_id',
        'licenciatura_id',
        'generacion_id',
        'cuatrimestre_id',
        'status',
        'fecha_inscripcion',
    ];

    protected $with = ['alumno.datosContacto', 'alumno.datosEscolares'];


    // RELACIONES
    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }
    public function licenciatura()
    {
        return $this->belongsTo(Licenciatura::class);
    }
    public function generacion()
    {
        return $this->belongsTo(Generacion::class);
    }

    public function cuatrimestre()
    {
        return $this->belongsTo(Cuatrimestre::class);
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class);
    }
}
