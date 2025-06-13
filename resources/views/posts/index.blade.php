<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis Publicaciones | StayTuned</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/playlists.css') }}" rel="stylesheet">
    <link href="{{ asset('css/navbar-fix.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/posts.css') }}" rel="stylesheet">
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
            <a class="navbar-brand text-white fw-bold" href="{{ url('dashboard') }}">
                StayTuned
            </a>
        </div>

        <div class="d-none d-lg-flex ms-auto align-items-center gap-3">
            <a href="{{ route('dashboard') }}" class="nav-link-inline">Dashboard</a>
            <a href="{{ route('explore.users.index') }}" class="nav-link-inline">Explorar Usuarios</a>
            <a href="{{ route('playlists.index') }}" class="nav-link-inline">Mis Playlists</a>
            <a href="{{ route('posts.index') }}" class="nav-link-inline active">Mis Publicaciones</a>
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
                <a class="nav-link" href="{{ route('playlists.index') }}">
                    <i class="fas fa-music me-2"></i> Mis playlists
                </a>
                <a class="nav-link active" href="{{ route('posts.index') }}">
                    <i class="fas fa-newspaper me-2"></i> Mis Publicaciones
                </a>
                <a class="nav-link" href="{{ route('communities.index') }}">
                    <i class="fas fa-users me-2"></i> Mis comunidades
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
    </div>    <!-- Contenido principal -->
    <main class="dashboard-container container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                
                <!-- Header con botón de crear publicación -->
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center align-items-lg-center mb-4 text-center text-lg-start">
                    <div class="w-100 w-lg-auto mb-3 mb-lg-0">
                        <h1 class="text-white mb-2" style="font-size: 2.5rem;">
                            Mis Publicaciones
                        </h1>
                        <p class="text-white mb-0">Crea y administra tus publicaciones musicales para compartir con la comunidad</p>
                    </div>
                    <a href="{{ route('posts.create') }}" class="btn btn-new-playlist">
                        <i class="bi bi-plus-circle me-2"></i>
                        Nueva Publicación
                    </a>
                </div>

                <!-- Mensaje de éxito -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                @if($posts->count() > 0)
                    <!-- Lista de publicaciones -->
                    <div class="posts-list">
                        @foreach($posts as $post)
                            <div class="post-card-full-width">
                                <div class="post-card-body">
                                    <!-- Contenido principal -->
                                    <div class="post-content-wrapper">
                                        <!-- Imagen/Cover de la publicación -->
                                        <div class="post-cover-container">
                                            @if($post->cover || $post->spotify_image)
                                                <img src="{{ $post->cover ?: $post->spotify_image }}" 
                                                     alt="{{ $post->title }}"
                                                     class="post-cover-image"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="post-cover-placeholder" style="display: none;">
                                                    <i class="bi bi-newspaper"></i>
                                                </div>
                                            @else
                                                <div class="post-cover-placeholder">
                                                    <i class="bi bi-newspaper"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Información del post -->
                                        <div class="post-info-container">
                                            <div class="post-header-section">
                                                <a href="{{ route('posts.show', $post) }}" class="post-title-link">
                                                    <h3 class="post-title">{{ $post->title }}</h3>
                                                </a>
                                                <span class="post-category-badge">{{ ucfirst($post->category->type) }}</span>
                                            </div>
                                            
                                            @if($post->content || $post->description)
                                                <p class="post-description">{{ Str::limit($post->content ?: $post->description, 150) }}</p>
                                            @endif
                                            
                                            @if($post->spotify_data)
                                                <div class="spotify-info-card">
                                                    <i class="bi bi-spotify spotify-icon"></i>
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
                                                    <a href="{{ route('explore.users.show', $post->user) }}" class="post-author d-flex align-items-center text-decoration-none">
                                                        <img src="{{ $post->user->profile_photo_url }}" 
                                                             class="rounded-circle me-2" 
                                                             style="width: 24px; height: 24px; object-fit: cover;"
                                                             alt="{{ $post->user->name }}">
                                                        <span class="text-white">{{ $post->user->username }}</span>
                                                    </a>
                                                    <span class="post-date">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        {{ $post->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                
                                                <div class="post-actions-section">
                                                    <button onclick="toggleLike({{ $post->id }})" 
                                                            class="like-btn {{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'liked' : '' }}"
                                                            data-post-id="{{ $post->id }}"
                                                            data-liked="{{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'true' : 'false' }}">
                                                        <i class="bi {{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'bi-heart-fill text-danger' : 'bi-heart text-white' }}"></i>
                                                        <span class="likes-count">{{ $post->likes_count }}</span>
                                                    </button>
                                                    
                                                    @if($post->user_id === Auth::id())
                                                        <div class="post-actions-buttons">
                                                            <a href="{{ route('posts.show', $post) }}" 
                                                               class="btn btn-glass-action" 
                                                               title="Ver publicación">
                                                                <i class="bi bi-eye me-1"></i>Ver
                                                            </a>
                                                            <a href="{{ route('posts.edit', $post) }}" 
                                                               class="btn btn-glass-action" 
                                                               title="Editar publicación">
                                                                <i class="bi bi-pencil me-1"></i>Editar
                                                            </a>
                                                            <form action="{{ route('posts.destroy', $post) }}" 
                                                                  method="POST" 
                                                                  style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="btn btn-glass-action btn-glass-danger" 
                                                                        title="Eliminar publicación">
                                                                    <i class="bi bi-trash me-1"></i>Eliminar
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Paginación con estilo glassmorphism -->
                    @if($posts->hasPages())
                        <div class="text-center mt-5">
                            <nav aria-label="Navegación de publicaciones" class="d-flex justify-content-center">
                                <ul class="pagination pagination-custom">
                                    {{-- Enlace anterior --}}
                                    @if ($posts->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                ‹
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $posts->previousPageUrl() }}" rel="prev">
                                                ‹
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Enlaces de páginas --}}
                                    @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                                        @if ($page == $posts->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Enlace siguiente --}}
                                    @if ($posts->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $posts->nextPageUrl() }}" rel="next">
                                                ›
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                ›
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                            
                            {{-- Información de paginación --}}
                            <div class="pagination-info mt-3">
                                <small class="text-light">
                                    Mostrando {{ $posts->firstItem() ?? 0 }} - {{ $posts->lastItem() ?? 0 }} 
                                    de {{ $posts->total() }} publicaciones
                                </small>
                            </div>
                        </div>
                    @endif

                @else
                    <!-- Estado vacío -->
                    <div class="card dashboard-card text-center py-5">
                        <div class="card-body">
                            <i class="bi bi-newspaper display-1 text-muted mb-3"></i>
                            <h4 class="text-muted mb-3">No tienes publicaciones aún</h4>
                            <p class="text-muted mb-4">
                                Crea tu primera publicación y comienza a compartir tu música favorita
                            </p>
                            <a href="{{ route('posts.create') }}" class="btn btn-new-playlist">
                                <i class="bi bi-plus-circle me-2"></i>
                                Crear mi primera publicación
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </main>    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Función para manejar los likes
        function toggleLike(postId) {
            const btn = document.querySelector(`[data-post-id="${postId}"]`);
            const likesCountElement = btn.querySelector('.likes-count');
            const heartIcon = btn.querySelector('i');
            
            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                likesCountElement.textContent = data.likes_count;
                
                if (data.liked) {
                    btn.classList.add('liked');
                    heartIcon.className = 'bi bi-heart-fill text-danger';
                } else {
                    btn.classList.remove('liked');
                    heartIcon.className = 'bi bi-heart text-white';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar el like');
            });
        }
    </script>
</body>
</html>





