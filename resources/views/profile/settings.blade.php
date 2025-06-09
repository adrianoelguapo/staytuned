{{-- resources/views/profile/settings.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Configuración de Perfil | StayTuned</title>

    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
      rel="stylesheet"
    />

    <!-- CSS personalizado -->
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet" />
</head>
<body class="dashboard-body">
    <!-- ==========================
         NAVBAR (igual que en Dashboard)
         ========================== -->
    <nav class="navbar navbar-expand-lg px-5 py-3">
        <div class="d-flex align-items-center">
            <!-- Offcanvas toggle: solo <lg> -->
            <button
              class="btn btn-link btn-offcanvas me-3 p-0 d-lg-none"
              type="button"
              data-bs-toggle="offcanvas"
              data-bs-target="#offcanvasMenu"
              aria-controls="offcanvasMenu"
            >
                <i class="bi bi-list text-white fs-3"></i>
            </button>
            <a class="navbar-brand text-white fw-bold" href="{{ url('dashboard') }}">
                StayTuned
            </a>
        </div>

        <div class="d-none d-lg-flex ms-auto align-items-center gap-3">
            <a href="{{ route('dashboard') }}" class="nav-link-inline">Dashboard</a>
            <a href="{{ route('explore.users.index') }}" class="nav-link-inline">Explorar usuarios</a>
            <a href="{{ route('playlists.index') }}" class="nav-link-inline">Mis playlists</a>
            <a href="{{ route('posts.index') }}" class="nav-link-inline">Mis Publicaciones</a>
            <a href="{{ route('communities.index') }}" class="nav-link-inline">Mis comunidades</a>

            <div class="dropdown">
                <a
                  class="d-flex align-items-center text-white dropdown-toggle nav-link-inline"
                  href="#"
                  id="userDropdown"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                    @if(Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <img
                          src="{{ Auth::user()->profile_photo_url }}"
                          class="rounded-circle me-2 user-photo"
                          alt="{{ Auth::user()->username }}"
                        />
                    @endif
                    {{ Auth::user()->username }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a
                          class="dropdown-item d-flex align-items-center"
                          href="{{ route('profile.settings') }}"
                        >
                            <i class="bi bi-person me-2"></i> Perfil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider" /></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                              type="submit"
                              class="dropdown-item d-flex align-items-center text-danger"
                            >
                                <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Offcanvas menu (para <lg) -->
    <div
      class="offcanvas offcanvas-start"
      tabindex="-1"
      id="offcanvasMenu"
      aria-labelledby="offcanvasMenuLabel"
    >
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasMenuLabel">StayTuned</h5>
            <button
              type="button"
              class="btn-close text-reset"
              data-bs-dismiss="offcanvas"
              aria-label="Cerrar"
            ></button>
        </div>
        <div class="offcanvas-body d-flex flex-column p-0">
            <nav class="nav flex-column">
                <a class="nav-link active" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link" href="{{ route('explore.users.index') }}">Explorar usuarios</a>
                <a class="nav-link" href="{{ route('playlists.index') }}">Mis playlists</a>
                <a class="nav-link" href="{{ route('posts.index') }}">Mis Publicaciones</a>
                <a class="nav-link" href="{{ route('communities.index') }}">Mis comunidades</a>
            </nav>
            <hr class="my-0" />
            <nav class="nav flex-column">
                <a class="nav-link" href="{{ route('profile.show') }}">
                    <i class="bi bi-person me-2"></i> Perfil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                      type="submit"
                      class="nav-link btn btn-link text-start text-danger"
                    >
                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                    </button>
                </form>
            </nav>
        </div>
    </div>

    <!-- ==========================
         CABECERA TIPO “INSTAGRAM PROFILE”
         ========================== -->
    <main class="container py-5">
        <div class="profile-header text-center mb-5">
            {{-- Foto de perfil redonda grande con hover effect --}}
            @if(Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <div class="profile-photo-container" 
                     onclick="document.getElementById('profile-photo-input').click();">
                    <img
                      src="{{ Auth::user()->profile_photo_url }}"
                      class="profile-header-img"
                      alt="{{ Auth::user()->username }}"
                    />
                    <div class="profile-photo-overlay">
                        <i class="bi bi-camera-fill"></i>
                        <span>Cambiar foto</span>
                    </div>
                </div>
                
                {{-- Input oculto para cambio de foto --}}
                <input type="file" id="profile-photo-input" style="display: none;" 
                       accept="image/*" 
                       onchange="uploadProfilePhoto(this)">
            @else
                <div class="profile-header-img-original mb-3 bg-secondary"></div>
            @endif

            {{-- Nombre de usuario --}}
            <h2 class="username mb-1">{{ Auth::user()->username }}</h2>

            {{-- Estadísticas reales del usuario --}}
            <div class="profile-stats d-flex justify-content-center gap-4">
                <div><strong>{{ $stats['playlists_count'] }}</strong> playlists</div>
                <div><strong>{{ $stats['followers_count'] }}</strong> seguidores</div>
                <div><strong>{{ $stats['following_count'] }}</strong> siguiendo</div>
            </div>
        </div>

        <!-- ==========================
             FORMULARIOS DE CONFIGURACIÓN (sin tarjetas extra)
             ========================== -->

        {{-- 1) Información de Perfil --}}
        @if (Laravel\Fortify\Features::canUpdateProfileInformation())
            <div class="mb-5 profile-form-section">
                @livewire('profile.update-profile-information-form')
            </div>
        @endif
        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
            <hr class="profile-divider" />
            <div class="mb-5 profile-form-section">
                @livewire('profile.update-password-form')
            </div>
        @endif
        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
            <hr class="profile-divider" />
            <div class="mb-5 profile-form-section">
                @livewire('profile.two-factor-authentication-form')
            </div>
        @endif
        <hr class="profile-divider" />
        <div class="mb-5 profile-form-section">
            @livewire('profile.logout-other-browser-sessions-form')
        </div>
        @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
            <hr class="profile-divider" />
            <div class="mb-5 profile-form-section">
                @livewire('profile.delete-user-form')
            </div>
        @endif

    </main>

    <!-- JavaScript para el cambio de foto de perfil -->
    <script>
        function uploadProfilePhoto(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const profileContainer = document.querySelector('.profile-photo-container');
                const profileImg = document.querySelector('.profile-header-img');
                
                // Validar que es una imagen
                if (!file.type.startsWith('image/')) {
                    showErrorMessage('Por favor selecciona un archivo de imagen válido.');
                    return;
                }
                
                // Validar tamaño (máximo 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    showErrorMessage('La imagen es demasiado grande. El tamaño máximo es 5MB.');
                    return;
                }
                
                // Mostrar estado de carga
                showLoadingState(profileContainer);
                
                // Mostrar preview inmediato
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (profileImg) {
                        profileImg.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
                
                // Crear FormData para enviar al servidor
                const formData = new FormData();
                formData.append('photo', file);
                formData.append('_token', '{{ csrf_token() }}');
                
                // Enviar al endpoint de nuestro ProfileController
                fetch('/user/profile-photo', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoadingState(profileContainer);
                    
                    if (data.success) {
                        showSuccessMessage(data.message);
                        
                        // Actualizar la imagen inmediatamente con la nueva URL
                        if (data.profile_photo_url && profileImg) {
                            profileImg.src = data.profile_photo_url + '?t=' + Date.now(); // Cache busting
                        }
                        
                        // También actualizar todas las imágenes de perfil en la página
                        document.querySelectorAll('img[src*="profile-photos"]').forEach(img => {
                            if (data.profile_photo_url) {
                                img.src = data.profile_photo_url + '?t=' + Date.now();
                            }
                        });
                    } else {
                        showErrorMessage(data.message || 'Error al actualizar la foto de perfil');
                        // Revertir la imagen si hubo error
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    hideLoadingState(profileContainer);
                    showErrorMessage('Hubo un error al actualizar la foto de perfil. Por favor, inténtalo de nuevo.');
                    // Revertir la imagen si hubo error
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                });
            }
        }
        
        function showLoadingState(container) {
            const loadingOverlay = document.createElement('div');
            loadingOverlay.className = 'profile-loading-overlay';
            loadingOverlay.innerHTML = `
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <span>Subiendo...</span>
            `;
            container.appendChild(loadingOverlay);
        }
        
        function hideLoadingState(container) {
            const loadingOverlay = container.querySelector('.profile-loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.remove();
            }
        }
        
        function showSuccessMessage(message) {
            showMessage(message, 'success');
        }
        
        function showErrorMessage(message) {
            showMessage(message, 'error');
        }
        
        function showMessage(message, type) {
            // Crear elemento de mensaje
            const messageDiv = document.createElement('div');
            messageDiv.className = `alert position-fixed message-${type}`;
            
            const bgColor = type === 'success' ? 'rgba(34, 197, 94, 0.9)' : 'rgba(239, 68, 68, 0.9)';
            
            messageDiv.style.cssText = `
                top: 20px;
                right: 20px;
                z-index: 9999;
                padding: 1rem 1.5rem;
                background: ${bgColor};
                color: white;
                border: none;
                border-radius: 0.5rem;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                font-weight: 500;
                animation: slideInRight 0.3s ease-out;
                max-width: 300px;
            `;
            messageDiv.textContent = message;
            
            // Agregar al DOM
            document.body.appendChild(messageDiv);
            
            // Remover después de 4 segundos
            setTimeout(() => {
                messageDiv.style.animation = 'slideOutRight 0.3s ease-in';
                setTimeout(() => {
                    if (messageDiv.parentNode) {
                        messageDiv.parentNode.removeChild(messageDiv);
                    }
                }, 300);
            }, 4000);
        }
        
        // Estilos CSS para las animaciones y loading state
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
            
            .profile-loading-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.8);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                color: white;
                text-align: center;
                z-index: 10;
            }
            
            .profile-loading-overlay span {
                font-size: 0.9rem;
                font-weight: 500;
                margin-top: 0.5rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
        `;
        document.head.appendChild(style);
    </script>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
