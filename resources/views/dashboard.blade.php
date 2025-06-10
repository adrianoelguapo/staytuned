<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | StayTuned</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/posts.css') }}" rel="stylesheet">
    <link href="{{ asset('css/community-fixed.css') }}" rel="stylesheet">
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
                <a class="nav-link active" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link" href="{{ route('explore.users.index') }}">Explorar usuarios</a>
                <a class="nav-link" href="{{ route('playlists.index') }}">Mis playlists</a>
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
                
                <!-- Header del Dashboard -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>                        <h1 class="text-white mb-1">
                            Dashboard
                        </h1>
                        <p class="text-light mb-0">Mantente al día con las publicaciones de tus seguidos y comunidades</p>
                    </div>
                </div>

                <!-- Estadísticas rápidas -->
                <div class="row mb-5">
                    <div class="col-md-4 mb-3">
                        <div class="card dashboard-card">
                            <div class="card-body text-center">
                                <i class="fas fa-user-friends fs-1 text-primary mb-2"></i>
                                <h5 class="card-title text-white">Siguiendo</h5>
                                <p class="card-text fs-4 fw-bold text-white">{{ $stats['following_count'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card dashboard-card">
                            <div class="card-body text-center">
                                <i class="fas fa-users fs-1 text-success mb-2"></i>
                                <h5 class="card-title text-white">Mis Comunidades</h5>
                                <p class="card-text fs-4 fw-bold text-white">{{ $stats['communities_count'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card dashboard-card">
                            <div class="card-body text-center">
                                <i class="fas fa-newspaper fs-1 text-info mb-2"></i>
                                <h5 class="card-title text-white">Mis Publicaciones</h5>
                                <p class="card-text fs-4 fw-bold text-white">{{ $stats['user_posts'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de Publicaciones de Seguidos -->
                <div class="mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">                        <h2 class="text-white mb-0">
                            Publicaciones de Seguidos
                        </h2>
                        <a href="{{ route('explore.users.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-search me-1"></i>
                            Explorar más usuarios
                        </a>
                    </div>

                    @if($followingPosts && $followingPosts->count() > 0)
                        <div class="posts-list">
                            @foreach($followingPosts as $post)
                                <div class="post-card-full-width mb-4">
                                    <div class="post-card-body">
                                        <!-- Contenido principal -->
                                        <div class="post-content-wrapper">
                                            <!-- Imagen/Cover de la publicación -->
                                            <div class="post-cover-container">
                                                @if($post->cover || ($post->spotify_data && isset($post->spotify_data['images']) && count($post->spotify_data['images']) > 0))
                                                    <img src="{{ $post->cover ?: $post->spotify_data['images'][0]['url'] }}" 
                                                         alt="{{ $post->title }}"
                                                         class="post-cover-image"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="post-cover-placeholder" style="display: none;">
                                                        <i class="fas fa-newspaper"></i>
                                                    </div>
                                                @else
                                                    <div class="post-cover-placeholder">
                                                        <i class="fas fa-newspaper"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Información del post -->
                                            <div class="post-info-container">                                                <div class="post-header-section">
                                                    <a href="{{ route('posts.show', $post) }}" class="post-title-link">
                                                        <h3 class="post-title">{{ $post->title }}</h3>
                                                    </a>
                                                    @if($post->category)
                                                        <span class="post-category-badge">{{ ucfirst($post->category->type) }}</span>
                                                    @endif
                                                </div>
                                                
                                                @if($post->content || $post->description)
                                                    <p class="post-description">{{ Str::limit($post->content ?: $post->description, 150) }}</p>
                                                @endif
                                                
                                                @if($post->spotify_data)
                                                    <div class="spotify-info-card">
                                                        <i class="fab fa-spotify spotify-icon"></i>
                                                        <div class="spotify-text">
                                                            <div class="spotify-track-name">{{ $post->spotify_name }}</div>
                                                            @if($post->spotify_artist)
                                                                <div class="spotify-artist-name">{{ $post->spotify_artist }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                  <!-- Meta información y acciones -->
                                                <div class="post-footer-section">
                                                    <div class="post-meta-info">
                                                        <span class="post-author">
                                                            <a href="{{ route('explore.users.show', $post->user) }}" class="d-inline-flex align-items-center text-decoration-none">
                                                                @if(Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                                                    <img src="{{ $post->user->profile_photo_url }}"
                                                                         class="rounded-circle me-2"
                                                                         alt="{{ $post->user->name }}"
                                                                         style="width: 20px; height: 20px; object-fit: cover;" />
                                                                @else
                                                                    <i class="fas fa-user me-1"></i>
                                                                @endif
                                                                <span class="text-white">{{ $post->user->username }}</span>
                                                            </a>
                                                        </span>
                                                        <span class="post-date">
                                                            <i class="fas fa-calendar me-1"></i>
                                                            {{ $post->created_at->diffForHumans() }}
                                                        </span>
                                                        <span class="post-stat">
                                                            <i class="fas fa-heart me-1"></i>
                                                            {{ $post->likes_count ?? 0 }} likes
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="post-actions-section">
                                                        <a href="{{ route('posts.show', $post) }}" class="btn-glass btn-sm">
                                                            <i class="fas fa-eye me-1"></i>
                                                            Ver
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="card dashboard-card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                                <h5 class="text-white mb-2">Sin publicaciones de seguidos</h5>
                                <p class="text-light mb-3">No sigues a ningún usuario aún o no han publicado contenido.</p>
                                <a href="{{ route('explore.users.index') }}" class="btn btn-outline-light">
                                    <i class="fas fa-search me-2"></i>
                                    Explorar usuarios
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sección de Publicaciones de Comunidades -->
                <div class="mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">                        <h2 class="text-white mb-0">
                            Publicaciones de Mis Comunidades
                        </h2>
                        <a href="{{ route('communities.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-users me-1"></i>
                            Ver mis comunidades
                        </a>
                    </div>

                    @if($communityPosts && $communityPosts->count() > 0)
                        <div class="posts-list">
                            @foreach($communityPosts as $post)
                                <div class="post-card-full-width mb-4">
                                    <div class="post-card-body">
                                        <!-- Contenido principal -->
                                        <div class="post-content-wrapper">
                                            <!-- Imagen/Cover de la publicación -->
                                            <div class="post-cover-container">
                                                @if($post->cover || ($post->spotify_data && isset($post->spotify_data['images']) && count($post->spotify_data['images']) > 0))
                                                    <img src="{{ $post->cover ?: $post->spotify_data['images'][0]['url'] }}" 
                                                         alt="{{ $post->title }}"
                                                         class="post-cover-image"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="post-cover-placeholder" style="display: none;">
                                                        <i class="fas fa-newspaper"></i>
                                                    </div>
                                                @else
                                                    <div class="post-cover-placeholder">
                                                        <i class="fas fa-newspaper"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Información del post -->
                                            <div class="post-info-container">
                                                <div class="post-header-section">
                                                    <a href="{{ route('posts.show', $post) }}" class="post-title-link">
                                                        <h3 class="post-title">{{ $post->title }}</h3>
                                                    </a>                                                    <div class="d-flex gap-2">
                                                        @if($post->category)
                                                            <span class="post-category-badge">{{ ucfirst($post->category->type) }}</span>
                                                        @endif
                                                        @if($post->community)
                                                            <span class="community-badge community-badge-public">
                                                                <i class="fas fa-users me-1"></i>
                                                                {{ $post->community->name }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                @if($post->content || $post->description)
                                                    <p class="post-description">{{ Str::limit($post->content ?: $post->description, 150) }}</p>
                                                @endif
                                                
                                                @if($post->spotify_data)
                                                    <div class="spotify-info-card">
                                                        <i class="fab fa-spotify spotify-icon"></i>
                                                        <div class="spotify-text">
                                                            <div class="spotify-track-name">{{ $post->spotify_name }}</div>
                                                            @if($post->spotify_artist)
                                                                <div class="spotify-artist-name">{{ $post->spotify_artist }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                  <!-- Meta información y acciones -->
                                                <div class="post-footer-section">
                                                    <div class="post-meta-info">
                                                        <span class="post-author">
                                                            <a href="{{ route('explore.users.show', $post->user) }}" class="d-inline-flex align-items-center text-decoration-none">
                                                                @if(Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                                                    <img src="{{ $post->user->profile_photo_url }}"
                                                                         class="rounded-circle me-2"
                                                                         alt="{{ $post->user->name }}"
                                                                         style="width: 20px; height: 20px; object-fit: cover;" />
                                                                @else
                                                                    <i class="fas fa-user me-1"></i>
                                                                @endif
                                                                <span class="text-white">{{ $post->user->username }}</span>
                                                            </a>
                                                        </span>
                                                        <span class="post-date">
                                                            <i class="fas fa-calendar me-1"></i>
                                                            {{ $post->created_at->diffForHumans() }}
                                                        </span>
                                                        <span class="post-stat">
                                                            <i class="fas fa-heart me-1"></i>
                                                            {{ $post->likes_count ?? 0 }} likes
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="post-actions-section">
                                                        <a href="{{ route('posts.show', $post) }}" class="btn-glass btn-sm">
                                                            <i class="fas fa-eye me-1"></i>
                                                            Ver
                                                        </a>
                                                        @if($post->community)
                                                            <a href="{{ route('communities.show', $post->community) }}" class="btn-glass btn-sm">
                                                                <i class="fas fa-users me-1"></i>
                                                                Comunidad
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="card dashboard-card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-white mb-2">Sin publicaciones de comunidades</h5>
                                <p class="text-light mb-3">No perteneces a ninguna comunidad aún o no hay publicaciones nuevas.</p>
                                <a href="{{ route('communities.index') }}" class="btn btn-outline-light">
                                    <i class="fas fa-users me-2"></i>
                                    Explorar comunidades
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
