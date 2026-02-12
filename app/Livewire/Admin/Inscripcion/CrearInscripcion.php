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
use App\Services\CurpService;
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
    public $foto = null;

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

    /** === ESTADO CONSULTA CURP === */
    public bool $curpConsultando = false;
    public ?string $curpError = null;

    /** ✅ BANDERA: modo pruebas CURP */
    public bool $curpModoPruebas = false;

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

        // ✅ Yo obtengo usuarios con rol Estudiante, activos y que todavía no tienen alumno asociado.
        $this->usuarios = User::role('Estudiante')
            ->where('status', 'true')
            ->whereDoesntHave('alumno')
            ->orderBy('id', 'desc')
            ->get();

        $this->licenciaturas = Licenciatura::orderBy('id')->get();
        $this->generaciones = Generacion::orderBy('id')->get();
        $this->cuatrimestres = Cuatrimestre::orderBy('id')->get();

        $this->fecha_inscripcion = now()->toDateString();

        // detecto si el service está en modo pruebas
        $service = app(CurpService::class);
        $this->curpModoPruebas = $service->esModoPruebas();
    }

    public function updatedCurp(?string $valor): void
    {
        $this->curp = $valor ? mb_strtoupper(trim($valor)) : null;
        $this->curpError = null;

        $this->regenerarMatricula();

        if (!$this->curp || mb_strlen($this->curp) !== 18) {
            return;
        }

        $this->consultarCurp();
    }

    public function updatedLicenciaturaId(?int $valor): void
    {
        $this->licenciatura_id = $valor;
        $this->regenerarMatricula();
    }

    protected function regenerarMatricula(): void
    {
        if (!$this->licenciatura_id || !$this->curp) {
            return;
        }

        $curp4 = mb_substr($this->curp, 0, 4);

        $lic = Licenciatura::find($this->licenciatura_id);
        $nombreLic = $lic?->nombre ?? '';

        $tresLic = mb_strtoupper(mb_substr(preg_replace('/\s+/', '', $nombreLic), 0, 3));

        $dosNumeros = str_pad((string) random_int(0, 99), 2, '0', STR_PAD_LEFT);

        $matricula = 'LIC' . $tresLic . $curp4 . $dosNumeros;

        $intentos = 0;
        while (DatosEscolares::where('matricula', $matricula)->exists() && $intentos < 15) {
            $dosNumeros = str_pad((string) random_int(0, 99), 2, '0', STR_PAD_LEFT);
            $matricula = 'LIC' . $tresLic . $curp4 . $dosNumeros;
            $intentos++;
        }

        $this->matricula = $matricula;
    }

    /** ============================
     *  CONSULTA CURP (API)
     *  ============================ */
    protected function consultarCurp(): void
    {
        $this->curpConsultando = true;
        $this->curpError = null;

        /** @var CurpService $service */
        $service = app(CurpService::class);

        // ✅ Yo vuelvo a setear por si cambió a pruebas
        $this->curpModoPruebas = $service->esModoPruebas();

        $data = $service->obtenerDatosPorCurp($this->curp);

        $this->curpConsultando = false;

        if (isset($data['error']) && $data['error'] === true) {
            $this->curpError = $data['message'] ?? 'No se pudo consultar el CURP.';
            return;
        }

        // ✅ Tu estructura real: response.Solicitante
        $sol = data_get($data, 'response.Solicitante')
            ?? data_get($data, 'response.Soliciante')
            ?? null;

        if (!$sol || !is_array($sol)) {
            $this->curpError = 'La API respondió, pero no trajo el bloque Solicitante.';
            return;
        }

        $nombre = $sol['Nombres'] ?? null;
        $apPat = $sol['ApellidoPaterno'] ?? null;
        $apMat = $sol['ApellidoMaterno'] ?? null;
        $fechaNac = $sol['FechaNacimiento'] ?? null;

        $claveSexo = $sol['ClaveSexo'] ?? null; // H o M

        if ($nombre)
            $this->nombre = $this->ponerMayusculas($nombre);
        if ($apPat)
            $this->apellido_paterno = $this->ponerMayusculas($apPat);
        if ($apMat)
            $this->apellido_materno = $this->ponerMayusculas($apMat);

        if ($fechaNac) {
            $this->fecha_nacimiento = trim((string) $fechaNac);
        }

        // ✅ Conversión a tu sistema M/F (validación)
        if ($claveSexo) {
            $claveSexo = mb_strtoupper(trim((string) $claveSexo));

            // H = Hombre => tu sistema usa M
            // M = Mujer  => tu sistema usa F
            if ($claveSexo === 'H') {
                $this->sexo = 'M';
            } elseif ($claveSexo === 'M') {
                $this->sexo = 'F';
            }
        }

        $this->regenerarMatricula();
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
            'user_id' => 'required|exists:users,id',
            'curp' => 'required|string|size:18|unique:alumnos,curp',
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:M,F',

            'matricula' => 'required|string|max:255|unique:datos_escolares,matricula',
            'folio' => 'nullable|string|max:255|unique:datos_escolares,folio',

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

            'licenciatura_id' => 'required|exists:licenciaturas,id',
            'generacion_id' => 'required|exists:generaciones,id',
            'cuatrimestre_id' => 'required|exists:cuatrimestres,id',
            'fecha_inscripcion' => 'required|date',
            'status' => 'boolean',

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

    public function guardarInscripcion(): void
    {
        try {
            $this->regenerarMatricula();
            $this->validate();

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
                $alumno = Alumno::create([
                    'user_id' => $this->user_id,
                    'curp' => $this->ponerMayusculas($this->curp),
                    'nombre' => $this->ponerMayusculas($this->nombre),
                    'apellido_paterno' => $this->apellido_paterno ? $this->ponerMayusculas($this->apellido_paterno) : null,
                    'apellido_materno' => $this->apellido_materno ? $this->ponerMayusculas($this->apellido_materno) : null,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'sexo' => $this->sexo,
                ]);

                $pathFoto = null;
                if ($this->foto) {
                    $pathFoto = $this->foto->store('alumnos/fotos', 'public');
                }

                DatosEscolares::create([
                    'alumno_id' => $alumno->id,
                    'matricula' => $this->limpiarTexto($this->matricula),
                    'folio' => $this->folio ? $this->limpiarTexto($this->folio) : null,
                    'foto' => $pathFoto,
                ]);

                DatosContacto::create([
                    'alumno_id' => $alumno->id,
                    'calle' => $this->limpiarTexto($this->calle),
                    'numero_exterior' => $this->numero_exterior ? $this->limpiarTexto($this->numero_exterior) : null,
                    'numero_interior' => $this->numero_interior ? $this->limpiarTexto($this->numero_interior) : null,
                    'colonia' => $this->limpiarTexto($this->colonia),
                    'municipio' => $this->limpiarTexto($this->municipio),
                    'codigo_postal' => $this->limpiarTexto($this->codigo_postal),
                    'celular' => $this->limpiarTexto($this->celular),
                    'telefono' => $this->telefono ? $this->limpiarTexto($this->telefono) : null,
                    'bachillerato_procedente' => $this->limpiarTexto($this->bachillerato_procedente),
                    'pais_id' => $this->pais_id,
                    'estado_id' => $this->estado_id,
                    'ciudad_id' => $this->ciudad_id,
                ]);

                Inscripcion::create([
                    'alumno_id' => $alumno->id,
                    'licenciatura_id' => $this->licenciatura_id,
                    'generacion_id' => $this->generacion_id,
                    'cuatrimestre_id' => $this->cuatrimestre_id,
                    'status' => $this->status,
                    'fecha_inscripcion' => $this->fecha_inscripcion,
                ]);
            });

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
                'curpConsultando',
                'curpError',
                'curpModoPruebas',
            ]);

            $this->fecha_inscripcion = now()->toDateString();
            $this->states = [];
            $this->cities = [];

            $this->dispatch('swal', [
                'title' => '¡Inscripción creada correctamente!',
                'icon' => 'success',
                'position' => 'top-end',
            ]);
        } catch (ValidationException $e) {
            $errorKeys = array_keys($e->validator->errors()->toArray());
            $step = $this->obtenerPrimerStepConError($errorKeys);

            $this->dispatch('ir-a-step', step: $step);
            $this->dispatch('errores-por-step', summary: $this->obtenerResumenErroresPorStep($e));

            throw $e;
        }
    }

    protected function obtenerPrimerStepConError(array $errorKeys): string
    {
        foreach ($this->stepMap as $step => $fields) {
            if (empty($fields))
                continue;

            if (count(array_intersect($fields, $errorKeys)) > 0) {
                return $step;
            }
        }

        return 'generales';
    }

    protected function obtenerResumenErroresPorStep(?ValidationException $e = null): array
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

    protected function limpiarTexto(?string $valor): string
    {
        return trim((string) $valor);
    }

    protected function ponerMayusculas(?string $valor): string
    {
        return mb_strtoupper($this->limpiarTexto($valor));
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
