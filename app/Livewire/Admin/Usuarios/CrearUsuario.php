<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class CrearUsuario extends Component
{

    public $username;
    public $email;
   public $rol = [];

    protected $rules = [
        'username' => 'required|unique:users,username|max:15',
        'email'    => 'required|email|unique:users,email',
        'rol'      => 'required|array|min:1',     // validar que haya al menos 1
        'rol.*'    => 'integer|exists:roles,id',  // validar cada id
    ];

    protected $messages = [
        'username.unique' => 'El nombre de usuario ya está en uso.',
        'email.unique'    => 'El correo electrónico ya está en uso.',
        'email.email'     => 'El correo electrónico no es válido.',
        'rol.required'    => 'Debes seleccionar al menos un rol.',
        'rol.array'       => 'Formato de roles inválido.',
        'rol.min'         => 'Debes seleccionar al menos un rol.',
        'rol.*.exists'    => 'Algún rol seleccionado no existe.',
    ];


    public function mount()
    {
        $this->username = $this->generarUsernameUnico();
    }

    private function generarUsernameUnico(): string
    {
        do {
            $username = 'user_' . Str::random(5);
        } while (User::where('username', $username)->exists());

        return $username;
    }

      // Validación “en vivo” por campo (incluye el grupo rol)
    public function updated($field)
    {
        $this->validateOnly($field === 'rol' || str_starts_with($field, 'rol.')
            ? 'rol'
            : $field
        );
    }




    public function guardarUsuario(){
        $this->validate();
        // Aquí agregamos la lógica para guardar el usuario en la base de datos
        $user = User::create([
            'username' => trim($this->username),
            'email' => trim($this->email),
            'password' => bcrypt('12345678'), // Contraseña por defecto
            'status' => 'true',
            'photo' => null,

        ]);

        // Asignar el rol al usuario
        $user->roles()->sync($this->rol);


        $this->dispatch('swal', [
            'title' => '¡Usuario creado correctamente!',
            'icon' => 'success',
            'position' => 'top-end',
        ]);

        // Limpiar los campos después de guardar
        $this->username = $this->generarUsernameUnico();
        $this->email = '';
        $this->rol = [];

       $this->dispatch('refreshUsuarios');

    }


    public function render()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        return view('livewire.admin.usuarios.crear-usuario', compact('roles'));
    }
}
