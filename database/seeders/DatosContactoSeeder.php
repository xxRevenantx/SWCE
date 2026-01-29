<?php

namespace Database\Seeders;

use App\Models\Alumno;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatosContactoSeeder extends Seeder
{
    public function run(): void
    {
        $alumnos = Alumno::query()->select('id')->get();

        if ($alumnos->isEmpty()) {
            return;
        }

        // Tomo catálogos (pueden estar vacíos, entonces se van a null)
        $paises = DB::table('countries')->pluck('id')->values();
        $estados = DB::table('states')->pluck('id')->values();
        $ciudades = DB::table('cities')->pluck('id')->values();

        $now = now();

        foreach ($alumnos as $alumno) {

            // ✅ 1 registro por alumno (unique alumno_id)
            $existe = DB::table('datos_contactos')
                ->where('alumno_id', $alumno->id)
                ->exists();

            if ($existe) {
                continue;
            }

            // ✅ ids opcionales
            $paisId = $paises->isNotEmpty() ? $paises->random() : null;
            $estadoId = $estados->isNotEmpty() ? $estados->random() : null;
            $ciudadId = $ciudades->isNotEmpty() ? $ciudades->random() : null;

            // ✅ genero data “realista” para MX (puedes ajustar)
            $calle = $this->calleAleatoria();
            $colonia = $this->coloniaAleatoria();
            $municipio = $this->municipioAleatorio();

            DB::table('datos_contactos')->insert([
                'alumno_id' => $alumno->id,
                'calle' => $calle,
                'numero_exterior' => (string) random_int(1, 999),
                'numero_interior' => random_int(0, 1) ? (string) random_int(1, 30) : null,
                'colonia' => $colonia,
                'municipio' => $municipio,
                'codigo_postal' => (string) random_int(39000, 41999),
                'celular' => $this->celularMx(),
                'telefono' => random_int(0, 1) ? $this->telefonoFijoMx() : null,
                'bachillerato_procedente' => $this->bachilleratoProcedente(),
                'ciudad_id' => $ciudadId,
                'estado_id' => $estadoId,
                'pais_id' => $paisId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /** Yo genero un celular tipo MX de 10 dígitos */
    private function celularMx(): string
    {
        // arranca con 7 u 8 o 9 para verse “real”
        $inicio = [7, 8, 9][array_rand([7, 8, 9])];
        $resto = '';
        for ($i = 0; $i < 9; $i++) {
            $resto .= (string) random_int(0, 9);
        }
        return $inicio . $resto;
    }

    /** Yo genero teléfono fijo (10 dígitos) */
    private function telefonoFijoMx(): string
    {
        // ejemplo “755” (Gro) + 7 dígitos
        $lada = ['755', '747', '762', '733', '222', '999'][array_rand(['755', '747', '762', '733', '222', '999'])];
        $resto = '';
        for ($i = 0; $i < 7; $i++) {
            $resto .= (string) random_int(0, 9);
        }
        return $lada . $resto;
    }

    private function bachilleratoProcedente(): string
    {
        $ops = [
            'Bachillerato General Estatal',
            'CBTis',
            'CONALEP',
            'CECyTE',
            'Preparatoria Abierta',
            'Colegio de Bachilleres',
            'Preparatoria Particular',
        ];
        return $ops[array_rand($ops)];
    }

    private function calleAleatoria(): string
    {
        $ops = [
            'Francisco I. Madero',
            'Benito Juárez',
            'Hidalgo',
            'Morelos',
            'Niños Héroes',
            'Reforma',
            'Guerrero',
            'Allende',
        ];
        return $ops[array_rand($ops)];
    }

    private function coloniaAleatoria(): string
    {
        $ops = [
            'Centro',
            'Esquipula',
            'San José',
            'Las Flores',
            'La Loma',
            'El Mirador',
            'Lázaro Cárdenas',
            'La Esperanza',
        ];
        return $ops[array_rand($ops)];
    }

    private function municipioAleatorio(): string
    {
        $ops = [
            'Pungarabato',
            'Iguala de la Independencia',
            'Chilpancingo de los Bravo',
            'Zihuatanejo de Azueta',
            'Acapulco de Juárez',
            'Coyuca de Benítez',
        ];
        return $ops[array_rand($ops)];
    }
}
