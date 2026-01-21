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
        // generales
        'user_id',
        'CURP',
        'matricula',
        'folio',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'sexo',
        'pais_nacimiento',
        'estado_nacimiento',
        'lugar_nacimiento',

        // contacto
        'calle',
        'num_exterior',
        'num_interior',
        'colonia',
        'codigo_postal',
        'municipio_residencia',
        'estado_residencia_id',
        'ciudad_residencia_id',
        'celular',
        'telefono_fijo',
        'correo_electronico',
        'tutor',

        // escolares
        'bachillerato_procedente',
        'licenciatura_id',
        'generacion_id',
        'cuatrimestre_id',

        // otros
        'foto',
        'status',
    ];
    // RELACIONES

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //COUNTRIES
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // CITIES
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // STATES
    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
