<x-action-section>
    <x-slot name="title"></x-slot>
    <x-slot name="description"></x-slot>

    <x-slot name="content">
        <div class="mb-4">
            <div style="font-size:2rem; font-weight:600; line-height:1.1; color:#fff; margin-bottom:0; text-align:left;">Eliminar Cuenta</div>
            <div style="font-size:1rem; color:rgba(255,255,255,0.7); margin-bottom:2.2rem; text-align:left;">Esta acción eliminará tu cuenta y todos tus datos de forma permanente. No podrás recuperarlos después.</div>
        </div>
        <p class="text-white-50 mb-3">
            Antes de continuar, descarga toda la información que desees conservar.
        </p>
        <x-confirms-password wire:then="confirmUserDeletion">
            <button type="button" class="btn btn-danger">
                Eliminar Cuenta
            </button>
        </x-confirms-password>

        <!-- Modal de confirmación de eliminación -->
        <x-dialog-modal wire:model.live="confirmingUserDeletion">
            <x-slot name="title">
                <div class="text-white">¿Estás Seguro?</div>
            </x-slot>

            <x-slot name="content">
                <p class="text-white">
                    Una vez eliminada, tu cuenta y todos tus datos se borrarán permanentemente. Por favor, ingresa tu contraseña para confirmar.
                </p>
                <div class="mt-3">
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror w-100"
                           placeholder="Contraseña"
                           wire:model="password"
                           wire:keydown.enter="deleteUser" />
                    @error('password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </x-slot>

            <x-slot name="footer">
                <div class="d-flex align-items-center">
                    <button type="button"
                            class="btn btn-outline-light"
                            wire:click="$toggle('confirmingUserDeletion')">
                        Cancelar
                    </button>
                    <button type="button"
                            class="btn btn-danger ms-2"
                            wire:click="deleteUser">
                        Eliminar Cuenta
                    </button>
                </div>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>
