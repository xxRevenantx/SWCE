<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CuatrimestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $cuatrimestres = [
            ['no_cuatrimestre' => '1', 'nombre_cuatrimestre' => "1° CUATRIMESTRE", 'mes_id' => 1],
            ['no_cuatrimestre' => '2', 'nombre_cuatrimestre' => "2° CUATRIMESTRE", 'mes_id' => 2],
            ['no_cuatrimestre' => '3', 'nombre_cuatrimestre' => "3° CUATRIMESTRE", 'mes_id' => 3],
            ['no_cuatrimestre' => '4', 'nombre_cuatrimestre' => "4° CUATRIMESTRE", 'mes_id' => 1],
            ['no_cuatrimestre' => '5', 'nombre_cuatrimestre' => "5° CUATRIMESTRE", 'mes_id' => 2],
            ['no_cuatrimestre' => '6', 'nombre_cuatrimestre' => "6° CUATRIMESTRE", 'mes_id' => 3],
            ['no_cuatrimestre' => '7', 'nombre_cuatrimestre' => "7° CUATRIMESTRE", 'mes_id' => 1],
            ['no_cuatrimestre' => '8', 'nombre_cuatrimestre' => "8° CUATRIMESTRE", 'mes_id' => 2],
            ['no_cuatrimestre' => '9', 'nombre_cuatrimestre' => "9° CUATRIMESTRE", 'mes_id' => 3],


        ];

        foreach ($cuatrimestres as $cuatrimestre) {
            \App\Models\Cuatrimestre::create($cuatrimestre);
        }

    }
}
