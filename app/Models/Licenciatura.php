<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licenciatura extends Model
{
    /** @use HasFactory<\Database\Factories\LicenciaturaFactory> */
    use HasFactory;

    protected $fillable = [
        'nombre',
        'slug',
        'RVOE',
        'nombre_corto',
        'logo'
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

    // RELACION CON MATERIAS
    public function materias()
    {
        return $this->hasMany(Materia::class);
    }
}
