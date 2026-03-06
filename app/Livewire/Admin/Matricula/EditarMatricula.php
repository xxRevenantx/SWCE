<?php

namespace App\Livewire\Admin\Matricula;

use Livewire\Component;
use Livewire\WithFileUploads;

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

class EditarMatricula extends Component
{
    use WithFileUploads;

    /** IDs */
    public int $inscripcion_id;

    /** Modelos cargados */
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

    /** Datos del alumno */
    public ?int $user_id = null;
    public ?string $curp = null;
    public ?string $nombre = null;
    public ?string $apellido_paterno = null;
    public ?string $apellido_materno = null;
    public ?string $fecha_nacimiento = null;
    public ?string $sexo = null;

    /** Datos escolares */
    public ?string $matricula = null;
    public ?string $folio = null;
    public $foto = null;
    public ?string $foto_actual = null;

    /** Datos de contacto */
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

    /** Datos de inscripción */
    public ?int $licenciatura_id = null;
    public ?int $generacion_id = null;
    public ?int $cuatrimestre_id = null;
    public ?string $fecha_inscripcion = null;
    public bool $status = true;

    /** Campos por paso */
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

    protected function generarMatriculaSiHaceFalta(bool $forzar = false): void
    {
        // Si ya existe y no se quiere forzar, no cambia
        if (!$forzar && !empty($this->matricula)) {
            return;
        }

        // Si faltan datos, no se genera
        if (empty($this->licenciatura_id) || empty($this->generacion_id)) {
            return;
        }

        $this->matricula = $this->construirMatriculaUnica();
    }

    /**
     * Genera una matrícula única.
     */
    protected function construirMatriculaUnica(): string
    {
        $siglas = $this->obtenerSiglasLicenciatura();
        $consecutivo = $this->obtenerConsecutivoInicial();

        while (true) {
            $consecutivoTexto = str_pad((string) $consecutivo, 2, '0', STR_PAD_LEFT);
            $matricula = "LIC{$siglas}CANP{$consecutivoTexto}";

            $existe = DatosEscolares::query()
                ->when($this->datosEscolares?->id, fn($consulta) => $consulta->where('id', '!=', $this->datosEscolares->id))
                ->where('matricula', $matricula)
                ->exists();

            if (!$existe) {
                return $matricula;
            }

            $consecutivo++;
        }
    }

    /**
     * Obtiene las siglas de la licenciatura.
     */
    protected function obtenerSiglasLicenciatura(): string
    {
        $licenciatura = $this->licenciaturas?->firstWhere('id', $this->licenciatura_id);

        $texto = $licenciatura?->nombre_corto
            ?? $licenciatura?->slug
            ?? $licenciatura?->nombre
            ?? ('LIC' . $this->licenciatura_id);

        $texto = strtoupper((string) $texto);
        $texto = preg_replace('/[^A-Z0-9]+/i', '', $texto) ?: 'XXX';

        $siglas = substr($texto, 0, 3);

        return str_pad($siglas, 3, 'X', STR_PAD_RIGHT);
    }

    /**
     * Obtiene el consecutivo inicial.
     */
    protected function obtenerConsecutivoInicial(): int
    {
        $total = DatosEscolares::query()
            ->when($this->datosEscolares?->id, fn($consulta) => $consulta->where('id', '!=', $this->datosEscolares->id))
            ->whereHas('alumno.inscripciones', function ($consulta) {
                $consulta->where('licenciatura_id', $this->licenciatura_id)
                    ->where('generacion_id', $this->generacion_id);
            })
            ->count();

        return $total + 1;
    }

