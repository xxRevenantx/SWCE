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


}
