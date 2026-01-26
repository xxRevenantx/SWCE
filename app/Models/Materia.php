<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    /** @use HasFactory<\Database\Factories\MateriaFactory> */
    use HasFactory;

    protected $fillable = [
        'nombre',
        'slug',
        'clave',
        'creditos',
        'calificable',
        'licenciatura_id',
        'cuatrimestre_id',
    ];

    public function licenciatura()
    {
        return $this->belongsTo(Licenciatura::class);
    }

    public function cuatrimestre()
    {
        return $this->belongsTo(Cuatrimestre::class);
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionMateria::class);
    }
}
