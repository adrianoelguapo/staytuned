<x-action-section>
    <x-slot name="title"></x-slot>
    <x-slot name="description"></x-slot>

    <x-slot name="content">
        <div class="mb-4">
            <div style="font-size:2rem; font-weight:600; line-height:1.1; color:#fff; margin-bottom:0; text-align:left;">Sesiones Activas</div>
            <div style="font-size:1rem; color:rgba(255,255,255,0.7); margin-bottom:2.2rem; text-align:left;">Consulta y cierra sesiones abiertas en otros dispositivos o navegadores para mantener tu cuenta protegida.</div>
        </div>

        <div class="mb-3">
            <p class="text-white-50">
                Si lo necesitas, cierra todas las sesiones activas en otros navegadores y dispositivos. Algunas de tus sesiones recientes se muestran abajo.
            </p>
        </div>

        @if (count($this->sessions) > 0)
            <div class="mb-4">
                @foreach ($this->sessions as $session)
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            @if ($session->agent->is_desktop)
                                <i class="bi bi-laptop size-4 text-white-50"></i>
                            @else
                                <i class="bi bi-phone size-4 text-white-50"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-white">
                                {{ $session->agent->platform }}
                                &mdash;
                                {{ $session->agent->browser }}
                            </div>
                            <div class="text-white-50 small">
                                {{ $session->ip_address }},
                                @if ($session->is_current_device)
                                    <span class="text-success fw-bold">Este dispositivo</span>
                                @else
                                    Última actividad {{ $session->last_active }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div>
            <x-confirms-password wire:then="logoutOtherBrowserSessions">
                <button type="button" class="btn btn-primary">Cerrar Sesiones</button>
            </x-confirms-password><x-action-message class="action-message-guardado" on="loggedOut">Hecho.</x-action-message>
        </div>

        <!-- Modal de confirmación para cerrar sesiones -->
        <x-dialog-modal wire:model.live="confirmingLogout">
            <x-slot name="title">
                <div class="text-white">Cerrar Sesiones en Otros Navegadores</div>
            </x-slot>

            <x-slot name="content">
                <p class="text-white">
                    Para confirmar, ingresa tu contraseña.
                </p>
                <div class="mt-3">
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror w-100"
                           placeholder="Contraseña"
                           wire:model="password"
                           wire:keydown.enter="logoutOtherBrowserSessions" />
                    @error('password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </x-slot>

            <x-slot name="footer">
                <div class="d-flex align-items-center">
                    <button type="button"
                            class="btn btn-outline-light"
                            wire:click="$toggle('confirmingLogout')">
                        Cancelar
                    </button>
                    <button type="button"
                            class="btn btn-primary ms-2"
                            wire:click="logoutOtherBrowserSessions">
                        Cerrar Sesiones
                    </button>
                </div>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>
