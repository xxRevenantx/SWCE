<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(\App\Observers\AsignarGeneracionObserver::class)]
class AsignarGeneracion extends Model
{
    use HasFactory;
    protected $table = 'asignar_generaciones';

    protected $fillable = [
        'licenciatura_id',
        'generacion_id',
        'orden'
    ];


    // RELACIONES
    public function licenciatura()
    {
        return $this->belongsTo(Licenciatura::class);
    }

    public function generacion()
    {
        return $this->belongsTo(Generacion::class);
    }

}
