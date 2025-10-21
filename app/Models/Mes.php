<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mes extends Model
{

    use \Illuminate\Database\Eloquent\Factories\HasFactory;


    protected $table = 'meses';

    protected $fillable = ['meses', 'meses_corto'];


    public function cuatrimestres()
    {
        return $this->hasMany(Cuatrimestre::class);
    }

}
