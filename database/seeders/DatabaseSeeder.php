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
            CountriesTableSeeder::class,
            LicenciaturaSeeder::class,
            MesSeeder::class,
            CuatrimestreSeeder::class,
            GeneracionSeeder::class,

            // Add other seeders here
        ]);

        // 2️⃣ LUEGO crear admin
        $email = env('DEFAULT_ADMIN_EMAIL', 'swce@gmail.com');
        $password = env('DEFAULT_ADMIN_PASSWORD', 'swce#2026');

        $user = \App\Models\User::updateOrCreate(
            ['email' => $email],
            [
                'username' => 'SWCE',
                'password' => bcrypt($password),
                'email_verified_at' => now(),
            ]
        );

        // 3️⃣ FINALMENTE asignar rol
        if (!$user->hasRole('Admin')) {
            $user->assignRole('Admin');
        }
    }
}
