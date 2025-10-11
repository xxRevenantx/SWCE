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
            UserSeeder::class,

            // Add other seeders here
        ]);

        // User::factory()->create([
        //     'name' => 'Centro Universitario Moctezuma AC',
        //     'email' => 'centrouniversitariomoctezuma@gmail.com',
        //     'password' => bcrypt('12345678')
        // ])->assignRole('Admin');
        // $this->call(UserSeeder::class);
    }
}
