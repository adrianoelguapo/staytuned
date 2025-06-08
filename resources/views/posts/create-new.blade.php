<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nueva Publicación | StayTuned</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/playlists.css') }}" rel="stylesheet">
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
    </div>

    <!-- Contenido principal -->
    <div class="dashboard-container container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-6">
                
                <div class="d-flex align-items-center gap-3 mb-4">
                    <a href="{{ route('posts.index') }}" class="btn btn-outline-light">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h1 class="h3 text-white mb-0">Nueva Publicación</h1>
                </div>

                <div class="playlist-card">
                    <form method="POST" action="{{ route('posts.store') }}">
                        @csrf

                        <!-- Título -->
                        <div class="mb-4">
                            <label for="title" class="form-label">Título de la publicación</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Comparte tus pensamientos sobre música...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Categoría -->
                        <div class="mb-4">
                            <label for="category_id" class="form-label">Categoría</label>
                            <select class="form-control @error('category_id') is-invalid @enderror" 
                                    id="category_id" 
                                    name="category_id" 
                                    required>
                                <option value="">Selecciona una categoría</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->text }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Búsqueda de Spotify -->
                        <div class="spotify-search mb-4">
                            <label class="form-label">Contenido de Spotify (Opcional)</label>
                            <p class="text-white-50 small mb-3">Busca y selecciona música de Spotify para enriquecer tu publicación</p>
                            
                            <div class="mb-3">
                                <div class="input-group">
                                    <select class="form-control" id="searchType">
                                        <option value="track">Canciones</option>
                                        <option value="artist">Artistas</option>
                                        <option value="album">Álbumes</option>
                                        <option value="playlist">Playlists</option>
                                    </select>
                                    <input type="text" 
                                           class="form-control" 
                                           id="spotifySearch" 
                                           placeholder="Buscar en Spotify...">
                                    <button class="btn btn-primary" type="button" onclick="searchSpotify()">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Resultados de búsqueda -->
                            <div id="spotifyResults" class="spotify-results" style="display: none;"></div>
                            
                            <!-- Elemento seleccionado -->
                            <div id="selectedSpotify" style="display: none;" class="mt-3">
                                <h6 class="text-white">Seleccionado:</h6>
                                <div id="selectedSpotifyContent" class="spotify-item selected"></div>
                                <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="clearSelection()">
                                    <i class="bi bi-x"></i> Quitar selección
                                </button>
                            </div>
                        </div>

                        <!-- Campos ocultos para Spotify -->
                        <input type="hidden" name="spotify_id" id="spotify_id">
                        <input type="hidden" name="spotify_type" id="spotify_type">
                        <input type="hidden" name="spotify_external_url" id="spotify_external_url">
                        <input type="hidden" name="spotify_data" id="spotify_data">

                        <!-- Botones -->
                        <div class="d-flex gap-3">
                            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Crear Publicación</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let selectedSpotifyItem = null;

        async function searchSpotify() {
            const query = document.getElementById('spotifySearch').value;
            const type = document.getElementById('searchType').value;
            
            if (!query.trim()) return;

            try {
                const response = await fetch('/api/spotify/search', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ query, type })
                });

                const data = await response.json();
                displayResults(data.items || [], type);
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function displayResults(items, type) {
            const resultsDiv = document.getElementById('spotifyResults');
            
            if (items.length === 0) {
                resultsDiv.innerHTML = '<p class="text-white-50">No se encontraron resultados</p>';
                resultsDiv.style.display = 'block';
                return;
            }

            let html = '';
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
                    <div class="spotify-item" onclick="selectItem(${JSON.stringify(item).replace(/"/g, '&quot;')}, '${type}')">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-spotify spotify-icon fs-4 me-3"></i>
                            ${imageUrl ? `<img src="${imageUrl}" class="me-3" style="width: 50px; height: 50px; border-radius: 4px; object-fit: cover;">` : ''}
                            <div>
                                <div class="fw-bold text-white">${item.name}</div>
                                <div class="text-white-50 small">${subtitle}</div>
                            </div>
                        </div>
                    </div>
                `;
            });

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
            }

            document.getElementById('selectedSpotifyContent').innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-spotify spotify-icon fs-4 me-3"></i>
                    ${imageUrl ? `<img src="${imageUrl}" class="me-3" style="width: 50px; height: 50px; border-radius: 4px; object-fit: cover;">` : ''}
                    <div>
                        <div class="fw-bold text-white">${item.name}</div>
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
        }
    </script>

</body>
</html>
