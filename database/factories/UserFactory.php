<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $nombre = $this->faker->firstName();
        $apellido = $this->faker->lastName();

        $baseUsername = Str::of($nombre . '.' . $apellido)
            ->lower()
            ->replace(' ', '')
            ->ascii()
            ->toString();

        return [
            'username' => $this->faker->unique()->lexify($baseUsername),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),

            // Si tu tabla los tiene:
            'photo' => null,
            'status' => true,
            'order' => 1,
            'change_password' => false
        ];
    }

    public function estudiante(): static
    {
        return $this->state(fn() => []);
    }

    public function profesor(): static
    {
        return $this->state(fn() => []);
    }
}
