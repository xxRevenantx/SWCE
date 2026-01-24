<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuatrimestre extends Model
{
    /** @use HasFactory<\Database\Factories\CuatrimestreFactory> */
    use HasFactory;


    protected $fillable = [
        'no_cuatrimestre',
        'nombre_cuatrimestre',
        'mes_id',
    ];

    // RELACION CON MES

    public function mes()
    {
        return $this->belongsTo(Mes::class);
    }

    // RELACION CON INSCRIPCIONES
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    // RELACION CON MATERIAS
    public function materias()
    {
        return $this->hasMany(Materia::class);
    }

}
