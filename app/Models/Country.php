<?php

namespace App\Models;

use Altwaireb\Countries\Models\Country as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Country extends Model
{
     use HasFactory;

    protected $table = 'countries';

    protected $fillable = [
        'name', 'iso2', 'iso3', 'numeric_code', 'phonecode',
        'capital', 'currency', 'currency_name', 'currency_symbol',
        'tld', 'native', 'region', 'subregion',
    ];

    // Relación con estados y ciudades (por ID)
    public function state()
    {
        return $this->hasMany(State::class, 'country_id');
    }

    public function city()
    {
        return $this->hasMany(City::class, 'country_id');
    }
}
