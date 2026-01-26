<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionMateria extends Model
{

    use HasFactory;
    protected $table = 'asignacion_materias';

    protected $fillable = [
        'materia_id',
        'cuatrimestre_id',
        'licenciatura_id',
        'profesor_id',
    ];

    // RELACIONES
    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    public function cuatrimestre()
    {
        return $this->belongsTo(Cuatrimestre::class);
    }

    public function licenciatura()
    {
        return $this->belongsTo(Licenciatura::class);
    }

    public function profesor()
    {
        return $this->belongsTo(Profesor::class);
    }


}
