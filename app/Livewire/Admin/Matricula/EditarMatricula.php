<?php

namespace App\Livewire\Admin\Matricula;

use Livewire\Component;


use App\Models\Alumno;
use App\Models\City;
use App\Models\Country;
use App\Models\DatosContacto;
use App\Models\DatosEscolares;
use App\Models\Inscripcion;
use App\Models\Licenciatura;
use App\Models\State;
use App\Models\User;
use App\Models\Generacion;
use App\Models\Cuatrimestre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\WithFileUploads;


class EditarMatricula extends Component
{



    use WithFileUploads;

    /** IDs */
    public int $inscripcion_id;

    /** Modelos cargados (para ignore y para paths viejos) */
    public ?Inscripcion $inscripcion = null;
    public ?Alumno $alumno = null;
    public ?DatosEscolares $datosEscolares = null;
    public ?DatosContacto $datosContacto = null;

    /** Catálogos */
    public $usuarios;
    public $licenciaturas;
    public $generaciones;
    public $cuatrimestres;

    public array $countries = [];
    public array $states = [];
    public array $cities = [];

    /** === ALUMNO (tabla alumnos) === */
    public ?int $user_id = null;
    public ?string $curp = null;
    public ?string $nombre = null;
    public ?string $apellido_paterno = null;
    public ?string $apellido_materno = null;
    public ?string $fecha_nacimiento = null;
    public ?string $sexo = null; // M/F

    /** === DATOS ESCOLARES (tabla datos_escolares) === */
    public ?string $matricula = null;
    public ?string $folio = null;
    public $foto = null; // archivo Livewire (nuevo)
    public ?string $foto_actual = null; // path actual (para preview)

    /** === DATOS CONTACTO (tabla datos_contactos) === */
    public ?string $calle = null;
    public ?string $numero_exterior = null;
    public ?string $numero_interior = null;
    public ?string $colonia = null;
    public ?string $municipio = null;
    public ?string $codigo_postal = null;
    public ?string $celular = null;
    public ?string $telefono = null;
    public ?string $bachillerato_procedente = null;

    public ?int $pais_id = null;
    public ?int $estado_id = null;
    public ?int $ciudad_id = null;

    /** === INSCRIPCION (tabla inscripciones) === */
    public ?int $licenciatura_id = null;
    public ?int $generacion_id = null;
    public ?int $cuatrimestre_id = null;
    public ?string $fecha_inscripcion = null;
    public bool $status = true;

    protected array $stepMap = [
        'generales' => [
            'user_id',
            'curp',
            'nombre',
            'apellido_paterno',
            'apellido_materno',
            'fecha_nacimiento',
            'sexo',
            'matricula',
            'folio',
        ],
        'contacto' => [
            'calle',
            'colonia',
            'municipio',
            'codigo_postal',
            'celular',
            'telefono',
            'bachillerato_procedente',
            'pais_id',
            'estado_id',
            'ciudad_id',
            'numero_exterior',
            'numero_interior',
        ],
        'escolares' => [
            'licenciatura_id',
            'generacion_id',
            'cuatrimestre_id',
            'fecha_inscripcion',
            'status',
            'foto',
        ],
    ];

