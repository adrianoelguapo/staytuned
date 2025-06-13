<!DOCTYPE html>
<html lang = "es">
    <head>

        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width = device-width, initial-scale = 1.0">
        <meta name = "csrf-token" content = "{{ csrf_token() }}">
        <meta name = "user-authenticated" content = "{{ auth()->check() ? 'true' : 'false' }}">
        <title>{{ $post->title }} | StayTuned</title>
    
        <link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel = "stylesheet">
        <link href = "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel = "stylesheet">
        <link href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel = "stylesheet">
        
        <link href = "{{ asset('css/posts.css') }}" rel = "stylesheet">
        <link href = "{{ asset('css/navbar-fix.css') }}?v = {{ time() }}" rel = "stylesheet">

    </head>

    <body class = "dashboard-body">

        <nav class = "navbar navbar-expand-lg px-5 py-3">

            <div class = "d-flex align-items-center">

                <button class = "btn btn-link btn-offcanvas me-3 p-0 d-lg-none" type = "button" data-bs-toggle = "offcanvas" data-bs-target = "#offcanvasMenu" aria-controls = "offcanvasMenu">
                    
                    <i class = "bi bi-list text-white fs-3"></i>
                
                </button>

                <a class = "navbar-brand text-white fw-bold" href = "{{ route('dashboard') }}">StayTuned</a>

            </div>

            <div class = "d-none d-lg-flex ms-auto align-items-center gap-3">
                <a href = "{{ route('dashboard') }}"  class = "nav-link-inline {{ request()->routeIs('dashboard') ? 'active' : '' }}">

                    Dashboard

                </a>

                <a href = "{{ route('explore.users.index') }}" class = "nav-link-inline {{ request()->routeIs('explore.users.*') ? 'active' : '' }}">

                    Explorar Usuarios

                </a>

                <a href = "{{ route('playlists.index') }}" class = "nav-link-inline {{ request()->routeIs('playlists.*') ? 'active' : '' }}">

                    Mis playlists

                </a>

                <a href = "{{ route('posts.index') }}" class = "nav-link-inline {{ request()->routeIs('posts.*') ? 'active' : '' }}">

                    Mis Publicaciones

                </a>

                <a href = "{{ route('communities.index') }}" class = "nav-link-inline">

                    Mis comunidades

                </a>

                <div class = "dropdown">
                    <a class = "d-flex align-items-center text-white dropdown-toggle nav-link-inline" href = "#" id = "userDropdown" role = "button" data-bs-toggle = "dropdown" aria-expanded = "false">

                        @if(Laravel\Jetstream\Jetstream::managesProfilePhotos())

                            <img src = "{{ Auth::user()->profile_photo_url }}" class = "rounded-circle me-2 user-photo" alt = "{{ Auth::user()->name }}"/>

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

                            <hr class = "dropdown-divider">

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

                    <a class = "nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href = "{{ route('dashboard') }}">

                        <i class = "fas fa-home me-2"></i> Dashboard

                    </a>

                    <a class = "nav-link {{ request()->routeIs('explore.users.*') ? 'active' : '' }}" href = "{{ route('explore.users.index') }}">

                        <i class = "fas fa-users me-2"></i> Explorar Usuarios

                    </a>

                    <a class = "nav-link {{ request()->routeIs('playlists.*') ? 'active' : '' }}" href = "{{ route('playlists.index') }}">

                        <i class = "fas fa-music me-2"></i> Mis playlists

                    </a>

                    <a class = "nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}" href = "{{ route('posts.index') }}">

                        <i class = "fas fa-newspaper me-2"></i> Mis Publicaciones

                    </a>

                    <a class = "nav-link" href = "{{ route('communities.index') }}">

                        <i class = "fas fa-users me-2"></i> Mis comunidades

                    </a>

                </nav>

                <hr class = "my-0 border-secondary">

                <nav class = "nav flex-column">

                    <a class = "nav-link d-flex align-items-center" href = "{{ route('profile.settings') }}">

                        <i class = "bi bi-person me-2"></i> Perfil

                    </a>


                    <form method = "POST" action = "{{ route('logout') }}">

                        @csrf

                        <button type = "submit" class = "nav-link btn btn-link d-flex align-items-center text-danger rounded-0">

                            <i class = "bi bi-box-arrow-right me-2"></i> Cerrar sesión

                        </button>

                    </form>

                </nav>

            </div>

        </div>

        <main class = "dashboard-container">

            <div class = "container-fluid">

                <div class = "row justify-content-center">

                    <div class = "col-12 col-lg-10">

                        @if(session('success'))

                            <div class = "alert alert-success mb-4">

                                {{ session('success') }}

                            </div>

                        @endif

                        <div class = "card dashboard-card mb-4">
                            
                            <div class = "card-body">

                                <div class = "d-flex justify-content-between align-items-start mb-4 pb-4 border-bottom border-light border-opacity-25 flex-column flex-md-row gap-3">

                                    <div class = "d-flex align-items-center flex-grow-1 min-width-0">

                                        <div class = "d-flex align-items-center">

                                            <a href = "{{ route('explore.users.show', $post->user) }}" class = "text-decoration-none">

                                                @if($post->user->profile_photo_url)

                                                    <img src = "{{ $post->user->profile_photo_url }}" alt = "{{ $post->user->username }}" class = "rounded-circle me-3 post-author-photo" style = "width: 48px; height: 48px; object-fit: cover;">
                                               
                                                @else

                                                    <div class = "bg-light bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3 post-author-photo" style = "width: 48px; height: 48px;">

                                                        <span class = "text-white fw-medium">{{ substr($post->user->username, 0, 1) }}</span>

                                                    </div>

                                                @endif

                                            </a>

                                            <div class = "overflow-hidden">

                                                <a href = "{{ route('explore.users.show', $post->user) }}" class = "text-decoration-none">

                                                    <h5 class = "text-white fw-semibold mb-1 post-author-name text-truncate">{{ $post->user->username }}</h5>

                                                </a>

                                                <p class = "text-white-50 mb-0 small">{{ $post->created_at->diffForHumans() }}</p>

                                            </div>

                                        </div>

                                    </div>
                                    
                                    <div class = "d-flex align-items-center gap-2 flex-shrink-0">

                                        <div class = "d-flex align-items-center gap-2 flex-wrap">

                                            @can('update', $post)

                                                <a href = "{{ route('posts.edit', $post) }}" class = "btn btn-glass-action btn-sm">
                                                    <i class = "fas fa-edit"></i>
                                                    <span class = "d-none d-sm-inline ms-1">Editar</span>
                                                </a>

                                                <form method = "POST" action = "{{ route('posts.destroy', $post) }}" class = "d-inline">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type = "submit" class = "btn btn-glass-action btn-glass-danger btn-sm">
                                                        <i class = "fas fa-trash"></i>
                                                        <span class = "d-none d-sm-inline ms-1">Eliminar</span>
                                                    </button>

                                                </form>

                                            @endcan
                                            
                                            <span class = "badge post-category-badge">

                                                {{ ucfirst($post->category->type) }}

                                            </span>

                                        </div>

                                    </div>

                                </div>

                                <h1 class = "text-white fw-bold mb-4">{{ $post->title }}</h1>

                                @if($post->content || $post->description)

                                    <div class = "mb-4">

                                        <p class = "text-white-75 lh-lg">{{ $post->content ?: $post->description }}</p>

                                    </div>

                                @endif

                                @if($post->spotify_data)

                                    <div class = "spotify-content mb-4">

                                        <div class = "d-flex align-items-center gap-4">

                                            @if($post->spotify_image)

                                                <img src = "{{ $post->spotify_image }}" alt = "{{ $post->spotify_name }}" class = "spotify-image rounded-3 flex-shrink-0">

                                            @endif
                                            
                                            <div class = "flex-grow-1 min-w-0">

                                                <div class = "d-flex align-items-center gap-2 mb-2">

                                                    <i class = "fab fa-spotify text-success fs-5"></i>

                                                    <span class = "spotify-type">

                                                        {{ ucfirst($post->spotify_type) }} de Spotify

                                                    </span>

                                                </div>
                                                
                                                <h4 class = "spotify-title">

                                                    {{ $post->spotify_name }}

                                                </h4>
                                                
                                                @if($post->spotify_artist)

                                                    <p class = "spotify-artist mb-3">

                                                        {{ $post->spotify_artist }}

                                                    </p>

                                                @endif
                                                
                                                @if($post->spotify_external_url)

                                                    <a href = "{{ $post->spotify_external_url }}" target = "_blank" class = "btn btn-success btn-sm d-inline-flex align-items-center">

                                                        <i class = "fab fa-spotify me-2"></i>

                                                        Abrir en Spotify

                                                        <i class = "fas fa-external-link-alt ms-2 small"></i>

                                                    </a>

                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                @elseif($post->cover)

                                    <div class = "mb-4 text-center">

                                        <img src = "{{ $post->cover }}" alt = "{{ $post->title }}" class = "img-fluid rounded-3 shadow-lg" style = "max-width: 600px;">
                                    
                                    </div>

                                @endif

                                <div class = "d-flex justify-content-between align-items-center pt-4 mt-4 border-top border-light border-opacity-25">

                                    <div class = "d-flex align-items-center gap-4">
                                        
                                        <button onclick = "toggleLike({{ $post->id }})" class = "btn like-btn p-2 d-flex align-items-center gap-2" data-post-id = "{{ $post->id }}" data-liked = "{{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'true' : 'false' }}">
                                            
                                            <i class = "bi {{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'bi-heart-fill text-danger' : 'bi-heart text-white' }}"></i>
                                            <span class = "likes-count">{{ $post->likes_count }}</span> likes

                                        </button>
                                        
                                        <div class = "d-flex align-items-center gap-2 text-white-50">

                                            <i class = "fas fa-comment"></i>
                                            <span class = "comments-count-actions">{{ $post->comments->count() }} comentarios</span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class = "card dashboard-card">

                            <div class = "card-body">

                                <div class = "d-flex justify-content-between align-items-center mb-4">

                                    <h3 class = "text-white fw-semibold mb-0">

                                        <span class = "comments-count-header">Comentarios ({{ $post->comments->count() }})</span>

                                    </h3>

                                </div>
                                
                                @auth

                                    <div class = "form-container mb-4">

                                        <form id = "comment-form">

                                            @csrf

                                            <div class = "mb-3">

                                                <label for = "comment-text" class = "form-label">Agregar comentario</label>

                                                <textarea id = "comment-text" name = "text" rows = "3" class = "form-control" placeholder = "Escribe tu comentario..."></textarea>

                                            </div>

                                            <div class = "d-flex justify-content-end">

                                                <button type = "submit" class = "btn btn-primary">
                                                    
                                                    <i class = "fas fa-comment me-2"></i>
                                                    Comentar

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                @endauth

                                <div id = "comments-list">

                                    @forelse($post->comments as $comment)

                                        <div class = "comment-item border-bottom border-light border-opacity-25 py-3" data-comment-id = "{{ $comment->id }}">

                                            <div class = "d-flex gap-3">

                                                <div class = "flex-shrink-0">

                                                    <a href = "{{ route('explore.users.show', $comment->user) }}" class = "text-decoration-none">

                                                        @if($comment->user->profile_photo_path)

                                                            <img class = "rounded-circle comment-author-photo" src = "{{ $comment->user->profile_photo_url }}" alt = "{{ $comment->user->username }}" style = "width: 32px; height: 32px; object-fit: cover;">
                                                       
                                                        @else
                                                            <div class = "bg-light bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center comment-author-photo" style = "width: 32px; height: 32px;">

                                                                <span class = "text-white small fw-medium">

                                                                    {{ substr($comment->user->username, 0, 1) }}

                                                                </span>

                                                            </div>

                                                        @endif

                                                    </a>

                                                </div>

                                                <div class = "flex-grow-1 min-w-0">

                                                    <div class = "d-flex align-items-center gap-2 mb-1">

                                                        <a href = "{{ route('explore.users.show', $comment->user) }}" class = "text-decoration-none">

                                                            <span class = "text-white fw-medium small comment-author-name">{{ $comment->user->username }}</span>

                                                        </a>

                                                        <span class = "text-white-50 small">

                                                            {{ $comment->created_at->diffForHumans() }}

                                                        </span>

                                                    </div>

                                                    <div class = "mb-2">

                                                        <p class = "comment-text text-white-75 small mb-0">

                                                            {{ $comment->text }}

                                                        </p>
                                                        
                                                    </div>
                                                    
                                                    @auth

                                                        @if($comment->user_id  ===  auth()->id())

                                                            <div class = "d-flex gap-2">

                                                                <button onclick = "editComment({{ $comment->id }})" class = "btn btn-link btn-sm text-white-50 p-0">

                                                                    Editar

                                                                </button>

                                                                <button onclick = "deleteComment({{ $comment->id }})" class = "btn btn-link btn-sm text-danger p-0">

                                                                    Eliminar

                                                                </button>

                                                            </div>

                                                        @endif

                                                    @endauth

                                                </div>

                                            </div>

                                        </div>

                                    @empty

                                        <div class = "text-center py-5">

                                            <div class = "empty-state-icon mb-3">

                                                <i class = "fas fa-comments text-white-50 fs-1"></i>

                                            </div>

                                            <h4 class = "text-white mb-2">No hay comentarios aún</h4>

                                            <p class = "text-white-50 mb-0">

                                                @guest

                                                    <a href = "{{ route('login') }}" class = "text-decoration-none">Inicia sesión</a> 
                                                    para ser el primero en comentar.

                                                @else

                                                    Sé el primero en comentar esta publicación.

                                                @endguest

                                            </p>

                                        </div>

                                    @endforelse

                                </div>

                            </div>

                        </div>

                        <div class = "mt-4 mb-4">

                            <a href = "{{ route('posts.index') }}" class = "btn btn-secondary">

                                <i class = "fas fa-arrow-left me-2"></i>
                                Volver a publicaciones

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </main>

        <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src = "{{ asset('js/posts-show.js') }}"></script>

    </body>

</html>