<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editar: {{ $post->title }} | StayTuned</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- CSS personalizados -->
    <link href="{{ asset('css/posts.css') }}" rel="stylesheet">
</head>

<body class="dashboard-body">
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg px-5 py-3">
        <div class="d-flex align-items-center">
            <!-- Offcanvas toggle: solo <lg -->
            <button class="btn btn-link btn-offcanvas me-3 p-0 d-lg-none" type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasMenu"
                    aria-controls="offcanvasMenu">
                <i class="bi bi-list text-white fs-3"></i>
            </button>
            <a class="navbar-brand text-white fw-bold" href="{{ route('dashboard') }}">StayTuned</a>
        </div>

        <!-- Enlaces + usuario: solo ≥lg -->
        <div class="d-none d-lg-flex ms-auto align-items-center gap-3">
            <a href="{{ route('dashboard') }}" 
               class="nav-link-inline {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('explore.users.index') }}" 
               class="nav-link-inline {{ request()->routeIs('explore.users.*') ? 'active' : '' }}">
                Explorar usuarios
            </a>
            <a href="{{ route('playlists.index') }}" 
               class="nav-link-inline {{ request()->routeIs('playlists.*') ? 'active' : '' }}">
                Mis playlists
            </a>
            <a href="{{ route('posts.index') }}" 
               class="nav-link-inline {{ request()->routeIs('posts.*') ? 'active' : '' }}">
                Publicaciones
            </a>

            <!-- Usuario -->
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle d-flex align-items-center p-0" 
                        type="button" id="userDropdown" data-bs-toggle="dropdown">
                    <img src="{{ Auth::user()->profile_photo_url }}" 
                         alt="{{ Auth::user()->name }}" 
                         class="user-photo rounded-circle me-2">
                    <span class="text-white">{{ Auth::user()->name }}</span>
                </button>
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
                            <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
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
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                   href="{{ route('dashboard') }}">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('explore.users.*') ? 'active' : '' }}" 
                   href="{{ route('explore.users.index') }}">
                    <i class="fas fa-users me-2"></i> Explorar usuarios
                </a>
                <a class="nav-link {{ request()->routeIs('playlists.*') ? 'active' : '' }}" 
                   href="{{ route('playlists.index') }}">
                    <i class="fas fa-music me-2"></i> Mis playlists
                </a>
                <a class="nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}" 
                   href="{{ route('posts.index') }}">
                    <i class="fas fa-newspaper me-2"></i> Publicaciones
                </a>
            </nav>
            <hr class="my-0 border-secondary">
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

    <!-- Contenido principal -->
    <main class="dashboard-container">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <!-- Header -->
                    <div class="d-flex align-items-center mb-4">
                        <a href="{{ route('posts.show', $post) }}" 
                           class="btn btn-link text-white p-0 me-3">
                            <i class="fas fa-arrow-left fs-5"></i>
                        </a>
                        <h1 class="text-white fw-bold mb-0">Editar Publicación</h1>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Card principal del formulario -->
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('posts.update', $post) }}">
                                @csrf
                                @method('PUT')
                                
                                <!-- Título -->
                                <div class="form-container mb-4">
                                    <label for="title" class="form-label">Título</label>
                                    <input type="text" 
                                           name="title" 
                                           id="title" 
                                           value="{{ old('title', $post->title) }}" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Descripción -->
                                <div class="form-container mb-4">
                                    <label for="description" class="form-label">Descripción</label>
                                    <textarea name="description" 
                                              id="description" 
                                              rows="4" 
                                              class="form-control @error('description') is-invalid @enderror"
                                              placeholder="Escribe una descripción de tu publicación...">{{ old('description', $post->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Categoría -->
                                <div class="form-container mb-4">
                                    <label for="category_id" class="form-label">Categoría</label>
                                    <select name="category_id" 
                                            id="category_id" 
                                            class="form-select @error('category_id') is-invalid @enderror" 
                                            required>
                                        <option value="">Selecciona una categoría</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->text }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>                                <!-- Mostrar contenido de Spotify actual si existe -->
                                @if($post->spotify_data)
                                    <div class="border-top pt-4 mb-4">
                                        <h3 class="text-white fw-semibold mb-3">
                                            <i class="fab fa-spotify text-success me-2"></i>
                                            Contenido de Spotify actual
                                        </h3>
                                        <div class="spotify-content p-4 rounded-3">
                                            <div class="d-flex align-items-center gap-3">
                                                @if($post->spotify_image)
                                                    <img src="{{ $post->spotify_image }}" 
                                                         alt="{{ $post->spotify_name }}" 
                                                         class="rounded-3 flex-shrink-0"
                                                         style="width: 64px; height: 64px; object-fit: cover;">
                                                @endif
                                                <div class="flex-grow-1">
                                                    <h4 class="spotify-title mb-1">{{ $post->spotify_name }}</h4>
                                                    @if($post->spotify_artist)
                                                        <p class="spotify-artist mb-2">{{ $post->spotify_artist }}</p>
                                                    @endif
                                                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50">
                                                        {{ $post->spotify_type === 'track' ? 'Canción' : ($post->spotify_type === 'artist' ? 'Artista' : 'Álbum') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-info mt-3 mb-0">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-info-circle me-2 mt-1"></i>
                                                <div>
                                                    <strong>Nota:</strong> No es posible cambiar el contenido de Spotify asociado a esta publicación. 
                                                    Si deseas asociar contenido diferente, deberás crear una nueva publicación.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Botones -->
                                <div class="d-flex align-items-center justify-content-end gap-3 pt-4 border-top border-light border-opacity-25">
                                    <a href="{{ route('posts.show', $post) }}" 
                                       class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>
                                        Actualizar Publicación
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