    public function mount(int $id): void
    {
        $this->inscripcion_id = $id;

        // 1) Catálogos base
        $this->countries = Country::orderBy('name')->get(['id', 'name'])->toArray();
        $this->licenciaturas = Licenciatura::orderBy('id')->get();
        $this->generaciones = Generacion::orderBy('id')->get();
        $this->cuatrimestres = Cuatrimestre::orderBy('id')->get();

        // 2) Cargar inscripción con todo lo necesario
        $this->inscripcion = Inscripcion::with([
            'alumno',
            'alumno.datosEscolares',
            'alumno.datosContacto',
        ])->findOrFail($this->inscripcion_id);

        $this->alumno = $this->inscripcion->alumno;
        $this->datosEscolares = $this->alumno?->datosEscolares;
        $this->datosContacto = $this->alumno?->datosContacto;

        // 3) Usuarios estudiante activos:
        //    - permito el usuario actual aunque ya tenga alumno
        $this->usuarios = User::role('Estudiante')
            ->where('status', 'true')
            ->where(function ($q) {
                $q->whereDoesntHave('alumno')
                    ->orWhere('id', $this->alumno?->user_id);
            })
            ->orderBy('id', 'desc')
            ->get();

        // 4) Poblar propiedades: ALUMNO
        $this->user_id = $this->alumno?->user_id;
        $this->curp = $this->alumno?->curp;
        $this->nombre = $this->alumno?->nombre;
        $this->apellido_paterno = $this->alumno?->apellido_paterno;
        $this->apellido_materno = $this->alumno?->apellido_materno;
        $this->fecha_nacimiento = $this->alumno?->fecha_nacimiento;
        $this->sexo = $this->alumno?->sexo;

        // 5) Poblar propiedades: DATOS ESCOLARES
        $this->matricula = $this->datosEscolares?->matricula;
        $this->folio = $this->datosEscolares?->folio;
        $this->foto_actual = $this->datosEscolares?->foto;

        // 6) Poblar propiedades: DATOS CONTACTO
        $this->calle = $this->datosContacto?->calle;
        $this->numero_exterior = $this->datosContacto?->numero_exterior;
        $this->numero_interior = $this->datosContacto?->numero_interior;
        $this->colonia = $this->datosContacto?->colonia;
        $this->municipio = $this->datosContacto?->municipio;
        $this->codigo_postal = $this->datosContacto?->codigo_postal;
        $this->celular = $this->datosContacto?->celular;
        $this->telefono = $this->datosContacto?->telefono;
        $this->bachillerato_procedente = $this->datosContacto?->bachillerato_procedente;

        $this->pais_id = $this->datosContacto?->pais_id;
        $this->estado_id = $this->datosContacto?->estado_id;
        $this->ciudad_id = $this->datosContacto?->ciudad_id;

        // 7) Poblar propiedades: INSCRIPCION
        $this->licenciatura_id = $this->inscripcion->licenciatura_id;
        $this->generacion_id = $this->inscripcion->generacion_id;
        $this->cuatrimestre_id = $this->inscripcion->cuatrimestre_id;
        $this->fecha_inscripcion = $this->inscripcion->fecha_inscripcion;
        $this->status = (bool) $this->inscripcion->status;

        // 8) Cargar cascadas si ya hay país/estado
        if ($this->pais_id) {
            $this->states = State::where('country_id', $this->pais_id)->orderBy('name')->get(['id', 'name'])->toArray();
        }
        if ($this->estado_id) {
            $this->cities = City::where('state_id', $this->estado_id)->orderBy('name')->get(['id', 'name'])->toArray();
        }
    }

    /** Cascadas país -> estado -> ciudad (para datos_contactos) */
    public function updatedPaisId(?int $countryId): void
    {
        $this->estado_id = null;
        $this->ciudad_id = null;
        $this->cities = [];
        $this->states = [];

        if (!$countryId) {
            $this->dispatch('catalogos-actualizados');
            return;
        }

        $this->states = State::where('country_id', $countryId)->orderBy('name')->get(['id', 'name'])->toArray();
        $this->dispatch('catalogos-actualizados');
    }

    public function updatedEstadoId(?int $stateId): void
    {
        $this->ciudad_id = null;
        $this->cities = [];

        if (!$stateId) {
            $this->dispatch('catalogos-actualizados');
            return;
        }

        $this->cities = City::where('state_id', $stateId)->orderBy('name')->get(['id', 'name'])->toArray();
        $this->dispatch('catalogos-actualizados');
    }

