<?php

namespace App\Livewire\Admin\Inscripcion;

use App\Models\City;
use App\Models\Country;
use App\Models\Inscripcion;
use App\Models\State;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CrearInscripcion extends Component
{
    /** Catálogos */
    public $usuarios;
    public array $countries = [];
    public array $states    = [];
    public array $cities    = [];
    public  $licenciaturas;

    /** Selecciones del usuario (IDs) */
    public ?int $pais_nacimiento   = null; // country_id
    public ?int $estado_nacimiento = null; // state_id
    public ?int $lugar_nacimiento  = null; // city_id
    public ?int    $user_id   = null;
    public ?string $CURP      = null;
    public ?string $matricula = null;
    public ?string $folio     = null;
    public ?string $nombre     = null;
    public ?string $apellido_paterno = null;
    public ?string $apellido_materno = null;
    public ?string $fecha_nacimiento   = null;
    public  $sexo                = null;



    /**
     * Paso del wizard -> campos que valida/contiene.
     * Agrega aquí los campos de otros pasos si también los validas en este componente.
     */
    protected array $stepMap = [
        'generales' => [
            'user_id',
            'CURP',
            'matricula',
            'folio',
            'nombre',
            'apellido_paterno',
            'apellido_materno',
            'fecha_nacimiento',
            'sexo',
        ],
        // Si este componente NO valida contacto/escolares, déjalos vacíos.
        'contacto'  => [],
        'escolares' => [],
    ];

    public function mount(): void
    {
        $this->countries = Country::orderBy('name')
            ->get(['id','name'])
            ->toArray();

         $this->usuarios = User::role('Estudiante')
            // ->whereNotIn('id', Inscripcion::pluck('user_id'))
            ->where('status', "true")
            ->orderBy('id', 'desc')
            ->get();

            $this->licenciaturas = \App\Models\Licenciatura::orderBy('id')->get();

            // dd($this->usuarios);
    }

    public function updatedPaisNacimiento(?int $countryId): void
    {
        $this->estado_nacimiento = null;
        $this->lugar_nacimiento  = null;
        $this->cities = [];
        $this->states = [];

        if (!$countryId) {
            $this->dispatch('catalogos-actualizados'); // recalcula altura
            return;
        }

        $this->states = State::where('country_id', $countryId)
            ->orderBy('name')
            ->get(['id','name'])
            ->toArray();

        $this->dispatch('catalogos-actualizados');
    }

    public function updatedEstadoNacimiento(?int $stateId): void
    {
        $this->lugar_nacimiento = null;
        $this->cities = [];

        if (!$stateId) {
            $this->dispatch('catalogos-actualizados');
            return;
        }

        $this->cities = City::where('state_id', $stateId)
            ->orderBy('name')
            ->get(['id','name'])
            ->toArray();

        $this->dispatch('catalogos-actualizados');
    }

    protected function rules(): array
    {
        return [
            'user_id'            => 'required|exists:users,id',
            'CURP'               => 'required|string|max:18',
            'matricula'          => 'nullable|string|max:10',
            'folio'              => 'nullable|string|max:10',
            'nombre'             => 'required|string|max:255',
            'apellido_paterno'   => 'nullable|string|max:255',
            'apellido_materno'   => 'nullable|string|max:255',
            'fecha_nacimiento'   => 'required|date',
            'pais_nacimiento'    => 'nullable|exists:countries,id',
            'estado_nacimiento'  => 'nullable|exists:states,id',
            'lugar_nacimiento'   => 'nullable|exists:cities,id',
            'sexo'               => 'required|string|max:1',
        ];
    }

    public function guardarInscripcion(): void
    {
        try {
            $this->validate();

            Inscripcion::create([
                'user_id'          => $this->user_id,
                'CURP'             => $this->CURP,
                'matricula'        => $this->matricula,
                'folio'            => $this->folio,
                'nombre'            => $this->nombre,
                'apellido_paterno'  => $this->apellido_paterno,
                'apellido_materno'  => $this->apellido_materno,
                'fecha_nacimiento'  => $this->fecha_nacimiento,
                'pais_nacimiento'   => $this->pais_nacimiento,
                'estado_nacimiento' => $this->estado_nacimiento,
                'lugar_nacimiento'  => $this->lugar_nacimiento,
                'sexo'              => $this->sexo,
            ]);

            $this->reset([
                'user_id','CURP','matricula','folio',
                'nombre', 'apellido_paterno','apellido_materno','fecha_nacimiento',
                'pais_nacimiento','estado_nacimiento','lugar_nacimiento', 'sexo',
                'countries','states','cities',
            ]);

            $this->dispatch('inscripcion-creada');
            $this->addError('success', 'Inscripción creada correctamente.');
        } catch (ValidationException $e) {
            // 1) ¿qué campos fallaron?
            $errorKeys = array_keys($e->validator->errors()->toArray());

            // 2) ¿en qué paso está el primer error?
            $step = $this->firstErroredStep($errorKeys);

            // 3) indica al wizard que navegue a ese paso
            $this->dispatch('ir-a-step', step: $step);

            // 4) (opcional) envía conteo de errores por step para mostrar badges
            $this->dispatch('errores-por-step', summary: $this->errorsSummaryByStep($e));

            // Re-lanza para que Livewire pinte los mensajes bajo los inputs
            throw $e;
        }
    }

    /**
     * Dado un arreglo de campos con error, devuelve el nombre del primer step con errores.
     */
    protected function firstErroredStep(array $errorKeys): string
    {
        foreach ($this->stepMap as $step => $fields) {
            if (empty($fields)) continue;
            if (count(array_intersect($fields, $errorKeys)) > 0) {
                return $step;
            }
        }
        // Si no mapea, cae en 'generales' por defecto
        return 'generales';
    }

    /**
     * Construye un resumen (#errores por step) para pintar indicadores en tabs.
     */
    protected function errorsSummaryByStep(?ValidationException $e = null): array
    {
        $messages = $e
            ? $e->validator->errors()->messages()
            : (session('errors')?->getBag('default')?->messages() ?? []);

        $summary = [];
        foreach (array_keys($this->stepMap) as $step) {
            $summary[$step] = 0;
        }

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
            'states'    => $this->states,
            'cities'    => $this->cities,
        ]);
    }
}
