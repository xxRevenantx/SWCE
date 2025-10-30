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


}
