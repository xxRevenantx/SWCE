<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\Cuatrimestre;
use App\Models\Dia;
use App\Models\Generacion;
use App\Models\Licenciatura;
use App\Models\Profesor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ProfesorPdfController extends Controller
{
    public function obtenerColorLicenciatura(?int $licenciaturaId): string
    {
        return match ($licenciaturaId) {
            1 => '#16a34a',
            2 => '#2563eb',
            3 => '#0f766e',
            4 => '#b91c1c',
            5 => '#7c3aed',
            6 => '#ea580c',
            default => '#334155',
        };
    }

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

        // Se obtienen los nombres reales de los filtros para mostrarlos en el PDF
        $licenciaturaSeleccionada = !empty($licenciaturaId)
            ? Licenciatura::find($licenciaturaId)
            : null;

        $generacionSeleccionada = !empty($generacionId)
            ? Generacion::find($generacionId)
            : null;

        $cuatrimestreSeleccionado = !empty($cuatrimestreId)
            ? Cuatrimestre::find($cuatrimestreId)
            : null;

        $nombreLicenciatura = $licenciaturaSeleccionada?->nombre ?? 'Todas';
        $nombreGeneracion = $generacionSeleccionada?->generacion ?? 'Todas';
        $nombreCuatrimestre = $cuatrimestreSeleccionado?->nombre_cuatrimestre ?? 'Todos';

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
                'horarios.licenciatura_id',
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
            $colorLicenciatura = $this->obtenerColorLicenciatura($horario->licenciatura_id);

            $matrizHorario[$horario->hora][$horario->dia_id][] = [
                'materia' => $horario->materia ?? 'Sin materia',
                'licenciatura' => $horario->licenciatura ?? 'Sin licenciatura',
                'cuatrimestre' => $horario->cuatrimestre ?? 'Sin cuatrimestre',
                'generacion' => $horario->generacion ?? 'Sin generación',
                'hora' => $horario->hora ?? '',
                'color' => $colorLicenciatura,
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
            'nombreLicenciatura' => $nombreLicenciatura,
            'nombreGeneracion' => $nombreGeneracion,
            'nombreCuatrimestre' => $nombreCuatrimestre,
        ])->setPaper('letter', 'portrait');

        return $pdf->stream('horario-profesor.pdf');
    }


    // Método para generar el PDF de la lista de alumnos del profesor
    public function lista_alumnos_pdf()
    {
        $id = auth()->user()->profesor->id;

        $profesor = Profesor::findOrFail($id);

        $licenciaturaId = request('licenciatura');
        $generacionId = request('generacion');
        $cuatrimestreId = request('cuatrimestre');
        $asignacionMateriaId = request('asignacion_materia');
        $search = trim((string) request('search', ''));

        if (
            empty($licenciaturaId) ||
            empty($generacionId) ||
            empty($cuatrimestreId) ||
            empty($asignacionMateriaId)
        ) {
            abort(404, 'Faltan filtros para generar la lista de asistencia.');
        }

        $materiaSeleccionada = DB::table('asignacion_materias')
            ->join('materias', 'asignacion_materias.materia_id', '=', 'materias.id')
            ->join('horarios', 'horarios.asignacion_materia_id', '=', 'asignacion_materias.id')
            ->leftJoin('licenciaturas', 'horarios.licenciatura_id', '=', 'licenciaturas.id')
            ->leftJoin('cuatrimestres', 'horarios.cuatrimestre_id', '=', 'cuatrimestres.id')
            ->leftJoin('generaciones', 'horarios.generacion_id', '=', 'generaciones.id')
            ->where('asignacion_materias.id', $asignacionMateriaId)
            ->where('asignacion_materias.profesor_id', $id)
            ->where('horarios.licenciatura_id', $licenciaturaId)
            ->where('horarios.cuatrimestre_id', $cuatrimestreId)
            ->where('horarios.generacion_id', $generacionId)
            ->select(
                'asignacion_materias.id as asignacion_materia_id',
                'materias.id as materia_id',
                'materias.clave',
                'materias.nombre as materia',
                'licenciaturas.nombre as licenciatura',
                'cuatrimestres.nombre_cuatrimestre as cuatrimestre',
                'generaciones.generacion as generacion'
            )
            ->distinct()
            ->first();

        if (!$materiaSeleccionada) {
            abort(404, 'No se encontró la materia seleccionada para este profesor.');
        }

        $alumnos = DB::table('inscripciones')
            ->join('alumnos', 'inscripciones.alumno_id', '=', 'alumnos.id')
            ->leftJoin('datos_escolares', 'alumnos.id', '=', 'datos_escolares.alumno_id')
            ->where('inscripciones.licenciatura_id', $licenciaturaId)
            ->where('inscripciones.status', 1)
            ->where('inscripciones.cuatrimestre_id', $cuatrimestreId)
            ->where('inscripciones.generacion_id', $generacionId)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subquery) use ($search) {
                    $subquery->where('datos_escolares.matricula', 'like', "%{$search}%")
                        ->orWhere('alumnos.nombre', 'like', "%{$search}%")
                        ->orWhere('alumnos.apellido_paterno', 'like', "%{$search}%")
                        ->orWhere('alumnos.apellido_materno', 'like', "%{$search}%")
                        ->orWhereRaw(
                            "CONCAT_WS(' ', alumnos.nombre, alumnos.apellido_paterno, alumnos.apellido_materno) LIKE ?",
                            ["%{$search}%"]
                        );
                });
            })
            ->select(
                'alumnos.id',
                'alumnos.nombre',
                'alumnos.apellido_paterno',
                'alumnos.apellido_materno',
                'datos_escolares.matricula'
            )
            ->distinct()
            ->orderBy('alumnos.apellido_paterno')
            ->orderBy('alumnos.apellido_materno')
            ->orderBy('alumnos.nombre')
            ->get();

        $pdf = Pdf::loadView('profesor.pdf.listaAsistenciaPDF', [
            'profesor' => $profesor,
            'alumnos' => $alumnos,
            'licenciatura' => $materiaSeleccionada->licenciatura ?? 'Sin licenciatura',
            'cuatrimestre' => $materiaSeleccionada->cuatrimestre ?? 'Sin cuatrimestre',
            'generacion' => $materiaSeleccionada->generacion ?? 'Sin generación',
            'materia' => $materiaSeleccionada->materia ?? 'Sin materia',
            'clave' => $materiaSeleccionada->clave ?? 'Sin clave',
        ])->setPaper('letter', 'portrait');

        return $pdf->stream('lista-asistencia-' . str_replace(' ', '-', strtolower($materiaSeleccionada->materia ?? 'materia')) . '.pdf');
    }

}
