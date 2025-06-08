<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | StayTuned</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
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
            <a href="{{ route('playlists.index') }}" class="nav-link-inline">Mis playlists</a>
            <a href="{{ route('posts.index') }}" class="nav-link-inline">Mis Publicaciones</a>
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
                            <!-- aquí quitamos ps-0 -->
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
                <a class="nav-link active" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link" href="{{ route('explore.users.index') }}">Explorar usuarios</a>
                <a class="nav-link" href="{{ route('playlists.index') }}">Mis playlists</a>
                <a class="nav-link" href="{{ route('posts.index') }}">Mis Publicaciones</a>
                <a class="nav-link" href="#">Mis comunidades</a>
            </nav>
            <hr class="my-0">
            <nav class="nav flex-column">
                <a class="nav-link d-flex align-items-center" href="{{ route('profile.settings') }}">
                    <i class="bi bi-person me-2"></i> Perfil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <!-- quitamos ps-0 aquí también -->
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
                <!-- Estadísticas generales -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card dashboard-card">
                            <div class="card-body text-center">
                                <i class="bi bi-file-earmark-text fs-1 text-primary mb-2"></i>
                                <h5 class="card-title">Total Publicaciones</h5>
                                <p class="card-text fs-4 fw-bold">{{ $stats['total_posts'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card dashboard-card">
                            <div class="card-body text-center">
                                <i class="bi bi-people fs-1 text-success mb-2"></i>
                                <h5 class="card-title">Total Usuarios</h5>
                                <p class="card-text fs-4 fw-bold">{{ $stats['total_users'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card dashboard-card">
                            <div class="card-body text-center">
                                <i class="bi bi-person-check fs-1 text-info mb-2"></i>
                                <h5 class="card-title">Mis Publicaciones</h5>
                                <p class="card-text fs-4 fw-bold">{{ $stats['user_posts'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Widget de publicaciones recientes -->
                <div class="card dashboard-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Publicaciones Recientes</h5>
                        <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
                    </div>
                    <div class="card-body">
                        @if($recentPosts && $recentPosts->count() > 0)
                            @foreach($recentPosts as $post)
                                <div class="d-flex align-items-start mb-3 pb-3 @if(!$loop->last) border-bottom @endif">
                                    @if($post->spotify_data && isset($post->spotify_data['images']) && count($post->spotify_data['images']) > 0)
                                        <img src="{{ $post->spotify_data['images'][0]['url'] }}" 
                                             alt="{{ $post->getSpotifyNameAttribute() }}" 
                                             class="rounded me-3" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" 
                                             style="width: 60px; height: 60px;">
                                            <i class="bi bi-music-note text-white"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                                                {{ $post->title }}
                                            </a>
                                        </h6>
                                        @if($post->spotify_data)
                                            <p class="text-muted small mb-1">
                                                <strong>{{ $post->getSpotifyNameAttribute() }}</strong>
                                                @if($post->getSpotifyArtistAttribute())
                                                    por {{ $post->getSpotifyArtistAttribute() }}
                                                @endif
                                            </p>
                                        @endif
                                        <small class="text-muted">
                                            Por {{ $post->user->username }} en {{ $post->category->name }} 
                                            · {{ $post->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    
                                    <div class="text-end">
                                        <small class="text-muted d-block">
                                            <i class="bi bi-heart"></i> {{ $post->likes_count }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-file-earmark-text fs-1 text-muted mb-3"></i>
                                <p class="text-muted">No hay publicaciones aún.</p>
                                <a href="{{ route('posts.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Crear primera publicación
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
