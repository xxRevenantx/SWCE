<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('DEFAULT_ADMIN_EMAIL', 'swce@gmail.com');
        $password = env('DEFAULT_ADMIN_PASSWORD', '12345678');

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'username' => 'SWCE',
                'password' => bcrypt($password),
                'email_verified_at' => now(), // opcional si usas verificación
            ]
        );

        // Evita error si el rol no existe por orden de seeders
        if (!$user->hasRole('Admin')) {
            $user->assignRole('Admin');
        }
    }

}
