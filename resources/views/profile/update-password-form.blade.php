<x-form-section submit="updatePassword">
    <x-slot name="title"></x-slot>
    <x-slot name="description"></x-slot>

    <x-slot name="form">
        <div class="mb-4">
            <div style="font-size:2rem; font-weight:600; line-height:1.1; color:#fff; margin-bottom:0; text-align:left;">Cambiar Contraseña</div>
            <div style="font-size:1rem; color:rgba(255,255,255,0.7); margin-bottom:2.2rem; text-align:left;">Actualiza tu contraseña para mantener tu cuenta segura. Usa una clave fácil de recordar pero difícil de adivinar.</div>
        </div>

        <div class="mb-4">
            <label for="current_password" class="form-label">Contraseña Actual</label>
            <input id="current_password" type="password"
                   class="form-control @error('state.current_password') is-invalid @enderror"
                   wire:model="state.current_password" autocomplete="current-password" />
            @error('state.current_password')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">Nueva Contraseña</label>
            <input id="password" type="password"
                   class="form-control @error('state.password') is-invalid @enderror"
                   wire:model="state.password" autocomplete="new-password" />
            @error('state.password')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
            <input id="password_confirmation" type="password"
                   class="form-control @error('state.password_confirmation') is-invalid @enderror"
                   wire:model="state.password_confirmation" autocomplete="new-password" />
            @error('state.password_confirmation')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    </x-slot>

    <x-slot name="actions">
        <div class="text-start w-100" style="display: flex; align-items: center; gap: 0;">
            <button type="submit" class="btn btn-primary">Guardar Contraseña</button><x-action-message class="action-message-guardado" on="saved">Guardado.</x-action-message>
        </div>
    </x-slot>

</x-form-section>
