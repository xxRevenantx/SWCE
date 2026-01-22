<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosContacto extends Model
{

    use HasFactory;
    protected $table = 'datos_contactos';

    protected $fillable = [
        'alumno_id',
        'calle',
        'numero_exterior',
        'numero_interior',
        'colonia',
        'municipio',
        'codigo_postal',
        'celular',
        'telefono',
        'bachillerato_procedente',
        'ciudad_id',
        'estado_id',
        'pais_id',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function ciudad()
    {
        return $this->belongsTo(City::class, 'ciudad_id');
    }

    public function estado()
    {
        return $this->belongsTo(State::class, 'estado_id');
    }

    public function pais()
    {
        return $this->belongsTo(Country::class, 'pais_id');
    }
}
