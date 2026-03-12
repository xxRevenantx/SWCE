<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class ResetPassword extends Component
{
    #[Locked]
    public string $token = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * Inicializa el componente.
     */
    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->string('email')->value();
    }

    /**
     * Valida en tiempo real cuando el usuario escribe.
     */
    public function updated($property): void
    {
        if (in_array($property, ['email', 'password', 'password_confirmation'])) {
            $this->validateOnly($property, $this->rules(), $this->messages());
        }
    }

    /**
     * Reglas de validación.
     */
    protected function rules(): array
    {
        return [
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
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
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'La contraseña no cumple con el formato requerido.',

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
     * Indica si la contraseña cumple todas las reglas.
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
     * Reinicia la contraseña del usuario.
     */
    public function resetPassword(): void
    {
        $this->validate($this->rules(), $this->messages());

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                    'change_password' => true,
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PasswordReset) {
            $this->addError('email', __($status));
            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
