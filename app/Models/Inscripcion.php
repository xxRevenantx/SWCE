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
        //
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
