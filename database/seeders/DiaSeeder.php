<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dias = [
            ['dia' => 'LUNES'],
            ['dia' => 'MARTES'],
            ['dia' => 'MIERCOLES'],
            ['dia' => 'JUEVES'],
            ['dia' => 'VIERNES'],

        ];

        foreach ($dias as $dia) {
            \App\Models\Dia::create($dia);
        }
    }
}
