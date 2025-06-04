<x-form-section submit="updateProfileInformation">
    <x-slot name="title"></x-slot>
    <x-slot name="description"></x-slot>

    <x-slot name="form">
        <div class="mb-4">
            <div style="font-size:2rem; font-weight:600; line-height:1.1; color:#fff; margin-bottom:0; text-align:left;">Información de Perfil</div>
            <div style="font-size:1rem; color:rgba(255,255,255,0.7); margin-bottom:2.2rem; text-align:left;">Modifica tu información básica, tu nombre de usuario y tu correo electrónico.</div>
        </div>

        <!-- Nombre -->
        <div class="mb-4">
            <label for="name">Nombre</label>
            <input id="name" type="text"
                   class="form-control w-100 @error('state.name') is-invalid @enderror"
                   wire:model="state.name" required autocomplete="name" />
            @error('state.name')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Nombre de usuario -->
        <div class="mb-4">
            <label for="username">Nombre de usuario</label>
            <input id="username" type="text"
                   class="form-control w-100 @error('state.username') is-invalid @enderror"
                   wire:model="state.username" required autocomplete="username" />
            @error('state.username')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email">Correo Electrónico</label>
            <input id="email" type="email"
                   class="form-control w-100 @error('state.email') is-invalid @enderror"
                   wire:model="state.email" required autocomplete="email" />
            @error('state.email')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) 
                 && ! $this->user->hasVerifiedEmail())
                <div class="mt-2 text-white-50">
                    Tu dirección de correo no está verificada.
                    <button type="button"
                            class="btn btn-link p-0"
                            wire:click.prevent="sendEmailVerification">
                        Haz clic aquí para reenviar el email de verificación.
                    </button>
                </div>
                @if ($this->verificationLinkSent)
                    <p class="text-success mt-1">Se ha enviado un nuevo enlace de verificación.</p>
                @endif
            @endif
        </div>

        <!-- Biografía -->
        <div class="mb-4">
            <label for="bio">Biografía</label>
            <textarea id="bio" type="text" maxlength="255"
                class="form-control w-100 @error('state.bio') is-invalid @enderror"
                wire:model.defer="state.bio" rows="3"></textarea>
            <div class="form-text">Máximo 255 caracteres.</div>
            @error('state.bio')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    </x-slot>

    <x-slot name="actions">
        <div class="text-start w-100" style="display: flex; align-items: center; gap: 0;">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button><x-action-message class="action-message-guardado" on="saved">Guardado.</x-action-message>
        </div>
    </x-slot>

</x-form-section>