    public function mount(int $id): void
    {
        $this->inscripcion_id = $id;

        // Catálogos
        $this->countries = Country::orderBy('name')->get(['id', 'name'])->toArray();
        $this->licenciaturas = Licenciatura::orderBy('id')->get();
        $this->generaciones = Generacion::orderBy('id')->get();
        $this->cuatrimestres = Cuatrimestre::orderBy('id')->get();

        // Inscripción y relaciones
        $this->inscripcion = Inscripcion::with([
            'alumno',
            'alumno.datosEscolares',
            'alumno.datosContacto',
        ])->findOrFail($this->inscripcion_id);

        $this->alumno = $this->inscripcion->alumno;
        $this->datosEscolares = $this->alumno?->datosEscolares;
        $this->datosContacto = $this->alumno?->datosContacto;

        // Usuarios activos con rol estudiante
        $this->usuarios = User::role('Estudiante')
            ->where('status', 'true')
            ->where(function ($consulta) {
                $consulta->whereDoesntHave('alumno')
                    ->orWhere('id', $this->alumno?->user_id);
            })
            ->orderBy('id', 'desc')
            ->get();

        // Alumno
        $this->user_id = $this->alumno?->user_id;
        $this->curp = $this->alumno?->curp;
        $this->nombre = $this->alumno?->nombre;
        $this->apellido_paterno = $this->alumno?->apellido_paterno;
        $this->apellido_materno = $this->alumno?->apellido_materno;
        $this->fecha_nacimiento = $this->alumno?->fecha_nacimiento;
        $this->sexo = $this->alumno?->sexo;

        // Datos escolares
        $this->matricula = $this->datosEscolares?->matricula;
        $this->folio = $this->datosEscolares?->folio;
        $this->foto_actual = $this->datosEscolares?->foto;

        // Datos de contacto
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

        // Inscripción
        $this->licenciatura_id = $this->inscripcion->licenciatura_id;
        $this->generacion_id = $this->inscripcion->generacion_id;
        $this->cuatrimestre_id = $this->inscripcion->cuatrimestre_id;
        $this->fecha_inscripcion = $this->inscripcion->fecha_inscripcion;
        $this->status = (bool) $this->inscripcion->status;

        // Cargar estados y ciudades si ya existen
        if ($this->pais_id) {
            $this->states = State::where('country_id', $this->pais_id)->orderBy('name')->get(['id', 'name'])->toArray();
        }

        if ($this->estado_id) {
            $this->cities = City::where('state_id', $this->estado_id)->orderBy('name')->get(['id', 'name'])->toArray();
        }

        // Generar matrícula si está vacía
        $this->generarMatriculaSiHaceFalta(false);
    }

    /**
     * Actualiza estados al cambiar país.
     */
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

    /**
     * Actualiza ciudades al cambiar estado.
     */
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

    /**
     * Genera matrícula si cambia la licenciatura.
     */
    public function updatedLicenciaturaId(?int $value): void
    {
        $this->generarMatriculaSiHaceFalta(false);
    }

    /**
     * Genera matrícula si cambia la generación.
     */
    public function updatedGeneracionId(?int $value): void
    {
        $this->generarMatriculaSiHaceFalta(false);
    }

    protected function rules(): array
    {
        $alumnoId = $this->alumno?->id;
        $escolaresId = $this->datosEscolares?->id;

        return [
            // alumno
            'user_id' => 'required|exists:users,id',
            'curp' => 'required|string|size:18|unique:alumnos,curp,' . $alumnoId,
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:M,F',

            // datos escolares
            'matricula' => 'nullable|string|max:255|unique:datos_escolares,matricula,' . $escolaresId,
            'folio' => 'nullable|string|max:255|unique:datos_escolares,folio,' . $escolaresId,

            // datos de contacto
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

            // inscripción
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
            'matricula.unique' => 'Esta matrícula ya está registrada.',
            'licenciatura_id.required' => 'Selecciona una licenciatura.',
            'generacion_id.required' => 'Selecciona una generación.',
            'cuatrimestre_id.required' => 'Selecciona un cuatrimestre.',
        ];
    }

