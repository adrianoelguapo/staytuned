<x-action-section>
    <x-slot name="title"></x-slot>
    <x-slot name="description"></x-slot>

    <x-slot name="content">
        <div class="mb-4">
            <div style="font-size:2rem; font-weight:600; line-height:1.1; color:#fff; margin-bottom:0; text-align:left;">Autenticación de Dos Factores</div>
            <div style="font-size:1rem; color:rgba(255,255,255,0.7); margin-bottom:2.2rem; text-align:left;">Añade una capa extra de seguridad a tu cuenta activando la autenticación en dos pasos.</div>
        </div>

        <h3 class="mb-3 text-white">
            @if ($this->enabled)
                @if ($showingConfirmation)
                    Termina de habilitar la autenticación de dos factores.
                @else
                    La autenticación de dos factores está habilitada.
                @endif
            @else
                No has habilitado la autenticación de dos factores.
            @endif
        </h3>

        <div class="mb-4">
            <p class="text-white-50">
                Cuando la autenticación de dos factores esté habilitada, se te pedirá un token aleatorio al iniciar sesión. Podrás obtenerlo en la app de autenticación de tu teléfono (por ejemplo, Google Authenticator).
            </p>
        </div>

        @if ($this->enabled)
            @if ($showingQrCode)
                <div class="mb-4">
                    <p class="text-white">
                        @if ($showingConfirmation)
                            Escanea este código QR con tu app de autenticación o ingresa la clave de configuración y luego proporciona el código OTP generado.
                        @else
                            La autenticación de dos factores ya está habilitada. Escanea el siguiente código QR con tu app de autenticación o ingresa la clave de configuración.
                        @endif
                    </p>
                </div>

                <div class="mb-4 p-3 bg-white d-inline-block">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>

                <div class="mb-4">
                    <p class="text-white">
                        <strong>Clave de Configuración:</strong> {{ decrypt($this->user->two_factor_secret) }}
                    </p>
                </div>

                @if ($showingConfirmation)
                    <div class="mb-4">
                        <label for="code" class="form-label text-white">Código</label>
                        <input id="code" type="text" name="code"
                               class="form-control @error('code') is-invalid @enderror w-50"
                               wire:model="code"
                               wire:keydown.enter="confirmTwoFactorAuthentication"
                               inputmode="numeric" autofocus autocomplete="one-time-code" />
                        @error('code')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
            @endif

            @if ($showingRecoveryCodes)
                <div class="mb-4">
                    <p class="text-white">
                        Guarda estos códigos de recuperación en un lugar seguro. Podrán usarse si pierdes el acceso a tu autenticador.
                    </p>
                </div>

                <div class="mb-4 px-4 py-3 bg-white text-monospace text-dark rounded">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <div>{{ $code }}</div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="d-flex gap-2 flex-wrap">
            @if (! $this->enabled)
                <x-confirms-password wire:then="enableTwoFactorAuthentication">
                    <button type="button" class="btn btn-primary">
                        Habilitar
                    </button>
                </x-confirms-password>
            @else
                @if ($showingRecoveryCodes)
                    <x-confirms-password wire:then="regenerateRecoveryCodes">
                        <button type="button" class="btn btn-outline-light">
                            Regenerar Códigos
                        </button>
                    </x-confirms-password>
                @elseif ($showingConfirmation)
                    <x-confirms-password wire:then="confirmTwoFactorAuthentication">
                        <button type="button" class="btn btn-primary me-2">
                            Confirmar
                        </button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="showRecoveryCodes">
                        <button type="button" class="btn btn-outline-light me-2">
                            Mostrar Códigos
                        </button>
                    </x-confirms-password>
                @endif

                @if ($showingConfirmation)
                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <button type="button" class="btn btn-outline-light">
                            Cancelar
                        </button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <button type="button" class="btn btn-danger">
                            Deshabilitar
                        </button>
                    </x-confirms-password>
                @endif
            @endif
        </div>
    </x-slot>
</x-action-section>
