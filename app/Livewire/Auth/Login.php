<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Login extends Component
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    // Token de Turnstile
    public ?string $cf_turnstile_response = null;

    public function login(): void
    {
        try {
            $this->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
                'cf_turnstile_response' => ['required', 'turnstile'],
            ], [
                'cf_turnstile_response.required' => 'Por favor, verifica que no eres un robot.',
                'cf_turnstile_response.turnstile' => 'La verificación de seguridad falló. Intenta de nuevo.',

            ]);

            $this->ensureIsNotRateLimited();

            $user = $this->validateCredentials();

            if (Features::canManageTwoFactorAuthentication() && $user->hasEnabledTwoFactorAuthentication()) {
                Session::put([
                    'login.id' => $user->getKey(),
                    'login.remember' => $this->remember,
                ]);

                $this->redirect(route('two-factor.login'), navigate: true);
                return;
            }

            Auth::login($user, $this->remember);

            RateLimiter::clear($this->throttleKey());
            Session::regenerate();

            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

        } catch (ValidationException $e) {
            // resetear captcha
            $this->reset('cf_turnstile_response');
            $this->dispatch('turnstile-reset');
            throw $e;
        }
    }

    protected function validateCredentials(): User
    {
        $user = Auth::getProvider()->retrieveByCredentials([
            'email' => $this->email,
            'password' => $this->password,
        ]);

        if (!$user || !Auth::getProvider()->validateCredentials($user, ['password' => $this->password])) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return $user;
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}
