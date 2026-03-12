<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Reset password')" :description="__('Please enter your new password below')" />

    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="resetPassword" class="flex flex-col gap-6">
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-1">
            <div class="space-y-4">
                {{-- Correo --}}
                <flux:input wire:model.live="email" :label="__('Email')" type="email" required autocomplete="email" />


                <!-- Password -->
                <flux:input wire:model.live="password" :label="__('Password')" type="password" required
                    autocomplete="new-password" :placeholder="__('Password')" viewable />

                <!-- Confirm Password -->
                <flux:input wire:model.live="password_confirmation" :label="__('Confirm password')" type="password"
                    required autocomplete="new-password" :placeholder="__('Confirm password')" viewable />

            </div>
        </div>

        {{-- Panel de reglas --}}
        <div
            class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">
                        Reglas de la contraseña
                    </h3>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                        La nueva contraseña debe cumplir lo siguiente:
                    </p>
                </div>


            </div>

            <div class="mt-5 space-y-3">
                <div class="flex items-center gap-3">
                    <span
                        class="inline-flex h-6 w-6 items-center justify-center rounded-full text-xs font-bold
                            {{ $this->cumpleMinimo
                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300'
                                : 'bg-neutral-100 text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400' }}">
                        {{ $this->cumpleMinimo ? '✓' : '•' }}
                    </span>
                    <p class="text-sm text-neutral-700 dark:text-neutral-300">
                        Mínimo 8 caracteres
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <span
                        class="inline-flex h-6 w-6 items-center justify-center rounded-full text-xs font-bold
                            {{ $this->cumpleMayuscula
                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300'
                                : 'bg-neutral-100 text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400' }}">
                        {{ $this->cumpleMayuscula ? '✓' : '•' }}
                    </span>
                    <p class="text-sm text-neutral-700 dark:text-neutral-300">
                        Al menos una letra mayúscula
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <span
                        class="inline-flex h-6 w-6 items-center justify-center rounded-full text-xs font-bold
                            {{ $this->cumpleNumero
                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300'
                                : 'bg-neutral-100 text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400' }}">
                        {{ $this->cumpleNumero ? '✓' : '•' }}
                    </span>
                    <p class="text-sm text-neutral-700 dark:text-neutral-300">
                        Al menos un número
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <span
                        class="inline-flex h-6 w-6 items-center justify-center rounded-full text-xs font-bold
                            {{ $this->cumpleEspecial
                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300'
                                : 'bg-neutral-100 text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400' }}">
                        {{ $this->cumpleEspecial ? '✓' : '•' }}
                    </span>
                    <p class="text-sm text-neutral-700 dark:text-neutral-300">
                        Al menos un carácter especial
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <span
                        class="inline-flex h-6 w-6 items-center justify-center rounded-full text-xs font-bold
                            {{ $this->cumpleConfirmacion
                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300'
                                : 'bg-neutral-100 text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400' }}">
                        {{ $this->cumpleConfirmacion ? '✓' : '•' }}
                    </span>
                    <p class="text-sm text-neutral-700 dark:text-neutral-300">
                        La confirmación debe coincidir con la nueva contraseña
                    </p>
                </div>
            </div>

            <div class="mt-5">
                @php
                    $avance = 0;

                    if ($this->cumpleMinimo) {
                        $avance += 20;
                    }

                    if ($this->cumpleMayuscula) {
                        $avance += 20;
                    }

                    if ($this->cumpleNumero) {
                        $avance += 20;
                    }

                    if ($this->cumpleEspecial) {
                        $avance += 20;
                    }

                    if ($this->cumpleConfirmacion) {
                        $avance += 20;
                    }
                @endphp

                <div class="h-2 w-full overflow-hidden rounded-full bg-neutral-100 dark:bg-neutral-800">
                    <div class="h-full rounded-full transition-all duration-300
                            {{ $avance === 100 ? 'bg-emerald-500' : ($avance >= 60 ? 'bg-sky-500' : 'bg-amber-500') }}"
                        style="width: {{ $avance }}%;">
                    </div>
                </div>

                <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                    Progreso de cumplimiento: {{ $avance }}%
                </p>
            </div>
        </div>


        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full" :disabled="!$this->passwordValida">
                {{ __('Reset password') }}
            </flux:button>
        </div>
    </form>
</div>
