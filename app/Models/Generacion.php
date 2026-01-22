<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generacion extends Model
{
    /** @use HasFactory<\Database\Factories\GeneracionFactory> */
    use HasFactory;

    protected $table = 'generaciones';

    protected $fillable = [
        'generacion',
        'status'
    ];


    // RELACION CON ASIGNAR GENERACIONES
    public function asignarGeneraciones()
    {
        return $this->hasMany(AsignarGeneracion::class);
    }

    // RELACION CON INSCRIPCIONES
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }


}
