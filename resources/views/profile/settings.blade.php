<!DOCTYPE html>
<html lang = "es">

    <head>

        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width = device-width, initial-scale = 1.0"/>
        <title>Configuración de Perfil | StayTuned</title>

        <link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel = "stylesheet"/>
        <link href = "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel = "stylesheet"/>
        <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <link href = "{{ asset('css/profile.css') }}" rel = "stylesheet" />
        <link href = "{{ asset('css/navbar-fix.css') }}?v = {{ time() }}" rel = "stylesheet">
        <link href = "{{ asset('css/livewire-modal-fix.css') }}?v = {{ time() }}" rel = "stylesheet">

    </head>

    <body class = "dashboard-body">

        <nav class = "navbar navbar-expand-lg px-5 py-3">

            <div class = "d-flex align-items-center">

                <button class = "btn btn-link btn-offcanvas me-3 p-0 d-lg-none" type = "button" data-bs-toggle = "offcanvas" data-bs-target = "#offcanvasMenu" aria-controls = "offcanvasMenu">

                    <i class = "bi bi-list text-white fs-3"></i>

                </button>

                <a class = "navbar-brand text-white fw-bold" href = "{{ url('dashboard') }}">StayTuned</a>
            </div>

            <div class = "d-none d-lg-flex ms-auto align-items-center gap-3">

                <a href = "{{ route('dashboard') }}" class = "nav-link-inline">Dashboard</a>
                <a href = "{{ route('explore.users.index') }}" class = "nav-link-inline">Explorar Usuarios</a>
                <a href = "{{ route('playlists.index') }}" class = "nav-link-inline">Mis Playlists</a>
                <a href = "{{ route('posts.index') }}" class = "nav-link-inline">Mis Publicaciones</a>
                <a href = "{{ route('communities.index') }}" class = "nav-link-inline">Mis Comunidades</a>

                <div class = "dropdown">

                    <a class = "d-flex align-items-center text-white dropdown-toggle nav-link-inline" href = "#" id = "userDropdown" role = "button" data-bs-toggle = "dropdown" aria-expanded = "false">

                        @if(Laravel\Jetstream\Jetstream::managesProfilePhotos())

                            <img src = "{{ Auth::user()->profile_photo_url }}" class = "rounded-circle me-2 user-photo" alt = "{{ Auth::user()->username }}"/>

                        @endif

                        {{ Auth::user()->username }}

                    </a>

                    <ul class = "dropdown-menu dropdown-menu-end">

                        <li>

                            <a class = "dropdown-item d-flex align-items-center" href = "{{ route('profile.settings') }}">

                                <i class = "bi bi-person me-2"></i> Perfil

                            </a>

                        </li>

                        <li>

                            <hr class = "dropdown-divider"/>

                        </li>

                        <li>

                            <form method = "POST" action = "{{ route('logout') }}">
                                @csrf
                                <button type = "submit" class = "dropdown-item d-flex align-items-center text-danger">

                                    <i class = "bi bi-box-arrow-right me-2"></i> Cerrar sesión

                                </button>

                            </form>

                        </li>

                    </ul>

                </div>

            </div>

        </nav>

        <div class = "offcanvas offcanvas-start" tabindex = "-1" id = "offcanvasMenu" aria-labelledby = "offcanvasMenuLabel">

            <div class = "offcanvas-header">

                <h5 class = "offcanvas-title text-white" id = "offcanvasMenuLabel">StayTuned</h5>

                <button type = "button" class = "btn-close btn-close-white" data-bs-dismiss = "offcanvas" aria-label = "Cerrar"></button>

            </div>

            <div class = "offcanvas-body d-flex flex-column p-0">

                <nav class = "nav flex-column">

                    <a class = "nav-link active" href = "{{ route('dashboard') }}">

                        <i class = "fas fa-home me-2"></i> Dashboard

                    </a>

                    <a class = "nav-link" href = "{{ route('explore.users.index') }}">

                        <i class = "fas fa-users me-2"></i> Explorar Usuarios

                    </a>

                    <a class = "nav-link" href = "{{ route('playlists.index') }}">

                        <i class = "fas fa-music me-2"></i> Mis Playlists

                    </a>


                    <a class = "nav-link" href = "{{ route('posts.index') }}">

                        <i class = "fas fa-newspaper me-2"></i> Mis Publicaciones

                    </a>

                    <a class = "nav-link" href = "{{ route('communities.index') }}">

                        <i class = "fas fa-users me-2"></i> Mis Comunidades

                    </a>

                </nav>

                <hr class = "my-0"/>

                <nav class = "nav flex-column">

                    <a class = "nav-link" href = "{{ route('profile.show') }}">

                        <i class = "bi bi-person me-2"></i> Perfil

                    </a>

                    <form method = "POST" action = "{{ route('logout') }}">

                        @csrf
                        <button type = "submit" class = "nav-link btn btn-link d-flex align-items-center text-start text-danger">

                            <i class = "bi bi-box-arrow-right me-2"></i> Cerrar sesión

                        </button>

                    </form>

                </nav>

            </div>

        </div>

        <main class = "container py-5">

            <div class = "profile-header text-center mb-5">

                @if(Laravel\Jetstream\Jetstream::managesProfilePhotos())

                    <div class = "profile-photo-container" onclick = "document.getElementById('profile-photo-input').click();">

                        <img src = "{{ Auth::user()->profile_photo_url }}" class = "profile-header-img" alt = "{{ Auth::user()->username }}"/>

                        <div class = "profile-photo-overlay">

                            <i class = "bi bi-camera-fill"></i>

                            <span>Cambiar foto</span>

                        </div>

                    </div>

                    <input type = "file" id = "profile-photo-input" style = "display: none;" accept = "image/*" onchange = "uploadProfilePhoto(this)">

                @else

                    <div class = "profile-header-img-original mb-3 bg-secondary"></div>

                @endif

                <h2 class = "username mb-1">{{ Auth::user()->username }}</h2>

                <div class = "profile-stats d-flex justify-content-center gap-4">

                    <div><strong>{{ $stats['playlists_count'] }}</strong> playlists</div>
                    <div><strong>{{ $stats['followers_count'] }}</strong> seguidores</div>
                    <div><strong>{{ $stats['following_count'] }}</strong> siguiendo</div>

                </div>

            </div>

            @if (Laravel\Fortify\Features::canUpdateProfileInformation())

                <div class = "mb-5 profile-form-section">

                    @livewire('profile.update-profile-information-form')

                </div>

            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))

                <hr class = "profile-divider"/>

                <div class = "mb-5 profile-form-section">

                    @livewire('profile.update-password-form')

                </div>

            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())

                <hr class = "profile-divider"/>

                <div class = "mb-5 profile-form-section">

                    @livewire('profile.two-factor-authentication-form')

                </div>

            @endif

            <hr class = "profile-divider"/>

            <div class = "mb-5 profile-form-section">

                @livewire('profile.logout-other-browser-sessions-form')

            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())

                <hr class = "profile-divider"/>

                <div class = "mb-5 profile-form-section">

                    @livewire('profile.delete-user-form')

                </div>

            @endif

        </main>

        <script>

            const csrfToken  =  '{{ csrf_token() }}';
            
            // Crear meta tag para CSRF token si no existe
            if (!document.querySelector('meta[name = "csrf-token"]')) {
                const meta  =  document.createElement('meta');
                meta.name  =  'csrf-token';
                meta.content  =  csrfToken;
                document.head.appendChild(meta);
            }

        </script>

        <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Modal Fix System -->
        <script src = "{{ asset('js/modal-fix.js') }}?v = {{ time() }}"></script>
        
        <!-- Profile Settings System -->
        <script src = "{{ asset('js/profile-settings.js') }}?v = {{ time() }}"></script>

    </body>

</html>