<?php

namespace App\Livewire\Admin\Inscripcion;

use App\Models\City;
use App\Models\Country;
use App\Models\Inscripcion;
use App\Models\State;
use Livewire\Component;

class CrearInscripcion extends Component
{
    public array $countries = [];
    public array $states    = [];
    public array $cities    = [];

    /** Selecciones del usuario (GUARDAN IDs) */
    public ?int $pais_nacimiento   = null; // country_id
    public ?int $estado_nacimiento = null; // state_id
    public ?int $lugar_nacimiento  = null; // city_id

    // otros campos
    public ?string $nombre = null;

    public function mount(): void
    {
        // Carga solo id y name
        $this->countries = Country::orderBy('name')
            ->get(['id','name'])
            ->toArray();
    }

    /** Al cambiar país → cargar estados del país y limpiar dependientes */
    public function updatedPaisNacimiento(?int $countryId): void
    {
        $this->estado_nacimiento = null;
        $this->lugar_nacimiento  = null;
        $this->cities            = [];
        $this->states            = [];

        if (!$countryId) return;

        $this->states = State::where('country_id', $countryId)
            ->orderBy('name')
            ->get(['id','name'])
            ->toArray();


    }

    /** Al cambiar estado → cargar ciudades del estado y limpiar ciudad */
    public function updatedEstadoNacimiento(?int $stateId): void
    {
        $this->lugar_nacimiento = null;
        $this->cities           = [];

        if (!$stateId) return;

        $this->cities = City::where('state_id', $stateId)
            ->orderBy('name')
            ->get(['id','name'])
            ->toArray();
    }

    protected function rules(): array
    {
        return [
            'nombre'             => 'required|string|max:255',
            'pais_nacimiento'    => 'required|exists:countries,id',
            'estado_nacimiento'  => 'required|exists:states,id',
            'lugar_nacimiento'   => 'required|exists:cities,id',
        ];
    }

    public function guardar(): void
    {
        $this->validate();

        Inscripcion::create([
            'nombre'            => $this->nombre,
            'pais_nacimiento'   => $this->pais_nacimiento,   // country_id
            'estado_nacimiento' => $this->estado_nacimiento, // state_id
            'lugar_nacimiento'  => $this->lugar_nacimiento,  // city_id
        ]);

        $this->reset([
            'nombre',
            'pais_nacimiento','estado_nacimiento','lugar_nacimiento',
            'states','cities',
        ]);

        $this->dispatch('inscripcion-creada');
        $this->addError('success', 'Inscripción creada correctamente.');
    }
    public function render()
    {
        // Pasamos catálogos y selecciones a la vista
        return view('livewire.admin.inscripcion.crear-inscripcion', [
            'countries' => $this->countries,
            'states'    => $this->states,
            'cities'    => $this->cities,
        ]);
    }
}
