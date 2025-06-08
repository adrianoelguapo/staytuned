<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis Publicaciones | StayTuned</title>    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/playlists.css') }}" rel="stylesheet">
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
            <a href="{{ route('explore.users.index') }}" class="nav-link-inline">Explorar usuarios</a>
            <a href="{{ route('playlists.index') }}" class="nav-link-inline">Mis playlists</a>
            <a href="{{ route('posts.index') }}" class="nav-link-inline active">Mis Publicaciones</a>
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
                <a class="nav-link" href="{{ route('playlists.index') }}">Mis playlists</a>
                <a class="nav-link active" href="{{ route('posts.index') }}">Mis Publicaciones</a>
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
    </div>    <!-- Contenido principal -->
    <main class="dashboard-container container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                
                <!-- Header con botón de crear publicación -->
                <div class="d-flex justify-content-between align-items-center mb-4">                    <h1 class="h3 text-white mb-0">
                        Mis Publicaciones
                    </h1>
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
                                                <span class="post-category-badge">{{ $post->category->text }}</span>
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
                                                    <span class="post-author">
                                                        <i class="bi bi-person me-1"></i>
                                                        {{ $post->user->name }}
                                                    </span>
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
                                                        <svg width="16" height="16" viewBox="0 0 24 24" 
                                                             class="{{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'fill-current' : 'stroke-current fill-none' }}">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                        </svg>
                                                        <span class="likes-count">{{ $post->likes_count }}</span>
                                                    </button>
                                                    
                                                    @if($post->user_id === Auth::id())
                                                        <div class="dropdown">
                                                            <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" 
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="bi bi-three-dots"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('posts.show', $post) }}">
                                                                        <i class="bi bi-eye me-2"></i>Ver
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('posts.edit', $post) }}">
                                                                        <i class="bi bi-pencil me-2"></i>Editar
                                                                    </a>
                                                                </li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li>
                                                                    <form action="{{ route('posts.destroy', $post) }}" 
                                                                          method="POST" 
                                                                          onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta publicación?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item text-danger">
                                                                            <i class="bi bi-trash me-2"></i>Eliminar
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            </ul>
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

                    <!-- Paginación si hay muchas publicaciones -->
                    @if($posts->hasPages())
                        <div class="d-flex justify-content-center mt-5">
                            {{ $posts->links() }}
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
            const heartIcon = btn.querySelector('svg');
            
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
                    heartIcon.classList.add('fill-current');
                    heartIcon.classList.remove('stroke-current', 'fill-none');
                } else {
                    btn.classList.remove('liked');
                    heartIcon.classList.remove('fill-current');
                    heartIcon.classList.add('stroke-current', 'fill-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar el like');
            });
        }

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
