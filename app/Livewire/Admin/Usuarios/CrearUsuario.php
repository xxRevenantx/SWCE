<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Livewire\Component;

class CrearUsuario extends Component
{
    public string $username = '';
    public string $numero_empleado = '';
    public string $email = '';
    public array $rol = [];


    /** Si el usuario modificó manualmente el username, dejamos de autollenarlo desde el email */
    public bool $usernameEdited = false;

    protected function rules(): array
    {
        // El correo puede ser de cualquier proveedor válido: Gmail, Hotmail, Outlook, institucional, etc.
        $reglasEmail = ['required', 'email', 'max:255', 'unique:users,email'];

        $rolProfesorId = Role::where('name', 'Profesor')->value('id');
        $numeroEmpleado = ['nullable', 'string', 'max:30', 'unique:users,numero_empleado'];

        if ($rolProfesorId && in_array((int) $rolProfesorId, array_map('intval', $this->rol), true)) {
            $numeroEmpleado[0] = 'required';
        }

        return [
            'username' => 'required|unique:users,username|max:15',
            'numero_empleado' => $numeroEmpleado,
            'email' => $reglasEmail,
            'rol' => 'required|array|min:1',
            'rol.*' => 'integer|exists:roles,id',
        ];
    }

    protected $messages = [
        'username.unique' => 'El nombre de usuario ya está en uso.',
        'email.unique' => 'El correo electrónico ya está en uso.',
        'email.email' => 'El correo electrónico no es válido.',
        'numero_empleado.required' => 'El número de empleado es obligatorio para usuarios con rol Profesor.',
        'numero_empleado.unique' => 'El número de empleado ya está asignado a otro usuario.',
        'rol.required' => 'Debes seleccionar al menos un rol.',
        'rol.array' => 'Formato de roles inválido.',
        'rol.min' => 'Debes seleccionar al menos un rol.',
        'rol.*.exists' => 'Algún rol seleccionado no existe.',
    ];

    public function mount(): void
    {
        // El username empieza vacío; se llenará al escribir el email.
        $this->username = '';

    }

    /** Sufijo aleatorio de 3 dígitos (000–999) */
    private function sufijoTresDigitos(): string
    {
        return str_pad((string) random_int(0, 999), 3, '0', STR_PAD_LEFT);
    }

    private function sugerirUsernameDesdeEmail(string $email): string
    {
        // 1) Local-part del email
        $local = strtolower(trim((string) \Illuminate\Support\Str::before($email, '@')));

        // 2) Quita puntos y guiones explícitamente
        $local = str_replace(['.', '-'], '', $local);

        // 3) Deja solo letras, números y guion_bajo (sin puntos ni guiones)
        $base = preg_replace('/[^a-z0-9]+/i', '', $local) ?: 'user';

        $maxLen = 15;
        $suffixLen = 3;

        // 4) Recorta base para dejar espacio al sufijo de 3 dígitos
        $roomForBase = max(1, $maxLen - $suffixLen);
        $base = substr($base, 0, $roomForBase);
        if ($base === '') {
            $base = substr('user', 0, $roomForBase);
        }

        // 5) Intenta con sufijos aleatorios primero
        for ($try = 0; $try < 200; $try++) {
            $candidate = $base . $this->sufijoTresDigitos();
            if (!\App\Models\User::where('username', $candidate)->exists()) {
                return $candidate;
            }
        }

        // 6) Fallback determinista (000–999)
        for ($i = 0; $i <= 999; $i++) {
            $suffix = str_pad((string) $i, 3, '0', STR_PAD_LEFT);
            $candidate = $base . $suffix;
            if (!\App\Models\User::where('username', $candidate)->exists()) {
                return $candidate;
            }
        }

        // 7) Último recurso
        $mini = substr($base, 0, max(1, $roomForBase - 1));
        return $mini . strtolower(\Illuminate\Support\Str::random(1)) . $this->sufijoTresDigitos();
    }
    /** Validación “en vivo”; además, reaccionamos a cambios específicos */
    public function updated($field): void
    {
        // Marcar que el usuario editó manualmente el username
        if ($field === 'username') {
            $this->usernameEdited = true;
        }

        // Si cambia el email y aún no han editado el username a mano, sugerimos uno
        if ($field === 'email' && !$this->usernameEdited) {
            $this->username = $this->sugerirUsernameDesdeEmail($this->email);
        }

        // Validar el campo (rol como grupo)
        $this->validateOnly(
            $field === 'rol' || str_starts_with($field, 'rol.')
            ? 'rol'
            : $field
        );
    }

    public function guardarUsuario()
    {
        $this->validate();

        $user = User::create([
            'username' => trim($this->username),
            'numero_empleado' => filled($this->numero_empleado) ? trim($this->numero_empleado) : null,
            'email' => strtolower(trim($this->email)),
            'password' => bcrypt('password'),
            'status' => 'true',
            'photo' => null,
        ]);

        $user->roles()->sync($this->rol);

        $this->dispatch('swal', [
            'title' => '¡Usuario creado correctamente!',
            'icon' => 'success',
            'position' => 'top-end',
        ]);

        // Reset para crear otro usuario
        $this->reset(['email', 'numero_empleado', 'rol']);
        $this->username = '';      // vuelve a vaciar
        $this->usernameEdited = false;

        $this->dispatch('refreshUsuarios');
    }

    public function render()
    {
        $roles = Role::all();
        return view('livewire.admin.usuarios.crear-usuario', compact('roles'));
    }
}
