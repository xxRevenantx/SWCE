<?php

namespace App\Support;

use Illuminate\Support\Collection;

class MyInspiring
{
    public static function quotes(): Collection
    {
        return collect([
            'La educación es el arma más poderosa para cambiar el mundo. - Nelson Mandela',
            'El único lugar donde el éxito viene antes que el trabajo es en el diccionario. - Vidal Sassoon',
            'La mente es como un paracaídas, solo funciona si se abre. - Albert Einstein',
            'El aprendizaje nunca agota la mente. - Leonardo da Vinci',
            'La educación no es preparación para la vida; la educación es la vida misma. - John Dewey',
            'El conocimiento es la mejor inversión que puedes hacer. - Benjamin Franklin',
            'La educación es el pasaporte para el futuro, el mañana pertenece a quienes se preparan hoy. - Malcolm X',
            'El éxito es la suma de pequeños esfuerzos repetidos día tras día. - Robert Collier',
            'La curiosidad es la mecha en la vela del aprendizaje. - William Arthur Ward',
            'No hay atajos para cualquier lugar que valga la pena ir. - Beverly Sills',
            'La educación es el movimiento de la oscuridada la luz. - Allan Bloom',
            'El aprendizaje es un tesoro que seguirá a su dueño a todas partes. - Proverbio chino',
            'La educación es el fundamento sobre el cual construimos nuestro futuro. - Christine Gregoire',
            'El éxito no es la clave de la felicidad. La felicidad es la clave del éxito. - Albert Schweitzer',
            'La educación es la llave que abre las puertas del mundo. - Oprah Winfrey',
            'El verdadero signo de la inteligencia no es el conocimiento sino la imaginación. - Albert Einstein',
            'La educación es el arma más poderosa que puedes usar para cambiar el mundo. - Nelson Mandela',
            'El aprendizaje es un viaje, no un destino. - Carl Rogers',
            'La educación es el pasaporte para el futuro, el mañana pertenece a quienes se preparan hoy. - Malcolm X',

        ]);
    }

    public static function random(): string
    {
        return static::quotes()->random();
    }
}
