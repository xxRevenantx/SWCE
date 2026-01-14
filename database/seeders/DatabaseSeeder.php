<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        $this->call([
            RoleSeeder::class,
            DiaSeeder::class,
                // CountriesTableSeeder::class,
            MesSeeder::class,

            // Add other seeders here
        ]);

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
