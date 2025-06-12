<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Playlists | StayTuned</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/playlists.css') }}?v={{ time() }}" rel="stylesheet">
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
            <a href="{{ route('posts.index') }}" class="nav-link-inline">Mis Publicaciones</a>
            <a href="{{ route('communities.index') }}" class="nav-link-inline">Mis comunidades</a>

            <div class="dropdown">
                <a class="d-flex align-items-center text-white dropdown-toggle nav-link-inline"
                   href="#"
                   id="userDropdown"
                   role="button"
                   data-bs-toggle="dropdown"
                   aria-expanded="false">
                    @if(Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <img src="{{ Auth::user()->profile_photo_url }}"
                             class="rounded-circle me-2 user-photo"
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
                <a class="nav-link" href="{{ route('posts.index') }}">Mis Publicaciones</a>
                <a class="nav-link" href="{{ route('communities.index') }}">Mis comunidades</a>
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
                    <div>
                        <h1 class="text-white mb-2" style="font-size: 2.5rem;">
                            Mis Playlists
                        </h1>
                        <p class="text-white mb-0">Organiza y gestiona tus colecciones musicales personalizadas</p>
                    </div>
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
                    <!-- Lista de playlists de ancho completo -->
                    <div class="playlists-list">
                        @foreach($playlists as $playlist)
                            <div class="playlist-card-full-width">
                                <!-- Contenido principal -->
                                <div class="playlist-content-wrapper">
                                    <!-- Imagen de la playlist -->
                                    <div class="playlist-cover-container">
                                        @if($playlist->cover)
                                            <img src="{{ asset('storage/' . $playlist->cover) }}" 
                                                 alt="{{ $playlist->name }}"
                                                 class="playlist-cover-image"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="playlist-cover-placeholder" style="display: none;">
                                                <i class="bi bi-music-note-beamed"></i>
                                            </div>
                                        @else
                                            <div class="playlist-cover-placeholder">
                                                <i class="bi bi-music-note-beamed"></i>
                                            </div>
                                        @endif
                                    </div>
                                        
                                        <!-- Información de la playlist -->
                                        <div class="playlist-info-container">
                                            <div class="playlist-header-section">
                                                <a href="{{ route('playlists.show', $playlist) }}" class="playlist-title-link">
                                                    <h3 class="playlist-title">{{ $playlist->name }}</h3>
                                                </a>
                                                <div class="d-flex gap-2">
                                                    <span class="playlist-privacy-badge">
                                                        <i class="bi bi-{{ $playlist->is_public ? 'globe' : 'lock' }} me-1"></i>
                                                        {{ $playlist->is_public ? 'Pública' : 'Privada' }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            @if($playlist->description)
                                                <p class="playlist-description">{{ Str::limit($playlist->description, 120) }}</p>
                                            @endif
                                            
                                            <!-- Meta información y acciones -->
                                            <div class="playlist-footer-section">
                                                <div class="playlist-meta-info">
                                                    <span class="playlist-author">
                                                        <a href="{{ route('explore.users.show', Auth::user()) }}" class="d-flex align-items-center text-decoration-none">
                                                            @if(Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                                                <img src="{{ Auth::user()->profile_photo_url }}"
                                                                     class="rounded-circle me-2"
                                                                     alt="{{ Auth::user()->name }}"
                                                                     style="width: 20px; height: 20px; object-fit: cover;" />
                                                            @else
                                                                <i class="bi bi-person-circle me-2"></i>
                                                            @endif
                                                            <span class="playlist-author-name">{{ Auth::user()->username }}</span>
                                                        </a>
                                                    </span>
                                                    <span class="playlist-date">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        {{ $playlist->created_at->diffForHumans() }}
                                                    </span>
                                                    <span class="playlist-stat">
                                                        <i class="bi bi-music-note me-1"></i>
                                                        {{ $playlist->songs->count() }} canciones
                                                    </span>
                                                </div>
                                                
                                                <div class="playlist-actions-section">
                                                    <a href="{{ route('playlists.show', $playlist) }}" class="btn-glass btn-sm">
                                                        <i class="bi bi-play-fill me-1"></i>
                                                        Ver
                                                    </a>
                                                    <div class="dropdown d-inline">
                                                        <button class="btn-glass btn-sm dropdown-toggle" type="button" 
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
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
                            <h4 class="text-light mb-3">No tienes playlists aún</h4>
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
        // Script simplificado para dropdowns de Bootstrap
        document.addEventListener('DOMContentLoaded', function() {
            // Usar eventos nativos de Bootstrap
            const dropdownElements = document.querySelectorAll('.dropdown-toggle');
            
            dropdownElements.forEach(function(element) {
                element.addEventListener('click', function(e) {
                    // Permitir que Bootstrap maneje el toggle
                    const parentDropdown = e.target.closest('.dropdown');
                    const menu = parentDropdown.querySelector('.dropdown-menu');
                    
                    if (menu) {
                        // Pequeño delay para permitir que Bootstrap procese primero
                        setTimeout(() => {
                            if (menu.classList.contains('show')) {
                                // Asegurar posicionamiento correcto cuando se abre
                                menu.style.position = 'absolute';
                                menu.style.top = '100%';
                                menu.style.right = '0';
                                menu.style.left = 'auto';
                                menu.style.zIndex = '99999';
                                menu.style.transform = 'translateY(0.25rem)';
                            }
                        }, 50);
                    }
                });
            });
            
            // Cerrar dropdowns cuando se hace clic fuera
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown')) {
                    document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                        menu.classList.remove('show');
                    });
                }
            });
        });
    </script>
</body>
</html>
