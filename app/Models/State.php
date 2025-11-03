<?php

namespace App\Models;

use Altwaireb\Countries\Models\State as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
      use HasFactory;

    protected $table = 'states';

    protected $fillable = [
        'name', 'country_id',
        // latitude, longitude, is_active, etc. si las usas
    ];

    public function countries()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function city()
    {
        return $this->hasMany(City::class, 'state_id');
    }
}