    protected function rules(): array
    {
        $alumnoId = $this->alumno?->id;
        $escolaresId = $this->datosEscolares?->id;

        return [
            // alumnos
            'user_id' => 'required|exists:users,id',
            'curp' => 'required|string|size:18|unique:alumnos,curp,' . $alumnoId,
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:M,F',

            // datos_escolares
            'matricula' => 'required|string|max:255|unique:datos_escolares,matricula,' . $escolaresId,
            'folio' => 'nullable|string|max:255|unique:datos_escolares,folio,' . $escolaresId,

            // datos_contactos
            'calle' => 'nullable|string|max:255',
            'colonia' => 'nullable|string|max:255',
            'municipio' => 'nullable|string|max:255',
            'codigo_postal' => 'nullable|string|max:10',
            'celular' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'numero_exterior' => 'nullable|string|max:10',
            'numero_interior' => 'nullable|string|max:10',
            'bachillerato_procedente' => 'nullable|string|max:255',

            'pais_id' => 'nullable|exists:countries,id',
            'estado_id' => 'nullable|exists:states,id',
            'ciudad_id' => 'nullable|exists:cities,id',

            // inscripciones
            'licenciatura_id' => 'required|exists:licenciaturas,id',
            'generacion_id' => 'required|exists:generaciones,id',
            'cuatrimestre_id' => 'required|exists:cuatrimestres,id',
            'fecha_inscripcion' => 'required|date',
            'status' => 'boolean',

            // foto
            'foto' => 'nullable|image|max:2048',
        ];
    }

    protected function messages(): array
    {
        return [
            'user_id.required' => 'El campo Estudiante es obligatorio.',
            'curp.required' => 'El campo CURP es obligatorio.',
            'curp.size' => 'El CURP debe tener 18 caracteres.',
            'curp.unique' => 'Este CURP ya está registrado.',
            'matricula.required' => 'La matrícula es obligatoria.',
            'matricula.unique' => 'Esta matrícula ya está registrada.',
            'licenciatura_id.required' => 'Selecciona una licenciatura.',
            'generacion_id.required' => 'Selecciona una generación.',
            'cuatrimestre_id.required' => 'Selecciona un cuatrimestre.',
        ];
    }

