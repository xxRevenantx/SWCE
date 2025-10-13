<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

use Spatie\Permission\Models\Role;

class EditarUsuario extends Component
{


    public $usuario;
    public $open = false;
    public $userId;
    public $username;
    public $email;
    public $status;
    public $rol;

    public $rol_name;

    public $toggle = false;


      // Método para abrir el modal con datos
      #[On('abrirModal')]
      public function abrirModal($id)
      {
            $this->usuario = User::findOrFail($id);
            $user = User::findOrFail($id);

            $this->userId = $user->id;
            $this->username = $user->username;
            $this->email = $user->email;
            $this->status = $user->status == "true" ? true : false;
            $this->rol = $user->roles->pluck('id')->toArray();

            $this->rol_name = $user->roles->pluck('name')->implode(', ');

          $this->open = true;
      }


      // TOGGLE STATUS
      public function toggleStatus(){
            $this->toggle = true;
      }


    public function actualizarUsuario()
{
    $this->validate([
        'username' => 'required|string|max:15|unique:users,username,' . $this->userId,
        'email'    => 'required|email|max:50|unique:users,email,' . $this->userId,
        'status'   => 'required|boolean',
        'rol'      => 'required',
    ],[
        'username.required' => 'El nombre de usuario es obligatorio.',
        'username.unique'   => 'El nombre de usuario ya está en uso.',
        'email.required'    => 'El correo electrónico es obligatorio.',
        'email.email'       => 'El correo electrónico no es válido.',
        'email.unique'      => 'El correo electrónico ya está en uso.',
        'rol.required'      => 'Debes seleccionar al menos un rol.',
    ]);

    // Noo permitir asignar Admin sin tenerlo
    $adminRoleId = \Spatie\Permission\Models\Role::where('name', 'Admin')->value('id');
    $this->username = trim($this->username);
    $this->email    = trim($this->email);
    if (in_array($adminRoleId, $this->rol) && !auth()->user()->hasRole('Admin')) {
        abort(403, 'No autorizado a asignar el rol Admin');
    }

    $this->status = $this->status ? 'true' : 'false';


    $eraAdmin = $this->usuario->hasRole('Admin');

    // Update + roles
    $this->usuario->update([
        'username' => $this->username,
        'email'    => $this->email,
        'status'   => $this->status,
    ]);
    $this->usuario->roles()->sync($this->rol);


    $quedoAdmin = $this->usuario->fresh()->hasRole('Admin');


    if ($eraAdmin !== $quedoAdmin) {
        $admin_final = $quedoAdmin ? 1 : -1;
        // Evento Livewire para otros componentes
        $this->dispatch('admin_final', ['admin_final' => $admin_final]);
    }

    $this->dispatch('swal', [
        'title' => '¡Usuario actualizado correctamente!',
        'icon'  => 'success',
        'position' => 'top-end',
    ]);

    // Mantén este evento para que la lista se recalcule completa
    $this->dispatch('refreshUsuarios');

    $this->cerrarModal();
}


      public function cerrarModal()
      {
          $this->reset(['open', 'userId', 'username', 'email', 'status', 'rol']);
          $this->toggle = false;
          $this->resetValidation();
      }



    public function render()
    {
          // Si el usuario autenticado NO tiene el rol Admin, excluye ese rol
        if (!auth()->user()->hasRole('Admin')) {
            $roles = Role::where('name', '!=', 'Admin')->get();
        } else {
            $roles = Role::all();
        }

        return view('livewire.admin.usuarios.editar-usuario', compact('roles'));

    }
}
