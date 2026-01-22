<?php

namespace App\Livewire\Admin\Inscripcion;

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
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class CrearInscripcion extends Component
{
    use WithFileUploads;

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
    public $foto = null; // archivo Livewire

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

    public function mount(): void
    {
        $this->countries = Country::orderBy('name')->get(['id', 'name'])->toArray();

        // Usuarios estudiante activos y sin alumno asociado
        $this->usuarios = User::role('Estudiante')
            ->where('status', 'true')
            ->whereDoesntHave('alumno')
            ->orderBy('id', 'desc')
            ->get();

        $this->licenciaturas = Licenciatura::orderBy('id')->get();
        $this->generaciones = Generacion::orderBy('id')->get();
        $this->cuatrimestres = Cuatrimestre::orderBy('id')->get();

        $this->fecha_inscripcion = now()->toDateString();
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
        return [
            // alumnos
            'user_id' => 'required|exists:users,id',
            'curp' => 'required|string|size:18|unique:alumnos,curp',
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'nullable|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:M,F',

            // datos_escolares
            'matricula' => 'required|string|max:255|unique:datos_escolares,matricula',
            'folio' => 'nullable|string|max:255|unique:datos_escolares,folio',

            // datos_contactos (en tu migración son obligatorios)
            'calle' => 'required|string|max:255',
            'colonia' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:10',
            'celular' => 'required|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'numero_exterior' => 'nullable|string|max:10',
            'numero_interior' => 'nullable|string|max:10',
            'bachillerato_procedente' => 'required|string|max:255',

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
            'foto' => 'nullable|image|max:2048', // 2MB
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

    public function guardarInscripcion(): void
    {
        try {
            $this->validate();

            // Evitar duplicar inscripción para la misma combinación
            // (si deseas permitir historial, aquí cambia la regla)
            $exists = Inscripcion::whereHas('alumno', function ($q) {
                $q->where('user_id', $this->user_id);
            })
                ->where('licenciatura_id', $this->licenciatura_id)
                ->where('generacion_id', $this->generacion_id)
                ->where('cuatrimestre_id', $this->cuatrimestre_id)
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'licenciatura_id' => 'Ya existe una inscripción para esta licenciatura, generación y cuatrimestre.',
                ]);
            }

            DB::transaction(function () {
                // 1) Alumno
                $alumno = Alumno::create([
                    'user_id' => $this->user_id,
                    'curp' => mb_strtoupper(trim((string) $this->curp)),
                    'nombre' => mb_strtoupper(trim((string) $this->nombre)),
                    'apellido_paterno' => $this->apellido_paterno ? mb_strtoupper(trim($this->apellido_paterno)) : null,
                    'apellido_materno' => $this->apellido_materno ? mb_strtoupper(trim($this->apellido_materno)) : null,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'sexo' => $this->sexo,
                ]);

                // 2) Datos escolares
                $pathFoto = null;
                if ($this->foto) {
                    $pathFoto = $this->foto->store('alumnos/fotos', 'public');
                }

                DatosEscolares::create([
                    'alumno_id' => $alumno->id,
                    'matricula' => trim((string) $this->matricula),
                    'folio' => $this->folio ? trim((string) $this->folio) : null,
                    'foto' => $pathFoto,
                ]);

                // 3) Datos contacto
                DatosContacto::create([
                    'alumno_id' => $alumno->id,
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

                // 4) Inscripción
                Inscripcion::create([
                    'alumno_id' => $alumno->id,
                    'licenciatura_id' => $this->licenciatura_id,
                    'generacion_id' => $this->generacion_id,
                    'cuatrimestre_id' => $this->cuatrimestre_id,
                    'status' => $this->status,
                    'fecha_inscripcion' => $this->fecha_inscripcion,
                ]);
            });

            // Reset (no resetees catálogos)
            $this->reset([
                'user_id',
                'curp',
                'nombre',
                'apellido_paterno',
                'apellido_materno',
                'fecha_nacimiento',
                'sexo',
                'matricula',
                'folio',
                'foto',
                'calle',
                'numero_exterior',
                'numero_interior',
                'colonia',
                'municipio',
                'codigo_postal',
                'celular',
                'telefono',
                'bachillerato_procedente',
                'pais_id',
                'estado_id',
                'ciudad_id',
                'licenciatura_id',
                'generacion_id',
                'cuatrimestre_id',
                'status',
                'fecha_inscripcion',
            ]);

            $this->fecha_inscripcion = now()->toDateString();
            $this->states = [];
            $this->cities = [];

            $this->dispatch('inscripcion-creada');

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
        return view('livewire.admin.inscripcion.crear-inscripcion', [
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
