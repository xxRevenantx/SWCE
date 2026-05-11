<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class EditarUsuario extends Component
{


    public $usuario;
    public $open = false;
    public $userId;
    public $username;
    public $numero_empleado;
    public $email;
    public $status;

    public $change_password;
    public $rol;

    public $rol_name;

    public $toggle = false;


    // Método para abrir el modal con datos
    #[On('editarModal')]
    public function editarModal($id)
    {
        $this->usuario = User::findOrFail($id);
        $user = User::findOrFail($id);

        $this->userId = $user->id;
        $this->username = $user->username;
        $this->numero_empleado = $user->numero_empleado;
        $this->email = $user->email;
        $this->status = $user->status == "true" ? true : false;
        $this->rol = $user->roles->pluck('id')->toArray();

        $this->rol_name = $user->roles->pluck('name')->implode(', ');

        $this->dispatch('editar-cargado');
    }


    // TOGGLE STATUS
    public function toggleStatus()
    {
        $this->toggle = true;
    }


    public function actualizarUsuario()
    {
        // El correo puede ser de cualquier proveedor válido: Gmail, Hotmail, Outlook, institucional, etc.
        $reglasEmail = ['required', 'email', 'max:255', 'unique:users,email,' . $this->userId];

        $adminRoleId = Role::where('name', 'Admin')->value('id');
        $rolProfesorId = Role::where('name', 'Profesor')->value('id');
        $numeroEmpleado = ['nullable', 'string', 'max:30', 'unique:users,numero_empleado,' . $this->userId];

        if ($rolProfesorId && in_array((int) $rolProfesorId, array_map('intval', (array) $this->rol), true)) {
            $numeroEmpleado[0] = 'required';
        }

        $this->validate([
            'username' => 'required|string|max:15|unique:users,username,' . $this->userId,
            'numero_empleado' => $numeroEmpleado,
            'email' => $reglasEmail,
            'status' => 'required|boolean',
            'rol' => 'required',

        ], [
            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.unique' => 'El nombre de usuario ya está en uso.',
            'numero_empleado.required' => 'El número de empleado es obligatorio para usuarios con rol Profesor.',
            'numero_empleado.unique' => 'El número de empleado ya está asignado a otro usuario.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no es válido.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'rol.required' => 'Debes seleccionar al menos un rol.',
        ]);

        // No permitir asignar Admin sin tenerlo.
        $this->username = trim($this->username);
        $this->numero_empleado = filled($this->numero_empleado) ? trim($this->numero_empleado) : null;
        $this->email = strtolower(trim($this->email));
        if (in_array($adminRoleId, $this->rol) && !auth()->user()->hasRole('Admin')) {
            abort(403, 'No autorizado a asignar el rol Admin');
        }

        $this->status = $this->status ? 'true' : 'false';


        $eraAdmin = $this->usuario->hasRole('Admin');

        // Update + roles
        $this->usuario->update([
            'username' => $this->username,
            'numero_empleado' => $this->numero_empleado,
            'email' => $this->email,
            'status' => $this->status,
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
            'icon' => 'success',
            'position' => 'top-end',
        ]);

        // Mantén este evento para que la lista se recalcule completa
        $this->dispatch('refreshUsuarios');

        // 👉 Avisamos al front que debe cerrar el modal
        $this->dispatch('cerrar-modal-editar');

        $this->cerrarModal();
    }


    public function cerrarModal()
    {
        $this->reset(['open', 'userId', 'username', 'numero_empleado', 'email', 'status', 'rol']);
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
