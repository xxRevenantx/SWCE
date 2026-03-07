<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documentacion extends Model
{

    protected $table = 'documentacion';

    protected $fillable = [
        'alumno_id',
        'url_curp',
        'url_acta_nacimiento',
        'url_certificado_estudios',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }




}
