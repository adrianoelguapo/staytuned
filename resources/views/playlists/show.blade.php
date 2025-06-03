<!DOCTYPE html>
<html lang="es">
<head>    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $playlist->name }} | StayTuned</title>
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
            <a href="#" class="nav-link-inline">Explorar usuarios</a>
            <a href="{{ route('playlists.index') }}" class="nav-link-inline">Mis playlists</a>
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
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.show') }}">
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
                <a class="nav-link" href="#">Explorar usuarios</a>
                <a class="nav-link active" href="{{ route('playlists.index') }}">Mis playlists</a>
                <a class="nav-link" href="#">Mis comunidades</a>
            </nav>
            <hr class="my-0">
            <nav class="nav flex-column">
                <a class="nav-link d-flex align-items-center" href="{{ route('profile.show') }}">
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
                
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('playlists.index') }}" class="text-white-50">
                                <i class="bi bi-music-note-list me-1"></i>
                                Mis Playlists
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ $playlist->name }}</li>
                    </ol>
                </nav>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                <!-- Header de la playlist -->
                <div class="playlist-header card dashboard-card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <!-- Imagen de la playlist -->
                                <div class="playlist-cover-large">                                    @if($playlist->cover)
                                        <img src="{{ asset('storage/' . $playlist->cover) }}" 
                                             alt="{{ $playlist->name }}" 
                                             class="img-fluid rounded">
                                    @else
                                        <div class="playlist-placeholder-large">
                                            <i class="bi bi-music-note-beamed"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col">
                                <!-- Info de la playlist -->
                                <div class="playlist-header-info">
                                    <span class="badge bg-secondary mb-2">PLAYLIST</span>
                                    <h1 class="playlist-title-large">{{ $playlist->name }}</h1>
                                    
                                    @if($playlist->description)
                                        <p class="playlist-description-large text-muted">{{ $playlist->description }}</p>
                                    @endif
                                    
                                    <div class="playlist-meta-large">
                                        <span class="me-3">
                                            <img src="{{ Auth::user()->profile_photo_url }}" 
                                                 class="rounded-circle me-2" 
                                                 style="width: 24px; height: 24px;" 
                                                 alt="{{ Auth::user()->name }}">
                                            {{ Auth::user()->username }}
                                        </span>
                                        <span class="me-3">
                                            <i class="bi bi-music-note me-1"></i>
                                            {{ $playlist->songs->count() }} canciones
                                        </span>
                                        <span class="me-3">
                                            <i class="bi bi-{{ $playlist->is_public ? 'globe' : 'lock' }} me-1"></i>
                                            {{ $playlist->is_public ? 'Pública' : 'Privada' }}
                                        </span>
                                        <span class="text-muted">
                                            Creada {{ $playlist->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <!-- Acciones -->
                                <div class="playlist-actions-header">
                                    <button class="btn btn-play-large me-2">
                                        <i class="bi bi-play-fill"></i>
                                    </button>
                                    <div class="dropdown d-inline">
                                        <button class="btn btn-options-large" type="button" 
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <button class="dropdown-item" onclick="toggleSpotifySearch()">
                                                    <i class="bi bi-plus me-2"></i>Agregar canciones
                                                </button>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('playlists.edit', $playlist) }}">
                                                    <i class="bi bi-pencil me-2"></i>Editar playlist
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('playlists.destroy', $playlist) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta playlist?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-2"></i>Eliminar playlist
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buscar canciones en Spotify (inicialmente oculto) -->
                <div id="spotifySearch" class="card dashboard-card mb-4" style="display: none;">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-search me-2"></i>
                            Buscar canciones en Spotify
                        </h5>
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" 
                                       class="form-control" 
                                       id="searchInput" 
                                       placeholder="Buscar canciones, artistas o álbumes...">
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary-playlist w-100" onclick="searchSpotify()">
                                    <i class="bi bi-search me-2"></i>
                                    Buscar
                                </button>
                            </div>
                        </div>
                        <div id="searchResults" class="mt-4"></div>
                    </div>
                </div>

                <!-- Lista de canciones -->
                <div class="card dashboard-card">
                    <div class="card-body">
                        @if($playlist->songs->count() > 0)
                            <div class="songs-table">
                                <div class="songs-header">
                                    <div class="song-number">#</div>
                                    <div class="song-title">Título</div>
                                    <div class="song-album d-none d-md-block">Álbum</div>
                                    <div class="song-duration">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <div class="song-actions"></div>
                                </div>
                                
                                @foreach($playlist->songs as $index => $song)
                                    <div class="song-row">
                                        <div class="song-number">
                                            <span class="song-index">{{ $index + 1 }}</span>
                                            <button class="btn-play-song">
                                                <i class="bi bi-play-fill"></i>
                                            </button>
                                        </div>
                                        <div class="song-title">
                                            <div class="song-info">
                                                @if($song->album_image)
                                                    <img src="{{ $song->album_image }}" 
                                                         alt="{{ $song->title }}" 
                                                         class="song-cover">
                                                @endif
                                                <div>
                                                    <div class="song-name">{{ $song->title }}</div>
                                                    <div class="song-artist">{{ $song->artist }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="song-album d-none d-md-block">
                                            {{ $song->album ?? 'Desconocido' }}
                                        </div>
                                        <div class="song-duration">
                                            {{ $song->duration ?? '--:--' }}
                                        </div>
                                        <div class="song-actions">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-link text-muted" 
                                                        type="button" 
                                                        data-bs-toggle="dropdown" 
                                                        aria-expanded="false">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <form action="{{ route('playlists.songs.remove', [$playlist, $song]) }}" 
                                                              method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="bi bi-trash me-2"></i>Quitar de la playlist
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Estado vacío -->
                            <div class="text-center py-5">
                                <i class="bi bi-music-note display-1 text-muted mb-3"></i>
                                <h4 class="text-muted mb-3">Tu playlist está vacía</h4>
                                <p class="text-muted mb-4">
                                    Busca y agrega tus canciones favoritas para comenzar a disfrutar tu música
                                </p>
                                <button class="btn btn-primary-playlist" onclick="toggleSpotifySearch()">
                                    <i class="bi bi-search me-2"></i>
                                    Buscar canciones
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleSpotifySearch() {
            const searchDiv = document.getElementById('spotifySearch');
            if (searchDiv.style.display === 'none') {
                searchDiv.style.display = 'block';
                document.getElementById('searchInput').focus();
            } else {
                searchDiv.style.display = 'none';
            }
        }

        function searchSpotify() {
            const query = document.getElementById('searchInput').value.trim();
            if (!query) return;

            const resultsDiv = document.getElementById('searchResults');
            resultsDiv.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Buscando...</span></div></div>';

            // Aquí implementaremos la búsqueda de Spotify
            fetch(`{{ route('playlists.search') }}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displaySearchResults(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultsDiv.innerHTML = '<div class="alert alert-danger">Error al buscar canciones. Inténtalo de nuevo.</div>';
                });
        }

        function displaySearchResults(data) {
            const resultsDiv = document.getElementById('searchResults');
            
            if (!data.tracks || data.tracks.length === 0) {
                resultsDiv.innerHTML = '<div class="alert alert-info">No se encontraron resultados.</div>';
                return;
            }

            let html = '<div class="search-results">';
            
            data.tracks.forEach(track => {
                html += `
                    <div class="search-result-item">
                        <div class="d-flex align-items-center">
                            <img src="${track.album.images[2]?.url || ''}" alt="${track.name}" class="search-result-cover me-3">
                            <div class="flex-grow-1">
                                <div class="search-result-title">${track.name}</div>
                                <div class="search-result-artist">${track.artists.map(a => a.name).join(', ')}</div>
                                <div class="search-result-album text-muted">${track.album.name}</div>
                            </div>
                            <div class="search-result-duration me-3">${formatDuration(track.duration_ms)}</div>
                            <button class="btn btn-sm btn-primary-playlist" onclick="addToPlaylist('${track.id}')">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            resultsDiv.innerHTML = html;
        }

        function addToPlaylist(spotifyId) {
            fetch(`{{ route('playlists.songs.add', $playlist) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ spotify_id: spotifyId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Recargar para mostrar la nueva canción
                } else {
                    alert('Error al agregar la canción: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al agregar la canción');
            });
        }

        function formatDuration(ms) {
            const minutes = Math.floor(ms / 60000);
            const seconds = ((ms % 60000) / 1000).toFixed(0);
            return minutes + ":" + (seconds < 10 ? '0' : '') + seconds;
        }

        // Permitir búsqueda con Enter
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchSpotify();
            }
        });
    </script>
</body>
</html>
