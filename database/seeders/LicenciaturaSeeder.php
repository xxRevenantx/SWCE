<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LicenciaturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $licenciaturas = [
            [
                'nombre' => 'Arquitectura y Diseño de Interiores',
                'slug' => 'arquitectura-y-diseno-de-interiores',
                'RVOE' => null, // Sin RVOE
                'nombre_corto' => 'Arquitectura',
                'logo' => null,
            ],
            [
                'nombre' => 'Contaduría Pública',
                'slug' => 'contabilidad-publica',
                'RVOE' => null, // Sin RVOE
                'nombre_corto' => 'Contaduría Pública',
                'logo' => null,
            ],
            [
                'nombre' => 'Cultura Física y Deportes',
                'slug' => 'cultura-fisica-y-deportes',
                'RVOE' => 'SEG/101/2022',
                'nombre_corto' => 'Física y Deportes',
                'logo' => null,
            ],
            [
                'nombre' => 'Ciencias de la Educación',
                'slug' => 'ciencias-de-la-educacion',
                'RVOE' => 'SEG/102/2022',
                'nombre_corto' => 'Ciencias de la Educación',
                'logo' => null,
            ],
            [
                'nombre' => 'Criminalística, Criminología y Técnicas Periciales',
                'slug' => 'criminologia-criminalista-y-tecnicas-periciales',
                'RVOE' => 'SEG/032/2021',
                'nombre_corto' => 'Criminalística',
                'logo' => null,
            ],
            [
                'nombre' => 'Ciencias Políticas y Administración Pública',
                'slug' => 'ciencias-politicas-y-administracion-publica',
                'RVOE' => null, // Sin RVOE
                'nombre_corto' => 'Ciencias Políticas',
                'logo' => null,
            ],
            [
                'nombre' => 'Administración Empresarial',
                'slug' => 'administracion-empresarial',
                'RVOE' => 'SEG/0011/2021',
                'nombre_corto' => 'Administración Emp.',
                'logo' => null,
            ],
            [
                'nombre' => 'Nutrición',
                'slug' => 'nutricion',
                'RVOE' => null, // Sin RVOE
                'nombre_corto' => 'Nutrición',
                'logo' => null,

            ],
        ];

        foreach ($licenciaturas as $licenciatura) {
            \App\Models\Licenciatura::create($licenciatura);
        }



    }
}
