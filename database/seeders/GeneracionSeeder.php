<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneracionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $generaciones = [
            ['generacion' => '2020-2023', 'status' => 'true'],
            ['generacion' => '2021-2024', 'status' => 'true'],
            ['generacion' => '2022-2025', 'status' => 'true'],
            ['generacion' => '2023-2026', 'status' => 'true'],
            ['generacion' => '2024-2027', 'status' => 'true'],
            ['generacion' => '2025-2028', 'status' => 'true'],
        ];

        foreach ($generaciones as $generacion) {
            \App\Models\Generacion::create($generacion);
        }
    }
}
