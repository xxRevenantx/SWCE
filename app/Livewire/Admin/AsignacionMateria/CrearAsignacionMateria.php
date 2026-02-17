<?php

namespace App\Livewire\Admin\AsignacionMateria;

use App\Models\AsignacionMateria;
use App\Models\Cuatrimestre;
use App\Models\Licenciatura;
use App\Models\Materia;
use App\Models\Profesor;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CrearAsignacionMateria extends Component
{
    use WithPagination;

    public string $search = '';

    /** Filtros */
    public ?int $filtrar_licenciatura = null;
    public ?int $filtrar_cuatrimestre = null;

    /** Paginación */
    public int $por_pagina = 25;

    /** Catálogos */
    public $licenciaturas = [];
    public $cuatrimestres = [];
    public $profesores = [];

    public array $colorMap = [];
    public array $profesoresValidos = [];
    public array $profesorSeleccionado = [];

    public function mount(): void
    {
        $this->licenciaturas = Licenciatura::query()
            ->orderBy('id')
            ->get(['id', 'nombre']);

        $this->cuatrimestres = Cuatrimestre::query()
            ->orderBy('no_cuatrimestre')
            ->get(['id', 'no_cuatrimestre', 'nombre_cuatrimestre']);

        $this->profesores = Profesor::query()
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'apellido_paterno', 'apellido_materno', 'color']);

        $this->profesoresValidos = $this->profesores
            ->pluck('id')
            ->map(fn($x) => (int) $x)
            ->toArray();

        $this->colorMap = $this->profesores
            ->mapWithKeys(fn($p) => [(int) $p->id => ($p->color ?: null)])
            ->toArray();

        // Precargar asignaciones existentes
        $asigs = AsignacionMateria::query()
            ->select(['licenciatura_id', 'cuatrimestre_id', 'materia_id', 'profesor_id'])
            ->get();

        foreach ($asigs as $a) {
            $key = $this->makeKey((int) $a->licenciatura_id, (int) $a->cuatrimestre_id, (int) $a->materia_id);
            $this->profesorSeleccionado[$key] = $a->profesor_id; // puede ser null
        }
    }

    public function limpiarFiltros(): void
    {
        $this->filtrar_licenciatura = null;
        $this->filtrar_cuatrimestre = null;
        $this->search = '';
        $this->resetPage();
    }

    /** Al cambiar búsqueda/filtros, regresar a página 1 */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFiltrarLicenciatura(): void
    {
        $this->resetPage();
    }

    public function updatedFiltrarCuatrimestre(): void
    {
        $this->resetPage();
    }

    public function updatedPorPagina(): void
    {
        $this->resetPage();
    }

    private function makeKey(int $licId, int $cuatId, int $matId): string
    {
        return "{$licId}_{$cuatId}_{$matId}";
    }

    private function parseKey(string $key): ?array
    {
        $parts = explode('_', $key);
        if (count($parts) !== 3) {
            return null;
        }

        [$licId, $cuatId, $matId] = array_map('intval', $parts);
        if ($licId <= 0 || $cuatId <= 0 || $matId <= 0) {
            return null;
        }

        return [$licId, $cuatId, $matId];
    }

    public function guardarProfesor(string $key, $value): void
    {
        $parsed = $this->parseKey($key);
        if (!$parsed) {
            return;
        }

        [$licId, $cuatId, $matId] = $parsed;

        $profesorId = ($value === '' || $value === null) ? null : (int) $value;

        // Validación sin query extra
        if ($profesorId !== null && !in_array($profesorId, $this->profesoresValidos, true)) {
            $this->dispatch('toast', type: 'error', message: 'Profesor inválido.');
            $this->profesorSeleccionado[$key] = $this->profesorSeleccionado[$key] ?? null;
            return;
        }

        DB::transaction(function () use ($licId, $cuatId, $matId, $profesorId) {
            AsignacionMateria::updateOrCreate(
                [
                    'licenciatura_id' => $licId,
                    'cuatrimestre_id' => $cuatId,
                    'materia_id' => $matId,
                ],
                [
                    'profesor_id' => $profesorId,
                ]
            );
        });

        $this->profesorSeleccionado[$key] = $profesorId;

        $this->dispatch('toast', type: 'success', message: 'Asignación guardada.');
    }

    /**
     * Regresa:
     * - $matriz (array agrupado con SOLO la página actual)
     * - $paginacion (LengthAwarePaginator)
     */
    private function obtenerMatrizPaginada(): array
    {
        $q = Materia::query()
            ->with([
                'licenciatura:id,nombre',
                'cuatrimestre:id,no_cuatrimestre,nombre_cuatrimestre',
            ])
            ->select([
                'materias.id',
                'materias.nombre',
                'materias.clave',
                'materias.slug',
                'materias.licenciatura_id',
                'materias.cuatrimestre_id',
            ])
            ->join('cuatrimestres', 'cuatrimestres.id', '=', 'materias.cuatrimestre_id')
            ->orderBy('materias.licenciatura_id')
            ->orderBy('cuatrimestres.no_cuatrimestre')
            ->orderBy('materias.nombre');

        if (!empty($this->filtrar_licenciatura)) {
            $q->where('materias.licenciatura_id', $this->filtrar_licenciatura);
        }

        if (!empty($this->filtrar_cuatrimestre)) {
            $q->where('materias.cuatrimestre_id', $this->filtrar_cuatrimestre);
        }

        if ($this->search !== '') {
            $s = '%' . $this->search . '%';
            $q->where(function ($qq) use ($s) {
                $qq->where('materias.nombre', 'like', $s)
                    ->orWhere('materias.clave', 'like', $s)
                    ->orWhere('materias.slug', 'like', $s);
            });
        }

        $paginacion = $q->paginate($this->por_pagina);

        // Agrupar SOLO lo de la página actual
        $out = [];

        foreach ($paginacion->getCollection() as $m) {
            $licId = (int) $m->licenciatura_id;
            $cuatId = (int) $m->cuatrimestre_id;

            if (!isset($out[$licId])) {
                $out[$licId] = [
                    'lic' => $m->licenciatura,
                    'cuatrimestres' => [],
                ];
            }

            if (!isset($out[$licId]['cuatrimestres'][$cuatId])) {
                $out[$licId]['cuatrimestres'][$cuatId] = [
                    'cuat' => $m->cuatrimestre,
                    'materias' => [],
                ];
            }

            $out[$licId]['cuatrimestres'][$cuatId]['materias'][] = $m;

            // Asegurar estado del select
            $key = $this->makeKey($licId, $cuatId, (int) $m->id);
            if (!array_key_exists($key, $this->profesorSeleccionado)) {
                $this->profesorSeleccionado[$key] = null;
            }
        }

        return [$out, $paginacion];
    }

    public function render()
    {
        [$matriz, $paginacion] = $this->obtenerMatrizPaginada();

        return view('livewire.admin.asignacion-materia.crear-asignacion-materia', [
            'matriz' => $matriz,
            'paginacion' => $paginacion,
        ]);
    }
}
