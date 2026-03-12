<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-1">
                <div class="space-y-6">
                    <div x-data="{ mostrar: false }" class="relative">
                        <flux:input wire:model.live="current_password" :label="__('Current password')"
                            placeholder="Escribe tu contraseña actual" x-bind:type="mostrar ? 'text' : 'password'"
                            required autocomplete="current-password" class="pr-12" />

                        <button type="button" @click="mostrar = !mostrar"
                            class="absolute right-3 top-[28px] inline-flex h-9 w-9 items-center justify-center rounded-xl text-neutral-500 transition hover:bg-neutral-100 hover:text-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-neutral-200"
                            aria-label="Mostrar u ocultar contraseña actual">
                            <svg x-show="!mostrar" class="h-5 w-5" fill="none" stroke="currentColor"
                                stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5.25 12 5.25S20.268 7.943 21.542 12c-1.274 4.057-5.065 6.75-9.542 6.75S3.732 16.057 2.458 12Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0a3 3 0 0 1 6 0Z" />
                            </svg>

                            <svg x-show="mostrar" x-cloak class="h-5 w-5" fill="none" stroke="currentColor"
                                stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.477 10.488A3 3 0 0 0 13.5 13.5" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.88 5.09A9.953 9.953 0 0 1 12 4.875c4.478 0 8.268 2.693 9.543 6.75a9.773 9.773 0 0 1-1.563 3.029M6.228 6.228C4.626 7.36 3.387 9.038 2.458 12c1.274 4.057 5.065 6.75 9.542 6.75a9.96 9.96 0 0 0 5.227-1.477" />
                            </svg>
                        </button>
                    </div>

                    <div x-data="{ mostrar: false }" class="relative">
                        <flux:input wire:model.live="password" placeholder="Escribe tu nueva contraseña"
                            :label="__('New password')" x-bind:type="mostrar ? 'text' : 'password'" required
                            autocomplete="new-password" class="pr-12" />

                        <button type="button" @click="mostrar = !mostrar"
                            class="absolute right-3 top-[28px] inline-flex h-9 w-9 items-center justify-center rounded-xl text-neutral-500 transition hover:bg-neutral-100 hover:text-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-neutral-200"
                            aria-label="Mostrar u ocultar nueva contraseña">
                            <svg x-show="!mostrar" class="h-5 w-5" fill="none" stroke="currentColor"
                                stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5.25 12 5.25S20.268 7.943 21.542 12c-1.274 4.057-5.065 6.75-9.542 6.75S3.732 16.057 2.458 12Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0a3 3 0 0 1 6 0Z" />
                            </svg>

                            <svg x-show="mostrar" x-cloak class="h-5 w-5" fill="none" stroke="currentColor"
                                stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.477 10.488A3 3 0 0 0 13.5 13.5" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.88 5.09A9.953 9.953 0 0 1 12 4.875c4.478 0 8.268 2.693 9.543 6.75a9.773 9.773 0 0 1-1.563 3.029M6.228 6.228C4.626 7.36 3.387 9.038 2.458 12c1.274 4.057 5.065 6.75 9.542 6.75a9.96 9.96 0 0 0 5.227-1.477" />
                            </svg>
                        </button>
                    </div>

                    <div x-data="{ mostrar: false }" class="relative">
                        <flux:input wire:model.live="password_confirmation" :label="__('Confirm Password')"
                            placeholder="Confirma tu contraseña" x-bind:type="mostrar ? 'text' : 'password'" required
                            autocomplete="new-password" class="pr-12" />

                        <button type="button" @click="mostrar = !mostrar"
                            class="absolute right-3 top-[28px] inline-flex h-9 w-9 items-center justify-center rounded-xl text-neutral-500 transition hover:bg-neutral-100 hover:text-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-neutral-200"
                            aria-label="Mostrar u ocultar confirmación de contraseña">
                            <svg x-show="!mostrar" class="h-5 w-5" fill="none" stroke="currentColor"
                                stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5.25 12 5.25S20.268 7.943 21.542 12c-1.274 4.057-5.065 6.75-9.542 6.75S3.732 16.057 2.458 12Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0a3 3 0 0 1 6 0Z" />
                            </svg>

                            <svg x-show="mostrar" x-cloak class="h-5 w-5" fill="none" stroke="currentColor"
                                stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.477 10.488A3 3 0 0 0 13.5 13.5" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.88 5.09A9.953 9.953 0 0 1 12 4.875c4.478 0 8.268 2.693 9.543 6.75a9.773 9.773 0 0 1-1.563 3.029M6.228 6.228C4.626 7.36 3.387 9.038 2.458 12c1.274 4.057 5.065 6.75 9.542 6.75a9.96 9.96 0 0 0 5.227-1.477" />
                            </svg>
                        </button>
                    </div>
                </div>

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

                    <div class="mt-5 space-y-1">
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
                        <div class="h-2 w-full overflow-hidden rounded-full bg-neutral-100 dark:bg-neutral-800">
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
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full" :disabled="!$this->passwordValida">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
