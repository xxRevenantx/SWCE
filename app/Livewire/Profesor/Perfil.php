<?php

namespace App\Livewire\Profesor;

use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Perfil extends Component
{
    public ?int $user_id = null;
    public ?int $profesor_id = null;

    // Datos del profesor
    public string $nombre = '';
    public string $apellido_paterno = '';
    public string $apellido_materno = '';
    public string $curp = '';
    public string $telefono = '';
    public string $perfil = '';
    public string $color = '#64748b';
    public string $estado = '';

    // Datos de acceso
    public string $email = '';

    // Foto
    public ?string $foto_actual = null;

    public function mount(): void
    {
        $this->cargarPerfil();
    }

    public function cargarPerfil(): void
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return;
        }

        $this->user_id = $usuario->id;

        $profesor = Profesor::with('user')
            ->where('user_id', $usuario->id)
            ->first();

        if (!$profesor) {
            return;
        }

        $this->profesor_id = $profesor->id;

        // Datos del profesor
        $this->nombre = $profesor->nombre ?? '';
        $this->apellido_paterno = $profesor->apellido_paterno ?? '';
        $this->apellido_materno = $profesor->apellido_materno ?? '';
        $this->curp = $profesor->CURP ?? '';
        $this->telefono = $profesor->telefono ?? '';
        $this->perfil = $profesor->perfil ?? '';
        $this->color = $profesor->color ?? '#64748b';
        $this->estado = (string) ($profesor->status ?? '');
        $this->foto_actual = $profesor->foto ?? null;

        // Datos del usuario relacionado
        $this->email = $profesor->user->email ?? '';
    }

    public function getNombreCompletoProperty(): string
    {
        return trim($this->nombre . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno);
    }

    public function getEstadoTextoProperty(): string
    {
        return $this->estado === 'true' ? 'Activo' : 'Inactivo';
    }

    public function getFotoPreviewProperty(): string
    {
        if ($this->foto_actual) {
            return asset('storage/profesores/' . $this->foto_actual);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nombreCompleto ?: 'Profesor') . '&background=E2E8F0&color=475569&size=180';
    }

    public function render()
    {
        return view('livewire.profesor.perfil');
    }
}
