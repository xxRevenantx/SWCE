<?php

namespace App\Livewire\Admin\Licenciatura;

use App\Models\Licenciatura;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

class EditarLicenciatura extends Component
{

    use WithFileUploads;
    public $licenciaturaId;
    public $nombre, $slug, $nombre_corto, $RVOE;
    public $logo;
    public $logo_nueva;
    public $open = false;

    // Método para abrir el modal con datos
    #[On('editarModal')]
    public function editarModal($id)
    {
        $lic = Licenciatura::findOrFail($id);

        $this->licenciaturaId = $lic->id;
        $this->nombre = $lic->nombre;
        $this->nombre_corto = $lic->nombre_corto;
        $this->RVOE = $lic->RVOE;
        $this->slug = $lic->slug;
        $this->logo = $lic->logo;
        // $this->open = true;
                 // avisa al front que ya hay datos
        $this->dispatch('editar-cargado');
    }

    public function updatedNombre($value)
    {
        $this->slug = Str::slug(trim($value));
    }

    public function actualizarLicenciatura()
    {
        $this->validate([
            'nombre' => 'required|string|max:255|unique:licenciaturas,nombre,' . $this->licenciaturaId,
            'nombre_corto' => 'required|string|max:100|unique:licenciaturas,nombre_corto,' . $this->licenciaturaId,
            'slug' => 'required|string|max:255|unique:licenciaturas,slug,' . $this->licenciaturaId,
            'RVOE' => 'nullable|string|max:100|unique:licenciaturas,RVOE,' . $this->licenciaturaId,
            'logo_nueva' => 'image|nullable|max:2048|mimes:jpeg,jpg,png',
        ],[
            'nombre.required' => 'El nombre de la licenciatura es obligatorio.',
            'nombre.unique' => 'El nombre de la licenciatura ya existe.',
            'RVOE.unique' => 'El RVOE ya existe.',
            'nombre_corto.required' => 'El nombre corto es obligatorio.',
            'nombre_corto.unique' => 'El nombre corto ya existe.',
            'slug.required' => 'La url es obligatoria.',
            'slug.unique' => 'La url ya existe.',
            'logo_nueva.image' => 'El archivo debe ser una logo',
            'logo_nueva.max' => 'El archivo no debe pesar más de 2MB',
            'logo_nueva.mimes' => 'El archivo debe ser formato jpeg, jpg o png',
        ]);

        if ($this->logo_nueva) {
            // Eliminar la logo anterior si existe
            if ($this->logo) {
            Storage::delete('licenciaturas/' . $this->logo);
            }

            $logo = $this->logo_nueva->store('licenciaturas');
            $datos['logo'] = str_replace('licenciaturas/', '', $logo);
        }

        Licenciatura::findOrFail($this->licenciaturaId)->update([
            'nombre' => $this->nombre,
            'slug' => $this->slug,
            'nombre_corto' => $this->nombre_corto,
            'RVOE' => $this->RVOE ? $this->RVOE : NULL,
            'logo' => $this->logo_nueva ? $datos['logo'] : $this->logo,
        ]);

        $this->dispatch('swal', [
            'title' => '¡Licenciatura actualizada!',
            'icon' => 'success',
            'position' => 'top-end',
        ]);

        $this->dispatch('refreshLicenciaturas');

          // 👉 Avisamos al front que debe cerrar el modal
         $this->dispatch('cerrar-modal-editar');

        $this->cerrarModal();
    }

    public function cerrarModal()
    {
        $this->reset(['open', 'licenciaturaId', 'nombre', 'slug', 'nombre_corto', 'RVOE', 'logo_nueva']);
        $this->resetValidation();
    }



    public function render()
    {
        return view('livewire.admin.licenciatura.editar-licenciatura');
    }
}
