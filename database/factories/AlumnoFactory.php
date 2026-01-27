<?php

namespace Database\Factories;

use App\Models\Alumno;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Alumno>
 */
class AlumnoFactory extends Factory
{
    protected $model = Alumno::class;

    public function definition(): array
    {
        $sexo = $this->faker->randomElement(['M', 'F']);

        return [
            // ✅ user_id es UNIQUE, así que creamos un user nuevo siempre
            'user_id' => User::factory(),

            // ✅ curp UNIQUE (18 chars). No generamos curp real; solo única con 18 chars.
            'curp' => strtoupper(Str::substr(
                preg_replace('/[^A-Z0-9]/', '', Str::random(24)),
                0,
                18
            )),

            'nombre' => $this->faker->firstName($sexo === 'M' ? 'male' : 'female'),
            'apellido_paterno' => $this->faker->lastName(),
            'apellido_materno' => $this->faker->lastName(),

            'fecha_nacimiento' => $this->faker->dateTimeBetween('-30 years', '-16 years')->format('Y-m-d'),
            'sexo' => $sexo,
        ];
    }
}
