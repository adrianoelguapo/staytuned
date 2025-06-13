<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Mis Playlists | StayTuned</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ asset('css/profile.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/playlists.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/mobile-responsive.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/navbar-fix.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/offcanvas-fix.css') }}?v={{ time() }}" rel="stylesheet">
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
            <a href="{{ route('explore.users.index') }}" class="nav-link-inline">Explorar Usuarios</a>
            <a href="{{ route('playlists.index') }}" class="nav-link-inline active">Mis Playlists</a>
            <a href="{{ route('posts.index') }}" class="nav-link-inline">Mis Publicaciones</a>
            <a href="{{ route('communities.index') }}" class="nav-link-inline">Mis Comunidades</a>

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
            <h5 class="offcanvas-title text-white" id="offcanvasMenuLabel">StayTuned</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column p-0">
            <nav class="nav flex-column">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
                <a class="nav-link" href="{{ route('explore.users.index') }}">
                    <i class="fas fa-users me-2"></i> Explorar Usuarios
                </a>
                <a class="nav-link active" href="{{ route('playlists.index') }}">
                    <i class="fas fa-music me-2"></i> Mis Playlists
                </a>
                <a class="nav-link" href="{{ route('posts.index') }}">
                    <i class="fas fa-newspaper me-2"></i> Mis Publicaciones
                </a>
                <a class="nav-link" href="{{ route('communities.index') }}">
                    <i class="fas fa-users me-2"></i> Mis Comunidades
                </a>
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

    <main class="dashboard-container container-fluid py-3 py-lg-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 px-2 px-lg-3">

                <!-- Header con botón de crear playlist -->
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4 gap-3">
                    <div class="header-text-section">
                        <h1 class="text-white mb-2 playlist-main-title">
                            Mis Playlists
                        </h1>
                        <p class="text-white mb-0 playlist-subtitle">Organiza y gestiona tus colecciones musicales personalizadas</p>
                    </div>
                    <a href="{{ route('playlists.create') }}" class="btn btn-new-playlist align-self-stretch align-self-lg-auto d-flex align-items-center justify-content-center">
                        <i class="bi bi-plus-circle me-2"></i>
                        <span>Nueva Playlist</span>
                    </a>
                </div>

                @if($playlists->count() > 0)
                    <div class="playlists-list">
                        @foreach($playlists as $playlist)
                            <div class="playlist-card-full-width">
                                <div class="playlist-card-body">
                                    <!-- Contenido principal -->
                                    <div class="playlist-content-wrapper">
                                        <!-- Imagen de la playlist -->
                                        <div class="playlist-cover-container">
                                            @if($playlist->cover)
                                                <img src="{{ asset('storage/' . $playlist->cover) }}"
                                                     alt="{{ $playlist->name }}"
                                                     class="playlist-cover-image">
                                            @else
                                                <div class="playlist-cover-placeholder">
                                                    <i class="bi bi-music-note-beamed"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Información de la playlist -->
                                        <div class="playlist-info-container">
                                            <div class="playlist-header-section">
                                                <div class="playlist-title-wrapper flex-grow-1">
                                                    <a href="{{ route('playlists.show', $playlist) }}" class="playlist-title-link">
                                                        <h3 class="playlist-title">{{ $playlist->name }}</h3>
                                                    </a>
                                                    <div class="playlist-badges mt-2">
                                                        <span class="playlist-privacy-badge">
                                                            <i class="bi bi-{{ $playlist->is_public ? 'globe' : 'lock' }} me-1"></i>
                                                            {{ $playlist->is_public ? 'Pública' : 'Privada' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($playlist->description)
                                                <p class="playlist-description">{{ Str::limit($playlist->description, 150) }}</p>
                                            @endif

                                            <!-- Meta información y acciones -->
                                            <div class="playlist-footer-section">
                                                <div class="playlist-meta-info">
                                                    <span class="playlist-stat">
                                                        <i class="bi bi-music-note me-1"></i>
                                                        {{ $playlist->songs->count() }} canciones
                                                    </span>
                                                    <span class="playlist-author">
                                                        <i class="bi bi-person me-1"></i>
                                                        {{ Auth::user()->username }}
                                                    </span>
                                                    <span class="playlist-date">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        <span class="d-none d-sm-inline">{{ $playlist->created_at->diffForHumans() }}</span>
                                                        <span class="d-sm-none">{{ $playlist->created_at->format('d/m/Y') }}</span>
                                                    </span>
                                                </div>

                                                <div class="playlist-actions-section">
                                                    <a href="{{ route('playlists.show', $playlist) }}" class="btn-playlist btn-playlist-primary btn-sm">
                                                        <i class="bi bi-play-fill me-1"></i>
                                                        Ver
                                                    </a>
                                                    <a href="{{ route('playlists.edit', $playlist) }}" class="btn-playlist btn-playlist-secondary btn-sm">
                                                        <i class="bi bi-pencil me-1"></i>
                                                        Editar
                                                    </a>
                                                    <form action="{{ route('playlists.destroy', $playlist) }}"
                                                          method="POST"
                                                          style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-playlist btn-playlist-danger btn-sm">
                                                            <i class="bi bi-trash me-1"></i>
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($playlists->hasPages())
                        <div class="text-center mt-5">
                            <nav aria-label="Navegación de playlists" class="d-flex justify-content-center">
                                <ul class="pagination pagination-custom">
                                    @if ($playlists->onFirstPage())
                                        <li class="page-item disabled"><span class="page-link">‹</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $playlists->previousPageUrl() }}" rel="prev">‹</a></li>
                                    @endif

                                    @foreach ($playlists->getUrlRange(1, $playlists->lastPage()) as $page => $url)
                                        @if ($page == $playlists->currentPage())
                                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                        @endif
                                    @endforeach

                                    @if ($playlists->hasMorePages())
                                        <li class="page-item"><a class="page-link" href="{{ $playlists->nextPageUrl() }}" rel="next">›</a></li>
                                    @else
                                        <li class="page-item disabled"><span class="page-link">›</span></li>
                                    @endif
                                </ul>
                            </nav>
                            <div class="pagination-info mt-3">
                                <small class="text-light">
                                    Mostrando {{ $playlists->firstItem() ?? 0 }} - {{ $playlists->lastItem() ?? 0 }}
                                    de {{ $playlists->total() }} playlists
                                </small>
                            </div>
                        </div>
                    @endif

                @else
                    <div class="card dashboard-card text-center py-5">
                        <div class="card-body">
                            <i class="bi bi-music-note-list display-1 text-light mb-3"></i>
                            <h4 class="text-light mb-3">No tienes playlists aún</h4>
                            <p class="text-muted mb-4">
                                Crea tu primera playlist y comienza a organizar tu música favorita
                            </p>
                            <a href="{{ route('playlists.create') }}" class="btn btn-new-playlist">
                                <i class="bi bi-plus-circle me-2"></i> Crear mi primera playlist
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/mobile-enhancements.js') }}?v={{ time() }}"></script>
</body>
</html>





