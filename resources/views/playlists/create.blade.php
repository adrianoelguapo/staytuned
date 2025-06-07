<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Playlist | StayTuned</title>
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
            <a href="{{ route('playlists.index') }}" class="nav-link-inline">Mis playlists</a>
            <a href="{{ route('posts.index') }}" class="nav-link-inline">Publicaciones</a>
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
                <a class="nav-link active" href="{{ route('playlists.index') }}">Mis playlists</a>
                <a class="nav-link" href="{{ route('posts.index') }}">Publicaciones</a>
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
            <div class="col-12 col-lg-8">
                
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('playlists.index') }}" class="text-white-50">
                                <i class="bi bi-music-note-list me-1"></i>
                                Mis Playlists
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Nueva Playlist</li>
                    </ol>
                </nav>

                <!-- Formulario de creación -->
                <div class="card create-playlist-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-plus-circle text-white me-3 fs-3"></i>
                            <h1 class="h3 mb-0 create-playlist-title">Crear Nueva Playlist</h1>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('playlists.store') }}" method="POST" enctype="multipart/form-data" class="playlist-form">
                            @csrf
                            
                            <div class="row">
                                <!-- Columna izquierda - Imagen -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label">Imagen de la playlist</label>
                                    <div class="playlist-image-upload">
                                        <div class="image-preview" id="imagePreview">
                                            <div class="image-placeholder">
                                                <i class="bi bi-image fs-1"></i>
                                                <p class="mt-2 mb-0">Seleccionar imagen</p>
                                            </div>
                                        </div>
                                        <input type="file" 
                                               class="form-control mt-3" 
                                               id="cover_image" 
                                               name="cover_image" 
                                               accept="image/*"
                                               onchange="previewImage(this)">
                                        <small class="text-muted">JPG, PNG, GIF. Máximo 2MB.</small>
                                    </div>
                                </div>

                                <!-- Columna derecha - Formulario -->
                                <div class="col-md-8">
                                    <!-- Nombre de la playlist -->
                                    <div class="mb-3">
                                        <label for="name" class="form-label">
                                            Nombre de la playlist <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               maxlength="100" 
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Descripción -->
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Descripción</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" 
                                                  name="description" 
                                                  rows="4" 
                                                  maxlength="500"
                                                  placeholder="Describe tu playlist...">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Máximo 500 caracteres</small>
                                    </div>

                                    <!-- Privacidad -->
                                    <div class="mb-4">
                                        <label class="form-label">Privacidad</label>
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="is_public" 
                                                   id="public" 
                                                   value="1" 
                                                   {{ old('is_public', '1') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="public">
                                                <i class="bi bi-globe me-2"></i>
                                                <strong>Pública</strong>
                                                <br>
                                                <small class="text-muted">Otros usuarios pueden ver y reproducir esta playlist</small>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="is_public" 
                                                   id="private" 
                                                   value="0" 
                                                   {{ old('is_public') == '0' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="private">
                                                <i class="bi bi-lock me-2"></i>
                                                <strong>Privada</strong>
                                                <br>
                                                <small class="text-muted">Solo tú puedes ver y reproducir esta playlist</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="d-flex gap-3 justify-content-end">
                                <a href="{{ route('playlists.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-lg me-2"></i>
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary-playlist">
                                    <i class="bi bi-plus-lg me-2"></i>
                                    Crear Playlist
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                const preview = document.getElementById('imagePreview');
                
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="img-fluid rounded">`;
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Contador de caracteres para descripción
        const descriptionTextarea = document.getElementById('description');
        const maxLength = descriptionTextarea.getAttribute('maxlength');
        
        if (descriptionTextarea) {
            descriptionTextarea.addEventListener('input', function() {
                const currentLength = this.value.length;
                const remaining = maxLength - currentLength;
                
                let counterElement = document.getElementById('charCounter');
                if (!counterElement) {
                    counterElement = document.createElement('small');
                    counterElement.id = 'charCounter';
                    counterElement.className = 'text-muted';
                    this.parentNode.appendChild(counterElement);
                }
                
                counterElement.textContent = `${currentLength}/${maxLength} caracteres`;
                
                if (remaining < 50) {
                    counterElement.className = 'text-warning';
                } else if (remaining < 20) {
                    counterElement.className = 'text-danger';
                } else {
                    counterElement.className = 'text-muted';
                }
            });
        }
    </script>
</body>
</html>
