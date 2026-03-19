<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';

    protected $fillable = [
        'hora',
        'dia_id',
        'cuatrimestre_id',
        'licenciatura_id',
        'generacion_id',
        'asignacion_materia_id',
    ];

    // RELACIONES

    public function dia()
    {
        return $this->belongsTo(Dia::class);
    }

    public function cuatrimestre()
    {
        return $this->belongsTo(Cuatrimestre::class);
    }

    public function licenciatura()
    {
        return $this->belongsTo(Licenciatura::class);
    }

    public function generacion()
    {
        return $this->belongsTo(Generacion::class);
    }

    public function asignacionMateria()
    {
        return $this->belongsTo(AsignacionMateria::class, 'asignacion_materia_id');
    }
}
