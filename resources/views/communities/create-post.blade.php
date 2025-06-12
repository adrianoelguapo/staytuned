<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nueva Publicación en {{ $community->name }} | StayTuned</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/playlists.css') }}" rel="stylesheet">
    
    <link href="{{ asset('css/navbar-fix.css') }}?v={{ time() }}" rel="stylesheet"><link href="{{ asset('css/posts.css') }}" rel="stylesheet">
    <link href="{{ asset('css/community-fixed.css') }}" rel="stylesheet">
</head>

<body class="dashboard-body">

    <nav class="navbar navbar-expand-lg px-5 py-3">
        <div class="d-flex align-items-center">
            <button class="btn btn-link btn-offcanvas me-3 p-0 d-lg-none" type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasMenu"
                    aria-controls="offcanvasMenu">
                <i class="bi bi-list text-white fs-3"></i>
            </button>
            <a class="navbar-brand text-white fw-bold" href="{{ url('dashboard') }}">StayTuned</a>
        </div>

        <div class="d-none d-lg-flex ms-auto align-items-center gap-3">
            <a href="{{ route('dashboard') }}" class="nav-link-inline">Dashboard</a>
            <a href="{{ route('explore.users.index') }}" class="nav-link-inline">Explorar usuarios</a>
            <a href="{{ route('playlists.index') }}" class="nav-link-inline">Mis playlists</a>
            <a href="{{ route('posts.index') }}" class="nav-link-inline">Mis Publicaciones</a>
            <a href="{{ route('communities.index') }}" class="nav-link-inline active">Mis Comunidades</a>

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

    <!-- Offcanvas para móvil -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title text-white">StayTuned</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <nav class="nav flex-column">
                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link" href="{{ route('explore.users.index') }}">Explorar usuarios</a>
                <a class="nav-link" href="{{ route('playlists.index') }}">Mis playlists</a>
                <a class="nav-link" href="{{ route('posts.index') }}">Mis Publicaciones</a>
                <a class="nav-link active" href="{{ route('communities.index') }}">Mis Comunidades</a>
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

    <!-- Contenido principal -->
    <div class="dashboard-container container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('communities.show', $community) }}" class="text-white-50">
                                <i class="bi bi-people me-1"></i>
                                {{ $community->name }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Nueva Publicación</li>
                    </ol>
                </nav>                <!-- Community Info Banner -->
                <div class="mb-4 community-card">
                    <div class="d-flex align-items-center">
                        @if($community->cover_image)
                            <img src="{{ asset('storage/' . $community->cover_image) }}" 
                                 alt="{{ $community->name }}" 
                                 class="rounded me-3" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="rounded me-3 d-flex align-items-center justify-content-center" 
                                 style="width: 60px; height: 60px; background: rgba(255,255,255,0.10);">
                                <i class="fas fa-users" style="color: #fff;"></i>
                            </div>
                        @endif
                        <div>
                            <h5 class="mb-1" style="color: #fff;">Publicando en {{ $community->name }}</h5>
                            <small style="color: #e0e0e0;">
                                {{ $community->members_count }} miembros • 
                                {{ $community->is_private ? 'Privada' : 'Pública' }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Formulario de creación -->
                <div class="card create-playlist-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-plus-circle text-white me-3 fs-3"></i>
                            <h1 class="h3 mb-0 create-playlist-title">Crear Nueva Publicación</h1>
                        </div>

                        <!-- Alerta de contenido moderado -->
                        @include('components.content-moderation-alert')

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('posts.store') }}" method="POST" class="playlist-form">
                            @csrf
                            <!-- Campo oculto para la comunidad -->
                            <input type="hidden" name="community_id" value="{{ $community->id }}">
                            
                            <div class="row">
                                <!-- Columna izquierda - Información principal -->
                                <div class="col-md-8 mb-4">
                                    <!-- Título -->
                                    <div class="mb-4">
                                        <label for="title" class="form-label">Título de la publicación</label>
                                        <input type="text" 
                                               class="form-control @error('title') is-invalid @enderror" 
                                               id="title" 
                                               name="title" 
                                               value="{{ old('title') }}" 
                                               placeholder="¿Qué quieres compartir con la comunidad?"
                                               required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Categoría -->
                                    <div class="mb-4">
                                        <label for="category_id" class="form-label">Tipo de Publicación</label>
                                        
                                        <!-- Botón selector de categoría -->
                                        <button type="button" id="categorySelector" class="category-selector-btn">
                                            <span id="categorySelectedText">Selecciona el tipo de contenido</span>
                                            <i class="bi bi-chevron-down category-selector-arrow"></i>
                                        </button>
                                        
                                        <!-- Input oculto para enviar el valor -->
                                        <input type="hidden" id="category_id" name="category_id" value="{{ old('category_id') }}" required>
                                        
                                        @error('category_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Contenido automático basado en categoría -->
                                    <div class="mb-4">
                                        <label for="category_content" class="form-label">Contenido de la Publicación</label>
                                        <div class="form-control" 
                                             id="category_content" 
                                             style="background-color: #f8f9fa; min-height: 80px; padding: 12px; border-radius: 6px;">
                                            <em class="text-muted">Selecciona un tipo de publicación para ver el contenido...</em>
                                        </div>
                                        <small class="text-muted">Este contenido se generará automáticamente según el tipo de publicación seleccionado.</small>
                                    </div>
                                </div>

                                <!-- Columna derecha - Contenido de Spotify -->
                                <div class="col-md-4 mb-4">
                                    <div id="spotify-section" style="display: none;">
                                        <label class="form-label">
                                            Contenido de Spotify <span class="text-danger">*</span>
                                        </label>
                                        <div class="spotify-search-container">
                                            <div class="spotify-preview" id="spotifyPreview">
                                                <div class="spotify-placeholder">
                                                    <i class="bi bi-spotify fs-1"></i>
                                                    <p class="mt-2 mb-0" id="spotify-search-text">Buscar en Spotify</p>
                                                </div>
                                            </div>
                                            
                                            <!-- Búsqueda de Spotify -->
                                            <div class="mt-3">
                                                <div class="input-group mb-3">
                                                    <input type="text" 
                                                           class="form-control" 
                                                           id="spotifySearch" 
                                                           placeholder="Buscar...">
                                                    <button class="btn btn-primary" type="button" onclick="searchSpotify()">
                                                        <i class="bi bi-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Mensaje cuando no se requiere Spotify -->
                                    <div id="no-spotify-section" class="text-center p-4">
                                        <i class="bi bi-info-circle fs-1 text-muted"></i>
                                        <p class="mt-2 mb-0 text-muted">Selecciona un tipo de publicación que requiera contenido de Spotify</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Resultados de búsqueda de Spotify -->
                            <div id="spotifyResults" class="spotify-results mt-3" style="display: none;"></div>
                            
                            <!-- Elemento seleccionado -->
                            <div id="selectedSpotify" style="display: none;" class="mt-3">
                                <div id="selectedSpotifyContent" class="spotify-item selected"></div>
                                <button type="button" class="btn btn-secondary btn-sm mt-2 w-100" onclick="clearSelection()">
                                    <i class="bi bi-x"></i> Quitar selección
                                </button>
                            </div>

                            <!-- Campos ocultos para Spotify -->
                            <input type="hidden" name="spotify_id" id="spotify_id">
                            <input type="hidden" name="spotify_type" id="spotify_type">
                            <input type="hidden" name="spotify_external_url" id="spotify_external_url">
                            <input type="hidden" name="spotify_data" id="spotify_data">

                            <!-- Botones de acción -->
                            <div class="d-flex gap-3 mt-4">
                                <a href="{{ route('communities.show', $community) }}" class="btn btn-secondary flex-fill">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary-playlist flex-fill" id="submitBtn">
                                    <i class="bi bi-plus-lg me-2"></i>
                                    Publicar en Comunidad
                                </button>
                            </div>

                            <!-- Mensaje de validación -->
                            <div id="validation-message" class="alert alert-warning mt-3" style="display: none;">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <span id="validation-text">Por favor, selecciona contenido de Spotify para continuar.</span>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>    </div>

    <!-- Modal de selección de categorías (posicionado fuera de todos los contenedores) -->
    <div id="categoryModal" class="category-modal">
        <div class="category-modal-content">
            <div class="category-modal-header">
                <h5 class="category-modal-title">Selecciona el tipo de publicación</h5>
                <button type="button" class="category-modal-close" id="closeCategoryModal">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <div class="category-options">
                @foreach($categories as $category)
                    <div class="category-option" 
                         data-value="{{ $category->id }}" 
                         data-type="{{ $category->type }}"
                         data-text="{{ $category->text }}">
                        <div>
                            <div class="fw-semibold">{{ ucfirst($category->type) }}</div>
                            <small class="text-muted">{{ $category->text }}</small>
                        </div>
                        <div class="category-option-check">
                            <i class="bi bi-check" style="display: none;"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
      <script>
        let selectedSpotifyItem = null;
        let currentSearchType = 'track'; // Tipo de búsqueda según la categoría

        // Mapeo de tipos de categoría a tipos de búsqueda de Spotify
        const categoryToSpotifyType = {
            'cancion': 'track',
            'album': 'album', 
            'artista': 'artist',
            'playlist': 'playlist'
        };        // Función para actualizar el contenido de la categoría
        function updateCategoryContent() {
            const categoryIdInput = document.getElementById('category_id');
            const contentDiv = document.getElementById('category_content');
            
            if (categoryIdInput.value) {
                // Buscar la opción seleccionada en el modal
                const selectedOption = document.querySelector(`[data-value="${categoryIdInput.value}"]`);
                
                if (selectedOption) {
                    const categoryText = selectedOption.getAttribute('data-text');
                    const categoryType = selectedOption.getAttribute('data-type');
                    
                    // Crear un mensaje más descriptivo con el tipo de contenido
                    const typeLabels = {
                        'cancion': 'canción',
                        'album': 'álbum', 
                        'artista': 'artista',
                        'playlist': 'playlist'
                    };
                    
                    contentDiv.innerHTML = `
                        <div class="d-flex align-items-start">
                            <i class="bi bi-quote text-primary me-2 fs-5"></i>
                            <div>
                                <p class="mb-2">"${categoryText}"</p>
                                <small class="text-muted">
                                    Tu publicación compartirá una <strong>${typeLabels[categoryType]}</strong> de Spotify con este mensaje.
                                </small>
                            </div>
                        </div>
                    `;
                }
            } else {
                contentDiv.innerHTML = '<em class="text-muted">Selecciona un tipo de publicación para ver el contenido...</em>';
            }
        }

        // Función para actualizar la sección de Spotify según la categoría
        function updateSpotifySearch() {
            const categoryIdInput = document.getElementById('category_id');
            const spotifySection = document.getElementById('spotify-section');
            const noSpotifySection = document.getElementById('no-spotify-section');
            const spotifySearchText = document.getElementById('spotify-search-text');
            
            if (categoryIdInput.value) {
                // Buscar la opción seleccionada en el modal
                const selectedOption = document.querySelector(`[data-value="${categoryIdInput.value}"]`);
                
                if (selectedOption) {
                    const categoryType = selectedOption.getAttribute('data-type');
                    
                    // Mostrar sección de Spotify para todos los tipos
                    spotifySection.style.display = 'block';
                    noSpotifySection.style.display = 'none';
                    
                    // Actualizar el tipo de búsqueda
                    currentSearchType = categoryToSpotifyType[categoryType] || 'track';
                    
                    // Actualizar texto del placeholder
                    const searchTexts = {
                        'track': 'Buscar canciones en Spotify',
                        'album': 'Buscar álbumes en Spotify', 
                        'artist': 'Buscar artistas en Spotify',
                        'playlist': 'Buscar playlists en Spotify'
                    };
                    
                    spotifySearchText.textContent = searchTexts[currentSearchType];
                    document.getElementById('spotifySearch').placeholder = `Buscar ${currentSearchType === 'track' ? 'canciones' : currentSearchType === 'album' ? 'álbumes' : currentSearchType === 'artist' ? 'artistas' : 'playlists'}...`;
                    
                    // Limpiar selección previa si cambia el tipo
                    clearSelection();
                }
            } else {
                // Ocultar sección de Spotify si no hay categoría seleccionada
                spotifySection.style.display = 'none';
                noSpotifySection.style.display = 'block';
                clearSelection();
            }
        }        async function searchSpotify() {
            const query = document.getElementById('spotifySearch').value;
            
            if (!query.trim()) return;

            try {
                // Usar la ruta web correcta con parámetros de query
                const url = new URL('/posts/search/spotify', window.location.origin);
                url.searchParams.append('query', query);
                url.searchParams.append('type', currentSearchType);

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                
                // Manejar errores del servidor
                if (data.error) {
                    console.error('Error de Spotify:', data.error);
                    displayResults([], currentSearchType);
                    return;
                }
                
                displayResults(data.results || [], currentSearchType);
            } catch (error) {
                console.error('Error de conexión:', error);
                displayResults([], currentSearchType);
            }
        }

        function displayResults(items, type) {
            const resultsDiv = document.getElementById('spotifyResults');
            
            if (items.length === 0) {
                resultsDiv.innerHTML = '<p class="text-white-50 small">No se encontraron resultados</p>';
                resultsDiv.style.display = 'block';
                return;
            }

            let html = '<div class="spotify-results-list">';
            items.forEach(item => {
                let imageUrl = '';
                let subtitle = '';
                
                if (item.images && item.images.length > 0) {
                    imageUrl = item.images[0].url;
                } else if (item.album && item.album.images && item.album.images.length > 0) {
                    imageUrl = item.album.images[0].url;
                }

                switch (type) {
                    case 'track':
                        subtitle = item.artists ? item.artists.map(a => a.name).join(', ') : '';
                        break;
                    case 'album':
                        subtitle = item.artists ? item.artists.map(a => a.name).join(', ') : '';
                        break;
                    case 'artist':
                        subtitle = `${item.followers ? item.followers.total.toLocaleString() : 0} seguidores`;
                        break;
                    case 'playlist':
                        subtitle = `${item.tracks ? item.tracks.total : 0} canciones`;
                        break;
                }

                html += `
                    <div class="spotify-result-item" onclick="selectItem(${JSON.stringify(item).replace(/"/g, '&quot;')}, '${type}')">
                        <div class="d-flex align-items-center p-2">
                            <i class="bi bi-spotify text-success me-2"></i>
                            ${imageUrl ? `<img src="${imageUrl}" class="me-2" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">` : ''}
                            <div class="flex-grow-1">
                                <div class="fw-bold text-white small">${item.name}</div>
                                <div class="text-white-50 small">${subtitle}</div>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';

            resultsDiv.innerHTML = html;
            resultsDiv.style.display = 'block';
        }

        function selectItem(item, type) {
            selectedSpotifyItem = item;
            document.getElementById('spotify_id').value = item.id;
            document.getElementById('spotify_type').value = type;
            document.getElementById('spotify_external_url').value = item.external_urls?.spotify || '';
            document.getElementById('spotify_data').value = JSON.stringify(item);

            let imageUrl = '';
            let subtitle = '';
            
            if (item.images && item.images.length > 0) {
                imageUrl = item.images[0].url;
            } else if (item.album && item.album.images && item.album.images.length > 0) {
                imageUrl = item.album.images[0].url;
            }

            switch (type) {
                case 'track':
                    subtitle = item.artists ? item.artists.map(a => a.name).join(', ') : '';
                    break;
                case 'album':
                    subtitle = item.artists ? item.artists.map(a => a.name).join(', ') : '';
                    break;
                case 'artist':
                    subtitle = `${item.followers ? item.followers.total.toLocaleString() : 0} seguidores`;
                    break;
                case 'playlist':
                    subtitle = `${item.tracks ? item.tracks.total : 0} canciones`;
                    break;
            }            // Actualizar la vista previa
            const spotifyPreview = document.getElementById('spotifyPreview');
            spotifyPreview.className = 'spotify-preview has-content';
            spotifyPreview.innerHTML = `
                <div class="spotify-preview-content">
                    ${imageUrl ? `<img src="${imageUrl}" class="spotify-preview-image" alt="${item.name}">` : '<div class="spotify-preview-image d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.1);"><i class="bi bi-spotify text-success"></i></div>'}
                    <div class="spotify-preview-info">
                        <div class="spotify-preview-title">${item.name}</div>
                        <div class="spotify-preview-subtitle">${subtitle}</div>
                    </div>
                </div>
            `;

            document.getElementById('selectedSpotifyContent').innerHTML = `
                <div class="d-flex align-items-center p-2">
                    <i class="bi bi-spotify text-success me-2"></i>
                    ${imageUrl ? `<img src="${imageUrl}" class="me-2" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">` : ''}
                    <div>
                        <div class="fw-bold text-white small">${item.name}</div>
                        <div class="text-white-50 small">${subtitle}</div>
                    </div>
                </div>
            `;

            document.getElementById('selectedSpotify').style.display = 'block';
            document.getElementById('spotifyResults').style.display = 'none';
            document.getElementById('spotifySearch').value = '';
        }

        function clearSelection() {
            selectedSpotifyItem = null;
            document.getElementById('spotify_id').value = '';
            document.getElementById('spotify_type').value = '';
            document.getElementById('spotify_external_url').value = '';
            document.getElementById('spotify_data').value = '';
            document.getElementById('selectedSpotify').style.display = 'none';
              // Restaurar placeholder
            const spotifySearchText = document.getElementById('spotify-search-text');
            const spotifyPreview = document.getElementById('spotifyPreview');
            spotifyPreview.className = 'spotify-preview';
            spotifyPreview.innerHTML = `
                <div class="spotify-placeholder">
                    <i class="bi bi-spotify fs-1"></i>
                    <p class="mt-2 mb-0">${spotifySearchText ? spotifySearchText.textContent : 'Buscar en Spotify'}</p>
                </div>
            `;
        }        // Permitir búsqueda con Enter
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('spotifySearch').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchSpotify();
                }
            });

            // Validación del formulario
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const categorySelect = document.getElementById('category_id');
                const spotifyId = document.getElementById('spotify_id').value;
                const validationMessage = document.getElementById('validation-message');
                const validationText = document.getElementById('validation-text');

                // Verificar que se haya seleccionado una categoría
                if (!categorySelect.value) {
                    e.preventDefault();
                    validationText.textContent = 'Por favor, selecciona un tipo de publicación.';
                    validationMessage.style.display = 'block';
                    validationMessage.scrollIntoView({ behavior: 'smooth' });
                    return;
                }

                // Verificar que se haya seleccionado contenido de Spotify
                if (!spotifyId) {
                    e.preventDefault();
                    validationText.textContent = 'Por favor, selecciona contenido de Spotify para tu publicación.';
                    validationMessage.style.display = 'block';
                    validationMessage.scrollIntoView({ behavior: 'smooth' });
                    return;
                }

                // Ocultar mensaje de validación si todo está bien
                validationMessage.style.display = 'none';
            });
        });
        // =============================================
        // FUNCIONALIDAD DEL MODAL DE CATEGORÍAS
        // =============================================
        
        // Variables del modal
        const categoryModal = document.getElementById('categoryModal');
        const categorySelector = document.getElementById('categorySelector');
        const closeCategoryModal = document.getElementById('closeCategoryModal');
        const categorySelectedText = document.getElementById('categorySelectedText');
        const categoryIdInput = document.getElementById('category_id');
        const categoryOptions = document.querySelectorAll('.category-option');

        // Abrir modal
        categorySelector.addEventListener('click', function() {
            categoryModal.classList.add('show');
            document.body.classList.add('modal-open'); // Prevenir scroll del body
        });

        // Cerrar modal
        function closeCategoryModalFunc() {
            categoryModal.classList.remove('show');
            document.body.classList.remove('modal-open'); // Restaurar scroll del body
        }

        closeCategoryModal.addEventListener('click', closeCategoryModalFunc);

        // Cerrar modal al hacer clic fuera del contenido
        categoryModal.addEventListener('click', function(e) {
            if (e.target === categoryModal) {
                closeCategoryModalFunc();
            }
        });

        // Cerrar modal con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && categoryModal.classList.contains('show')) {
                closeCategoryModalFunc();
            }
        });

        // Seleccionar categoría
        categoryOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remover selección anterior
                categoryOptions.forEach(opt => {
                    opt.classList.remove('selected');
                    opt.querySelector('.bi-check').style.display = 'none';
                });

                // Agregar selección actual
                this.classList.add('selected');
                this.querySelector('.bi-check').style.display = 'block';

                // Obtener datos
                const value = this.getAttribute('data-value');
                const type = this.getAttribute('data-type');
                const text = this.getAttribute('data-text');

                // Actualizar elementos
                categoryIdInput.value = value;
                categorySelectedText.textContent = this.querySelector('.fw-semibold').textContent;
                
                // Agregar clase de seleccionado al botón
                categorySelector.classList.add('selected');

                // Cerrar modal después de un breve delay
                setTimeout(() => {
                    closeCategoryModalFunc();
                    
                    // Llamar funciones existentes
                    updateCategoryContent();
                    updateSpotifySearch();
                }, 300);
            });
        });

        // Función para marcar categoría seleccionada si hay valor previo
        function initializeSelectedCategory() {
            const currentValue = categoryIdInput.value;
            if (currentValue) {
                const selectedOption = document.querySelector(`[data-value="${currentValue}"]`);
                if (selectedOption) {
                    selectedOption.classList.add('selected');
                    selectedOption.querySelector('.bi-check').style.display = 'block';
                    categorySelectedText.textContent = selectedOption.querySelector('.fw-semibold').textContent;
                    categorySelector.classList.add('selected');
                }
            }
        }

        // Inicializar al cargar la página
        document.addEventListener('DOMContentLoaded', initializeSelectedCategory);
    </script>

</body>
</html>
