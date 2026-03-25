<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dia;
use App\Models\Profesor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ProfesorPDFController extends Controller
{
    public function horario_profesor_pdf()
    {
        $id = auth()->user()->profesor->id;

        $profesor = Profesor::findOrFail($id);

        if (!$profesor) {
            abort(404);
        }

        $profesorModelo = $profesor;

        $licenciaturaId = request('licenciatura');
        $generacionId = request('generacion');
        $cuatrimestreId = request('cuatrimestre');

        $consulta = DB::table('horarios')
            ->join('asignacion_materias', 'horarios.asignacion_materia_id', '=', 'asignacion_materias.id')
            ->leftJoin('materias', 'asignacion_materias.materia_id', '=', 'materias.id')
            ->leftJoin('licenciaturas', 'horarios.licenciatura_id', '=', 'licenciaturas.id')
            ->leftJoin('cuatrimestres', 'horarios.cuatrimestre_id', '=', 'cuatrimestres.id')
            ->leftJoin('generaciones', 'horarios.generacion_id', '=', 'generaciones.id')
            ->leftJoin('dias', 'horarios.dia_id', '=', 'dias.id')
            ->where('asignacion_materias.profesor_id', $profesorModelo->id);

        if (!empty($licenciaturaId)) {
            $consulta->where('horarios.licenciatura_id', $licenciaturaId);
        }

        if (!empty($generacionId)) {
            $consulta->where('horarios.generacion_id', $generacionId);
        }

        if (!empty($cuatrimestreId)) {
            $consulta->where('horarios.cuatrimestre_id', $cuatrimestreId);
        }

        $horarios = $consulta
            ->select(
                'horarios.hora',
                'horarios.dia_id',
                'materias.nombre as materia',
                'licenciaturas.nombre as licenciatura',
                'cuatrimestres.nombre_cuatrimestre as cuatrimestre',
                'generaciones.generacion as generacion'
            )
            ->orderByRaw("
                STR_TO_DATE(
                    TRIM(SUBSTRING_INDEX(horarios.hora, '-', 1)),
                    '%h:%i%p'
                ) asc
            ")
            ->orderBy('horarios.dia_id')
            ->get();

        $dias = Dia::orderBy('id')->get();

        $horasDisponibles = $horarios
            ->pluck('hora')
            ->unique()
            ->values()
            ->toArray();

        $matrizHorario = [];

        foreach ($horasDisponibles as $hora) {
            foreach ($dias as $dia) {
                $matrizHorario[$hora][$dia->id] = [];
            }
        }

        foreach ($horarios as $horario) {
            $matrizHorario[$horario->hora][$horario->dia_id][] = [
                'materia' => $horario->materia ?? 'Sin materia',
                'licenciatura' => $horario->licenciatura ?? 'Sin licenciatura',
                'cuatrimestre' => $horario->cuatrimestre ?? 'Sin cuatrimestre',
                'generacion' => $horario->generacion ?? 'Sin generación',
                'hora' => $horario->hora ?? '',
            ];
        }

        $pdf = Pdf::loadView('profesor.pdf.horarioProfesorPDF', [
            'profesor' => $profesorModelo,
            'dias' => $dias,
            'horasDisponibles' => $horasDisponibles,
            'matrizHorario' => $matrizHorario,
            'licenciaturaId' => $licenciaturaId,
            'generacionId' => $generacionId,
            'cuatrimestreId' => $cuatrimestreId,
        ])->setPaper('letter', 'portrait');

        return $pdf->stream('horario-profesor.pdf');
    }
}
