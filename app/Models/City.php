<?php

namespace App\Models;

use Altwaireb\Countries\Models\City as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
     use HasFactory;

    protected $table = 'cities';

    protected $fillable = [
        'name', 'state_id', 'country_id',
        // latitude, longitude, is_active, etc.
    ];

    public function states()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function countries()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
