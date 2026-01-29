<?php

namespace Database\Seeders;

use App\Models\Alumno;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatosEscolaresSeeder extends Seeder
{
    public function run(): void
    {
        $alumnos = Alumno::query()
            ->select('id')
            ->get();

        if ($alumnos->isEmpty()) {
            return;
        }

        $now = now();

        foreach ($alumnos as $alumno) {

            // ✅ Si ya existe datos_escolares para este alumno, me salto
            $existe = DB::table('datos_escolares')
                ->where('alumno_id', $alumno->id)
                ->exists();

            if ($existe) {
                continue;
            }

            // ✅ Genero matricula UNIQUE
            $matricula = $this->generarMatriculaUnica($alumno->id);

            // ✅ Genero folio UNIQUE (nullable pero aquí lo llenamos)
            $folio = $this->generarFolioUnico();

            DB::table('datos_escolares')->insert([
                'alumno_id' => $alumno->id,
                'matricula' => $matricula,
                'folio' => $folio,
                'foto' => null, // aquí puedes poner una ruta fake si quieres
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Genero una matrícula sencilla pero única:
     *  YY + "CUM" + alumno_id padded + random(3)
     *  Ej: 26CUM000035847
     */
    private function generarMatriculaUnica(int $alumnoId): string
    {
        $yy = now()->format('y'); // 2 dígitos
        $base = $yy . 'CUM' . str_pad((string) $alumnoId, 6, '0', STR_PAD_LEFT);

        $intentos = 0;
        do {
            $intentos++;
            $matricula = $base . random_int(100, 999); // 3 dígitos
            $existe = DB::table('datos_escolares')->where('matricula', $matricula)->exists();
        } while ($existe && $intentos < 50);

        // Si por algo extremo sigue chocando, meto un sufijo extra
        if ($existe) {
            $matricula = $base . random_int(1000, 9999);
        }

        return $matricula;
    }

    /**
     * Genero un folio tipo:
     *  FOL-YYYY-XXXXXXXX
     */
    private function generarFolioUnico(): string
    {
        $anio = now()->format('Y');

        $intentos = 0;
        do {
            $intentos++;
            $folio = 'FOL-' . $anio . '-' . strtoupper(Str::random(8));
            $existe = DB::table('datos_escolares')->where('folio', $folio)->exists();
        } while ($existe && $intentos < 50);

        return $folio;
    }
}
