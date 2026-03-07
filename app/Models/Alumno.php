<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alumno extends Model
{

    use HasFactory;

    use SoftDeletes;

    protected $table = 'alumnos';

    protected $fillable = [
        'user_id',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'curp',
        'sexo'
        // Otros campos relevantes del alumno
    ];

    // Relación con DatosContacto
    public function datosContacto()
    {
        return $this->hasOne(DatosContacto::class);
    }

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con DatosEscolares
    public function datosEscolares()
    {
        return $this->hasOne(DatosEscolares::class);
    }

    // Relación con Inscripciones
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    // Relación con Documentacion
    public function documentacion()
    {
        return $this->hasOne(Documentacion::class);
    }

}
