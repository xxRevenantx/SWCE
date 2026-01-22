<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosEscolares extends Model
{
    use HasFactory;
    protected $table = 'datos_escolares';

    protected $fillable = [
        'alumno_id',
        'matricula',
        'folio',
        'foto',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }
}
