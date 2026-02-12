<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurpService
{
    // URL real
    protected string $baseUrl = 'https://api.valida-curp.com.mx/curp/obtener_datos/';

    protected string $token = 'pruebas';
    // protected string $token = '8d51c37a-87b1-40c9-8ae6-7b5651406d1f';

    /**
     * Detecto si estoy en modo pruebas
     */
    public function esModoPruebas(): bool
    {
        return $this->token === 'pruebas';
    }

    public function obtenerDatosPorCurp(string $curp): array
    {
        $curp = mb_strtoupper(trim($curp));

        // Si estoy en modo pruebas, puedo regresar datos fake
        if ($this->esModoPruebas() && method_exists($this, 'fakeResponse')) {
            return $this->fakeResponse($curp);
        }

        $response = Http::acceptJson()->get($this->baseUrl, [
            'token' => $this->token,
            'curp' => $curp,
        ]);

        if ($response->successful()) {
            return $response->json() ?? [];
        }

        return [
            'error' => true,
            'message' => 'CURP inválido o error de conexión',
            'status' => $response->status(),
        ];
    }


    protected function fakeResponse(string $curp): array
    {
        $seed = abs(crc32($curp));
        mt_srand($seed);

        $nombres = ['CARLOS', 'ALBERTO', 'MARIA', 'FERNANDA', 'JUAN', 'PEDRO', 'ANGEL', 'SOFIA', 'DANIEL', 'PAOLA'];
        $apellidos = ['NUNEZ', 'PEREZ', 'GARCIA', 'HERNANDEZ', 'LOPEZ', 'MARTINEZ', 'SANCHEZ', 'RAMIREZ', 'FLORES', 'TORRES'];

        $nombre1 = $nombres[mt_rand(0, count($nombres) - 1)];
        $nombre2 = $nombres[mt_rand(0, count($nombres) - 1)];
        $apellidoP = $apellidos[mt_rand(0, count($apellidos) - 1)];
        $apellidoM = $apellidos[mt_rand(0, count($apellidos) - 1)];

        $claveSexo = (mt_rand(0, 1) === 0) ? 'H' : 'M';
        $sexoTexto = ($claveSexo === 'H') ? 'Hombre' : 'Mujer';

        $year = mt_rand(1985, 2006);
        $month = str_pad((string) mt_rand(1, 12), 2, '0', STR_PAD_LEFT);
        $day = str_pad((string) mt_rand(1, 28), 2, '0', STR_PAD_LEFT);
        $fechaNacimiento = "{$year}-{$month}-{$day}";

        return [
            'error' => false,
            'code_error' => 0,
            'error_message' => '',
            'response' => [
                'Solicitante' => [
                    'CURP' => $curp,
                    'Nombres' => "{$nombre1} {$nombre2}",
                    'ApellidoPaterno' => $apellidoP,
                    'ApellidoMaterno' => $apellidoM,
                    'ClaveSexo' => $claveSexo,
                    'Sexo' => $sexoTexto,
                    'FechaNacimiento' => $fechaNacimiento,
                    'Nacionalidad' => 'MEX',
                    'ClaveEntidadNacimiento' => 'GR',
                    'EntidadNacimiento' => 'Guerrero',
                ],
            ],
        ];
    }

}
