<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Playlists | StayTuned</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/playlists.css') }}" rel="stylesheet">
</head>

<body class="dashboard-body">

    <nav class="navbar navbar-expand-lg px-5 py-3">
        <div class="d-flex align-items-center">
            <!-- Offcanvas toggle: solo <lg -->
            <button class="btn btn-link btn-offcanvas me-3 p-0 d-lg-none" type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasMenu"
                    aria-controls="offcanvasMenu">
                <i class="bi bi-list text-white fs-3"></i>
            </button>
            <a class="navbar-brand text-white fw-bold" href="{{ url('dashboard') }}">StayTuned</a>
        </div>

        <!-- Enlaces + usuario: solo ≥lg -->
        <div class="d-none d-lg-flex ms-auto align-items-center gap-3">
            <a href="{{ route('dashboard') }}" class="nav-link-inline">Dashboard</a>
            <a href="{{ route('explore.users.index') }}" class="nav-link-inline">Explorar usuarios</a>
            <a href="{{ route('playlists.index') }}" class="nav-link-inline active">Mis playlists</a>
            <a href="#" class="nav-link-inline">Mis comunidades</a>

            <div class="dropdown">
                <a class="d-flex align-items-center text-white dropdown-toggle nav-link-inline"
                   href="#"
                   id="userDropdown"
                   role="button"
                   data-bs-toggle="dropdown"
                   aria-expanded="false">
                    @if(Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <img src="{{ Auth::user()->profile_photo_url }}"
                             class="rounded-circle me-2 user-photo-small"
                             alt="{{ Auth::user()->name }}" />
                    @endif
                    {{ Auth::user()->username }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.settings') }}">
                            <i class="bi bi-person me-2"></i> Perfil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="dropdown-item d-flex align-items-center text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Offcanvas menu (para <lg) -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasMenuLabel">StayTuned</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column p-0">
            <nav class="nav flex-column">
                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link" href="{{ route('explore.users.index') }}">Explorar usuarios</a>
                <a class="nav-link active" href="{{ route('playlists.index') }}">Mis playlists</a>
                <a class="nav-link" href="#">Mis comunidades</a>
            </nav>
            <hr class="my-0">
            <nav class="nav flex-column">
                <a class="nav-link d-flex align-items-center" href="{{ route('profile.settings') }}">
                    <i class="bi bi-person me-2"></i> Perfil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="nav-link btn btn-link d-flex align-items-center text-danger rounded-0">
                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                    </button>
                </form>
            </nav>
        </div>
    </div>

    <main class="dashboard-container container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                
                <!-- Header con botón de crear playlist -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-white mb-0">
                        <i class="bi bi-music-note-list me-2"></i>
                        Mis Playlists
                    </h1>
                    <a href="{{ route('playlists.create') }}" class="btn btn-new-playlist">
                        <i class="bi bi-plus-circle me-2"></i>
                        Nueva Playlist
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                @if($playlists->count() > 0)
                    <!-- Grid de playlists -->
                    <div class="row g-4">
                        @foreach($playlists as $playlist)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="playlist-card">
                                    <!-- Imagen de la playlist -->
                                    <div class="playlist-image">
                                        @if($playlist->cover)
                                            <img src="{{ asset('storage/' . $playlist->cover) }}" 
                                                 alt="{{ $playlist->name }}"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="playlist-placeholder" style="display: none;">
                                                <i class="bi bi-music-note-beamed"></i>
                                            </div>
                                        @else
                                            <div class="playlist-placeholder">
                                                <i class="bi bi-music-note-beamed"></i>
                                            </div>
                                        @endif
                                        
                                        <!-- Overlay con botones -->
                                        <div class="playlist-overlay">
                                            <div class="playlist-actions">
                                                <a href="{{ route('playlists.show', $playlist) }}" 
                                                   class="btn btn-play">
                                                    <i class="bi bi-play-fill"></i>
                                                </a>
                                                <div class="dropdown">
                                                    <button class="btn btn-options" type="button" 
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('playlists.show', $playlist) }}">
                                                                <i class="bi bi-eye me-2"></i>Ver
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('playlists.edit', $playlist) }}">
                                                                <i class="bi bi-pencil me-2"></i>Editar
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('playlists.destroy', $playlist) }}" 
                                                                  method="POST" 
                                                                  onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta playlist?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="bi bi-trash me-2"></i>Eliminar
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Info de la playlist -->
                                    <div class="playlist-info">
                                        <h5 class="playlist-title">{{ $playlist->name }}</h5>
                                        @if($playlist->description)
                                            <p class="playlist-description">{{ Str::limit($playlist->description, 80) }}</p>
                                        @endif
                                        <div class="playlist-meta">
                                            <span class="playlist-songs">
                                                <i class="bi bi-music-note me-1"></i>
                                                {{ $playlist->songs->count() }} canciones
                                            </span>
                                            <span class="playlist-privacy">
                                                <i class="bi bi-{{ $playlist->is_public ? 'globe' : 'lock' }} me-1"></i>
                                                {{ $playlist->is_public ? 'Pública' : 'Privada' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Paginación si hay muchas playlists -->
                    @if($playlists->hasPages())
                        <div class="d-flex justify-content-center mt-5">
                            {{ $playlists->links() }}
                        </div>
                    @endif

                @else
                    <!-- Estado vacío -->
                    <div class="card dashboard-card text-center py-5">
                        <div class="card-body">
                            <i class="bi bi-music-note-list display-1 text-muted mb-3"></i>
                            <h4 class="text-muted mb-3">No tienes playlists aún</h4>
                            <p class="text-muted mb-4">
                                Crea tu primera playlist y comienza a organizar tu música favorita
                            </p>
                            <a href="{{ route('playlists.create') }}" class="btn btn-new-playlist">
                                <i class="bi bi-plus-circle me-2"></i>
                                Crear mi primera playlist
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mejorar posicionamiento de dropdowns con z-index alto
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.playlist-card .dropdown-toggle');
            
            dropdowns.forEach(function(dropdown) {
                dropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Cerrar otros dropdowns abiertos
                    document.querySelectorAll('.playlist-card .dropdown-menu.show').forEach(function(menu) {
                        if (menu !== dropdown.nextElementSibling) {
                            menu.classList.remove('show');
                        }
                    });
                    
                    // Toggle del dropdown actual
                    const menu = dropdown.nextElementSibling;
                    if (menu && menu.classList.contains('dropdown-menu')) {
                        setTimeout(function() {
                            if (menu.classList.contains('show')) {
                                menu.classList.remove('show');
                            } else {
                                menu.classList.add('show');
                                
                                // Calcular posición fija para que aparezca encima de todo
                                const rect = dropdown.getBoundingClientRect();
                                menu.style.position = 'fixed';
                                menu.style.zIndex = '99999';
                                menu.style.top = (rect.bottom + 5) + 'px';
                                menu.style.left = (rect.right - menu.offsetWidth) + 'px';
                                
                                // Asegurar que no se salga de la pantalla
                                if (menu.getBoundingClientRect().right > window.innerWidth) {
                                    menu.style.left = (window.innerWidth - menu.offsetWidth - 10) + 'px';
                                }
                                if (menu.getBoundingClientRect().left < 0) {
                                    menu.style.left = '10px';
                                }
                            }
                        }, 10);
                    }
                });
            });
            
            // Cerrar dropdowns al hacer clic fuera
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.playlist-card .dropdown')) {
                    document.querySelectorAll('.playlist-card .dropdown-menu.show').forEach(function(menu) {
                        menu.classList.remove('show');
                    });
                }
            });
        });
    </script>
</body>
</html>
