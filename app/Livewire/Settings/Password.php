<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Password extends Component
{
    public string $current_password = '';

    public string $password = '';

    public bool $change_password = false;

    public string $password_confirmation = '';

    /**
     * Valida en tiempo real cuando el usuario escribe.
     */
    public function updated($property): void
    {
        if (in_array($property, ['current_password', 'password', 'password_confirmation'])) {
            $this->validateOnly($property, $this->rules(), $this->messages());
        }
    }

    /**
     * Reglas de validación.
     */
    protected function rules(): array
    {
        return [
            'current_password' => ['required', 'string', 'current_password'],
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
            ],
            'password_confirmation' => ['required', 'string'],
        ];
    }

    /**
     * Mensajes personalizados.
     */
    protected function messages(): array
    {
        return [
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'current_password.current_password' => 'La contraseña actual no es correcta.',

            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'La nueva contraseña no cumple con el formato requerido.',

            'password_confirmation.required' => 'La confirmación de contraseña es obligatoria.',
        ];
    }

    /**
     * Indica si tiene al menos 8 caracteres.
     */
    public function getCumpleMinimoProperty(): bool
    {
        return mb_strlen($this->password) >= 8;
    }

    /**
     * Indica si contiene una mayúscula.
     */
    public function getCumpleMayusculaProperty(): bool
    {
        return preg_match('/[A-Z]/', $this->password) === 1;
    }

    /**
     * Indica si contiene un número.
     */
    public function getCumpleNumeroProperty(): bool
    {
        return preg_match('/[0-9]/', $this->password) === 1;
    }

    /**
     * Indica si contiene un carácter especial.
     */
    public function getCumpleEspecialProperty(): bool
    {
        return preg_match('/[^A-Za-z0-9]/', $this->password) === 1;
    }

    /**
     * Indica si la confirmación coincide.
     */
    public function getCumpleConfirmacionProperty(): bool
    {
        return $this->password !== ''
            && $this->password_confirmation !== ''
            && $this->password === $this->password_confirmation;
    }

    /**
     * Indica si la nueva contraseña cumple todas las reglas.
     */
    public function getPasswordValidaProperty(): bool
    {
        return $this->cumpleMinimo
            && $this->cumpleMayuscula
            && $this->cumpleNumero
            && $this->cumpleEspecial
            && $this->cumpleConfirmacion;
    }

    /**
     * Actualiza la contraseña del usuario autenticado.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate($this->rules(), $this->messages());
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        $usuario = Auth::user();

        if (!$usuario) {
            return;
        }

        $usuario->update([
            'password' => Hash::make($validated['password']),
            'change_password' => true,
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');

        $this->dispatch('refreshHeader');
    }

    public function render()
    {
        return view('livewire.settings.password');
    }
}