    public function actualizarInscripcion(): void
    {
        try {
            // Generar matrícula antes de validar
            $this->generarMatriculaSiHaceFalta(false);

            // Si no se pudo generar, mostrar error
            if (empty($this->matricula)) {
                throw ValidationException::withMessages([
                    'matricula' => 'No se pudo generar la matrícula. Verifica licenciatura y generación.',
                ]);
            }

            $this->validate();

            // Evitar inscripción duplicada
            $existe = Inscripcion::query()
                ->where('id', '!=', $this->inscripcion_id)
                ->where('alumno_id', $this->alumno?->id)
                ->where('licenciatura_id', $this->licenciatura_id)
                ->where('generacion_id', $this->generacion_id)
                ->where('cuatrimestre_id', $this->cuatrimestre_id)
                ->exists();

            if ($existe) {
                throw ValidationException::withMessages([
                    'licenciatura_id' => 'Ya existe otra inscripción para esta licenciatura, generación y cuatrimestre.',
                ]);
            }

            DB::transaction(function () {
                // Alumno
                $this->alumno->update([
                    'user_id' => $this->user_id,
                    'curp' => mb_strtoupper(trim((string) $this->curp)),
                    'nombre' => mb_strtoupper(trim((string) $this->nombre)),
                    'apellido_paterno' => $this->apellido_paterno ? mb_strtoupper(trim($this->apellido_paterno)) : null,
                    'apellido_materno' => $this->apellido_materno ? mb_strtoupper(trim($this->apellido_materno)) : null,
                    'fecha_nacimiento' => $this->fecha_nacimiento,
                    'sexo' => $this->sexo,
                ]);

                // Foto
                $rutaFoto = $this->foto_actual;

                if ($this->foto) {
                    $nuevaRuta = $this->foto->store('alumnos/fotos', 'public');

                    if ($this->foto_actual && Storage::disk('public')->exists($this->foto_actual)) {
                        Storage::disk('public')->delete($this->foto_actual);
                    }

                    $rutaFoto = $nuevaRuta;
                }

                // Datos escolares
                if ($this->datosEscolares) {
                    $this->datosEscolares->update([
                        'matricula' => trim((string) $this->matricula),
                        'folio' => $this->folio ? trim((string) $this->folio) : null,
                        'foto' => $rutaFoto,
                    ]);
                } else {
                    $this->datosEscolares = DatosEscolares::create([
                        'alumno_id' => $this->alumno->id,
                        'matricula' => trim((string) $this->matricula),
                        'folio' => $this->folio ? trim((string) $this->folio) : null,
                        'foto' => $rutaFoto,
                    ]);
                }

                $this->foto_actual = $rutaFoto;

                // Datos de contacto
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

                // Inscripción
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
            // Regresa al paso donde está el error
            $camposConError = array_keys($e->validator->errors()->toArray());
            $step = $this->obtenerPrimerPasoConError($camposConError);

            $this->dispatch('ir-a-step', step: $step);
            $this->dispatch('errores-por-step', summary: $this->obtenerResumenErroresPorPaso($e));

            throw $e;
        }
    }

    /**
     * Busca el primer paso con error.
     */
    protected function obtenerPrimerPasoConError(array $camposConError): string
    {
        foreach ($this->stepMap as $step => $campos) {
            if (empty($campos)) {
                continue;
            }

            if (count(array_intersect($campos, $camposConError)) > 0) {
                return $step;
            }
        }

        return 'generales';
    }

    /**
     * Cuenta los errores por paso.
     */
    protected function obtenerResumenErroresPorPaso(?ValidationException $e = null): array
    {
        $mensajes = $e
            ? $e->validator->errors()->messages()
            : (session('errors')?->getBag('default')?->messages() ?? []);

        $resumen = array_fill_keys(array_keys($this->stepMap), 0);

        foreach ($mensajes as $campo => $listaMensajes) {
            foreach ($this->stepMap as $step => $campos) {
                if (!empty($campos) && in_array($campo, $campos, true)) {
                    $resumen[$step] += count($listaMensajes);
                    break;
                }
            }
        }

        return $resumen;
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
