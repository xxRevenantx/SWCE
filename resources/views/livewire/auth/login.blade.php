<div class="flex flex-col gap-7 bg-white shadow-2xl dark:bg-neutral-800 p-6 rounded-lg ">
    <x-auth-header :title="__('Inicia sesión en tu cuenta')" :description="__('Ingresa tu email para iniciar sesión en tu panel')" />

    {{-- Turnstile solo en producción u otros entornos distintos de local/testing --}}
    {{-- @if ($this->usaTurnstile) --}}
    <div class="mt-4" wire:ignore>
        <div class="cf-turnstile" data-sitekey="{{ env('TURNSTILE_SITE_KEY') }}" data-callback="onTurnstileSuccess">
        </div>
    </div>

    @error('cf_turnstile_response')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror

    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

    <script>
        function onTurnstileSuccess(token) {
            @this.set('cf_turnstile_response', token);
        }

        document.addEventListener('livewire:init', () => {
            Livewire.on('turnstile-reset', () => {
                if (window.turnstile) {
                    turnstile.reset();
                }
            });
        });
    </script>
    {{-- @endif --}}

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input wire:model="email" :label="__('Correo electrónico')" type="email" autofocus autocomplete="email"
            placeholder="Correo electrónico" />

        <!-- Password -->
        <div class="relative">
            <flux:input wire:model="password" :label="__('Contraseña')" type="password" autocomplete="current-password"
                :placeholder="__('Contraseña')" />

            @if (Route::has('password.request'))
                <flux:link class="absolute end-0 top-0 text-sm" :href="route('password.request')" wire:navigate>
                    {{ __('¿Olvidaste tu contraseña?') }}
                </flux:link>
            @endif
        </div>

        <!-- Remember Me -->
        <flux:checkbox wire:model="remember" :label="__('Mantener la sesión activa')" />

        <div class="flex items-center justify-end">
            <flux:button style="background: #04689c; color:white; cursor:pointer" type="submit"
                class="w-full text-white">
                {{ __('Iniciar sesión') }}
            </flux:button>
        </div>
    </form>
</div>