    public function actualizarInscripcion(): void
    {
        try {
            $this->validate();

            // Evitar duplicar inscripción para la misma combinación (excluyendo esta inscripción)
            $exists = Inscripcion::query()
                ->where('id', '!=', $this->inscripcion_id)
                ->where('alumno_id', $this->alumno?->id)
                ->where('licenciatura_id', $this->licenciatura_id)
                ->where('generacion_id', $this->generacion_id)
                ->where('cuatrimestre_id', $this->cuatrimestre_id)
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'licenciatura_id' => 'Ya existe otra inscripción para esta licenciatura, generación y cuatrimestre.',
                ]);
            }

            DB::transaction(function () {
                // 1) Alumno
                $this->alumno->update([
                    'user_id' => $this->user_id,
                    'curp' => mb_strtoupper(trim((string) $this->curp)),
                    'nombre' => mb_strtoupper(trim((string) $this->nombre)),
                    'apellido_paterno' => $this->apellido_paterno ? mb_strtoupper(trim($this->apellido_paterno)) : null,
                    'apellido_materno' => $this->apellido_materno ? mb_strtoupper(trim($this->apellido_materno)) : null,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'sexo' => $this->sexo,
                ]);

                // 2) Datos escolares
                $pathFoto = $this->foto_actual;

                // Si subo nueva foto, guardo y (opcional) elimino la anterior
                if ($this->foto) {
                    $nuevoPath = $this->foto->store('alumnos/fotos', 'public');

                    // Si ya existía una foto, la borro (si quieres conservar historial, quita esto)
                    if ($this->foto_actual && Storage::disk('public')->exists($this->foto_actual)) {
                        Storage::disk('public')->delete($this->foto_actual);
                    }

                    $pathFoto = $nuevoPath;
                }

                // Si tu relación es 1-1 y siempre existe, hago update; si no existe, creo
                if ($this->datosEscolares) {
                    $this->datosEscolares->update([
                        'matricula' => trim((string) $this->matricula),
                        'folio' => $this->folio ? trim((string) $this->folio) : null,
                        'foto' => $pathFoto,
                    ]);
                } else {
                    $this->datosEscolares = DatosEscolares::create([
                        'alumno_id' => $this->alumno->id,
                        'matricula' => trim((string) $this->matricula),
                        'folio' => $this->folio ? trim((string) $this->folio) : null,
                        'foto' => $pathFoto,
                    ]);
                }

                // Refresco foto_actual para la UI
                $this->foto_actual = $pathFoto;

                // 3) Datos contacto
                if ($this->datosContacto) {
                    $this->datosContacto->update([
                        'calle' => trim((string) $this->calle),
                        'numero_exterior' => $this->numero_exterior ? trim((string) $this->numero_exterior) : null,
                        'numero_interior' => $this->numero_interior ? trim((string) $this->numero_interior) : null,
                        'colonia' => trim((string) $this->colonia),
                        'municipio' => trim((string) $this->municipio),
                        'codigo_postal' => trim((string) $this->codigo_postal),
                        'celular' => trim((string) $this->celular),
                        'telefono' => $this->telefono ? trim((string) $this->telefono) : null,
                        'bachillerato_procedente' => trim((string) $this->bachillerato_procedente),
                        'pais_id' => $this->pais_id,
                        'estado_id' => $this->estado_id,
                        'ciudad_id' => $this->ciudad_id,
                    ]);
                } else {
                    $this->datosContacto = DatosContacto::create([
                        'alumno_id' => $this->alumno->id,
                        'calle' => trim((string) $this->calle),
                        'numero_exterior' => $this->numero_exterior ? trim((string) $this->numero_exterior) : null,
                        'numero_interior' => $this->numero_interior ? trim((string) $this->numero_interior) : null,
                        'colonia' => trim((string) $this->colonia),
                        'municipio' => trim((string) $this->municipio),
                        'codigo_postal' => trim((string) $this->codigo_postal),
                        'celular' => trim((string) $this->celular),
                        'telefono' => $this->telefono ? trim((string) $this->telefono) : null,
                        'bachillerato_procedente' => trim((string) $this->bachillerato_procedente),
                        'pais_id' => $this->pais_id,
                        'estado_id' => $this->estado_id,
                        'ciudad_id' => $this->ciudad_id,
                    ]);
                }

                // 4) Inscripción
                $this->inscripcion->update([
                    'licenciatura_id' => $this->licenciatura_id,
                    'generacion_id' => $this->generacion_id,
                    'cuatrimestre_id' => $this->cuatrimestre_id,
                    'status' => $this->status,
                    'fecha_inscripcion' => $this->fecha_inscripcion,
                ]);
            });

            $this->dispatch('inscripcion-actualizada');
        } catch (ValidationException $e) {
            $errorKeys = array_keys($e->validator->errors()->toArray());
            $step = $this->firstErroredStep($errorKeys);

            $this->dispatch('ir-a-step', step: $step);
            $this->dispatch('errores-por-step', summary: $this->errorsSummaryByStep($e));

            throw $e;
        }
    }

    protected function firstErroredStep(array $errorKeys): string
    {
        foreach ($this->stepMap as $step => $fields) {
            if (empty($fields))
                continue;
            if (count(array_intersect($fields, $errorKeys)) > 0)
                return $step;
        }
        return 'generales';
    }

    protected function errorsSummaryByStep(?ValidationException $e = null): array
    {
        $messages = $e
            ? $e->validator->errors()->messages()
            : (session('errors')?->getBag('default')?->messages() ?? []);

        $summary = array_fill_keys(array_keys($this->stepMap), 0);

        foreach ($messages as $field => $msgs) {
            foreach ($this->stepMap as $step => $fields) {
                if (!empty($fields) && in_array($field, $fields, true)) {
                    $summary[$step] += count($msgs);
                    break;
                }
            }
        }

        return $summary;
    }

    public function render()
    {
        return view('livewire.admin.matricula.editar-matricula', [
            'countries' => $this->countries,
            'states' => $this->states,
            'cities' => $this->cities,
            'usuarios' => $this->usuarios,
            'licenciaturas' => $this->licenciaturas,
            'generaciones' => $this->generaciones,
            'cuatrimestres' => $this->cuatrimestres,
        ]);
    }
}


