<!DOCTYPE html>
<html lang = "es">
    <head>

        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width = device-width, initial-scale = 1.0">
        <meta name = "csrf-token" content = "{{ csrf_token() }}">
        <title>Nueva Publicación | StayTuned</title>

        <link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel = "stylesheet">
        <link href = "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel = "stylesheet">

        <link href = "{{ asset('css/dashboard.css') }}" rel = "stylesheet">
        <link href = "{{ asset('css/playlists.css') }}" rel = "stylesheet">
        <link href = "{{ asset('css/navbar-fix.css') }}?v = {{ time() }}" rel = "stylesheet"><link href = "{{ asset('css/posts.css') }}" rel = "stylesheet">

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
                <a href = "{{ route('posts.index') }}" class = "nav-link-inline active">Mis Publicaciones</a>
                <a href = "{{ route('communities.index') }}" class = "nav-link-inline">Mis Comunidades</a>

                <div class = "dropdown">
                    <a class = "d-flex align-items-center text-white dropdown-toggle nav-link-inline" href = "#" id = "userDropdown" role = "button" data-bs-toggle = "dropdown" aria-expanded = "false">

                        @if(Laravel\Jetstream\Jetstream::managesProfilePhotos())

                            <img src = "{{ Auth::user()->profile_photo_url }}" class = "rounded-circle me-2 user-photo" alt = "{{ Auth::user()->name }}" />

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

        <div class = "offcanvas offcanvas-start" tabindex = "-1" id = "offcanvasMenu">

            <div class = "offcanvas-header">

                <h5 class = "offcanvas-title text-white">StayTuned</h5>
                <button type = "button" class = "btn-close btn-close-white" data-bs-dismiss = "offcanvas"></button>

            </div>

            <div class = "offcanvas-body p-0">

                <nav class = "nav flex-column">

                    <a class = "nav-link" href = "{{ route('dashboard') }}">Dashboard</a>
                    <a class = "nav-link" href = "{{ route('explore.users.index') }}">Explorar Usuarios</a>
                    <a class = "nav-link" href = "{{ route('playlists.index') }}">Mis Playlists</a>
                    <a class = "nav-link active" href = "{{ route('posts.index') }}">Mis Publicaciones</a>
                    <a class = "nav-link" href = "{{ route('communities.index') }}">Mis Comunidades</a>

                </nav>

                <hr class = "my-0">

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

        <div class = "dashboard-container container-fluid py-5">

            <div class = "row justify-content-center">

                <div class = "col-12 col-lg-8">
                
                    <nav aria-label = "breadcrumb" class = "mb-4">

                        <ol class = "breadcrumb">

                            <li class = "breadcrumb-item">

                                <a href = "{{ route('posts.index') }}" class = "text-white-50">

                                    <i class = "bi bi-chat-dots me-1"></i>
                                    Mis Publicaciones

                                </a>

                            </li>

                            <li class = "breadcrumb-item active text-white" aria-current = "page">Nueva Publicación</li>

                        </ol>

                    </nav>

                    <div class = "card create-playlist-card">

                        <div class = "card-body">

                            <div class = "d-flex align-items-center mb-4">

                                <i class = "bi bi-plus-circle text-white me-3 fs-3"></i>
                                <h1 class = "h3 mb-0 create-playlist-title">Crear Nueva Publicación</h1>

                            </div>

                            @if ($errors->any())

                                <div class = "alert alert-danger">

                                    <ul class = "mb-0">

                                        @foreach ($errors->all() as $error)

                                            <li>{{ $error }}</li>

                                        @endforeach

                                    </ul>

                                </div>  

                            @endif

                            <form action = "{{ route('posts.store') }}" method = "POST" class = "playlist-form">

                                @csrf
                                
                                <div class = "row">

                                    <div class = "col-md-8 mb-4">

                                        <div class = "mb-4">

                                            <label for = "title" class = "form-label">Título de la publicación</label>

                                            <input type = "text"  class = "form-control @error('title') is-invalid @enderror" id = "title" name = "title" value = "{{ old('title') }}" placeholder = "¿Qué quieres compartir?" required>

                                            @error('title')

                                                <div class = "invalid-feedback">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class = "mb-4">

                                            <label for = "category_id" class = "form-label">Tipo de Publicación</label>
                                            
                                            <button type = "button" id = "categorySelector" class = "category-selector-btn">

                                                <span id = "categorySelectedText">Selecciona el tipo de contenido</span>
                                                <i class = "bi bi-chevron-down category-selector-arrow"></i>

                                            </button>
                                            
                                            <input type = "hidden" id = "category_id" name = "category_id" value = "{{ old('category_id') }}" required>
                                            
                                            @error('category_id')

                                                <div class = "invalid-feedback d-block">{{ $message }}</div>

                                            @enderror

                                        </div>

                                        <div class = "mb-4">

                                            <label for = "category_content" class = "form-label">Contenido de la Publicación</label>
                                            
                                            <div class = "form-control" id = "category_content" style = "background-color: #f8f9fa; min-height: 80px; padding: 12px; border-radius: 6px;">
                                                
                                                <em class = "text-muted">Selecciona un tipo de publicación para ver el contenido...</em>
                                            
                                            </div>
                                            
                                            <small class = "text-muted">Este contenido se generará automáticamente según el tipo de publicación seleccionado.</small>
                                        
                                        </div>

                                    </div>  

                                    <div class = "col-md-4 mb-4">

                                        <div id = "spotify-section" style = "display: none;">

                                            <label class = "form-label">

                                                Contenido de Spotify <span class = "text-danger">*</span>

                                            </label>

                                            <div class = "spotify-search-container">

                                                <div class = "spotify-preview" id = "spotifyPreview">

                                                    <div class = "spotify-placeholder">

                                                        <i class = "bi bi-spotify fs-1"></i>
                                                        <p class = "mt-2 mb-0" id = "spotify-search-text">Buscar en Spotify</p>

                                                    </div>

                                                </div>
                                                
                                                <div class = "mt-3">

                                                    <div class = "input-group mb-3">

                                                        <input type = "text" class = "form-control" id = "spotifySearch" placeholder = "Buscar...">

                                                        <button class = "btn btn-primary" type = "button" onclick = "searchSpotify()">

                                                            <i class = "bi bi-search"></i>

                                                        </button>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                        
                                        <div id = "no-spotify-section" class = "text-center p-4">

                                            <i class = "bi bi-info-circle fs-1 text-muted"></i>
                                            <p class = "mt-2 mb-0 text-muted">Selecciona un tipo de publicación que requiera contenido de Spotify</p>

                                        </div>

                                    </div>

                                </div> 

                                <div id = "spotifyResults" class = "spotify-results mt-3" style = "display: none;"></div>
                                
                                <div id = "selectedSpotify" style = "display: none;" class = "mt-3">

                                    <div id = "selectedSpotifyContent" class = "spotify-item selected"></div>

                                    <button type = "button" class = "btn btn-secondary btn-sm mt-2 w-100" onclick = "clearSelection()">

                                        <i class = "bi bi-x"></i> Quitar selección

                                    </button>

                                </div>

                                <input type = "hidden" name = "spotify_id" id = "spotify_id">
                                <input type = "hidden" name = "spotify_type" id = "spotify_type">
                                <input type = "hidden" name = "spotify_external_url" id = "spotify_external_url">
                                <input type = "hidden" name = "spotify_data" id = "spotify_data">

                                <div class = "d-flex gap-3 mt-4">

                                    <a href = "{{ route('posts.index') }}" class = "btn btn-secondary flex-fill">

                                        <i class = "bi bi-arrow-left me-2"></i>
                                        Cancelar

                                    </a>

                                    <button type = "submit" class = "btn btn-primary-playlist flex-fill text-align-center" id = "submitBtn">

                                        <i class = "bi bi-plus-lg me-2"></i>
                                        Crear Publicación

                                    </button>

                                </div>

                                <div id = "validation-message" class = "alert alert-warning mt-3" style = "display: none;">

                                    <i class = "bi bi-exclamation-triangle me-2"></i>
                                    <span id = "validation-text">Por favor, selecciona contenido de Spotify para continuar.</span>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div id = "categoryModal" class = "category-modal">

            <div class = "category-modal-content">

                <div class = "category-modal-header">

                    <h5 class = "category-modal-title">Selecciona el tipo de publicación</h5>

                    <button type = "button" class = "category-modal-close" id = "closeCategoryModal">

                        <i class = "bi bi-x"></i>

                    </button>

                </div>

                <div class = "category-options">

                    @foreach($categories as $category)

                        <div class = "category-option" data-value = "{{ $category->id }}" data-type = "{{ $category->type }}" data-text = "{{ $category->text }}">

                            <div>

                                <div class = "fw-semibold">{{ ucfirst($category->type) }}</div>
                                <small class = "text-muted">{{ $category->text }}</small>

                            </div>

                            <div class = "category-option-check">

                                <i class = "bi bi-check" style = "display: none;"></i>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

        <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src = "{{ asset('js/posts-create.js') }}"></script>

    </body>

</html>