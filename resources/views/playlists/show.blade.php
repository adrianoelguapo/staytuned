<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
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
            <a href="{{ route('explore.users.index') }}" class="nav-link-inline">Explorar usuarios</a>
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
                                             class="playlist-header-cover"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="playlist-header-cover d-none align-items-center justify-content-center">
                                            <i class="bi bi-music-note-beamed"></i>
                                        </div>
                                    @else
                                        <div class="playlist-header-cover d-flex align-items-center justify-content-center">
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
                                                 class="rounded-circle me-2 user-photo-small" 
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
                                    <div class="dropdown d-inline">
                                        <button class="btn btn-options-large btn-purple" type="button" 
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
                                       class="form-control search-input" 
                                       id="searchInput" 
                                       placeholder="Buscar canciones, artistas o álbumes...">
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-purple w-100" onclick="searchSpotify()">
                                    <i class="bi bi-search me-2"></i>
                                    Buscar
                                </button>
                            </div>
                        </div>
                        <div id="searchResults" class="mt-4 search-results-container"></div>
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
                                            <button class="btn-play-song" 
                                                    onclick="openSpotify('{{ $song->spotify_url }}')" 
                                                    title="Abrir en Spotify"
                                                    {{ !$song->spotify_url ? 'disabled style="opacity: 0.3; cursor: not-allowed;" title="No disponible en Spotify"' : '' }}>
                                                <i class="bi bi-spotify"></i>
                                            </button>
                                        </div>
                                        <div class="song-title">
                                            <div class="song-info">
                                                @if($song->album_image)
                                                    <img src="{{ $song->album_image }}" 
                                                         alt="{{ $song->title }}" 
                                                         class="song-cover-img"
                                                         onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none'); this.nextElementSibling.classList.add('d-flex');">
                                                    <div class="song-cover-placeholder d-none align-items-center justify-content-center">
                                                        <i class="bi bi-music-note"></i>
                                                    </div>
                                                @else
                                                    <div class="song-cover-placeholder d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-music-note"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="song-name">{{ $song->title ?: $song->name ?: 'Canción sin título' }}</div>
                                                    <div class="song-artist">{{ $song->artist ?: $song->author ?: 'Artista desconocido' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="song-album d-none d-md-block">
                                            {{ $song->album ?? 'Desconocido' }}
                                        </div>
                                        <div class="song-duration">
                                            {{ $song->duration_formatted ?? $song->duration ?? '--:--' }}
                                        </div>
                                        <div class="song-actions">
                                            <div class="custom-dropdown-container">
                                                <button class="btn btn-sm btn-link text-muted custom-dropdown-trigger" 
                                                        type="button" 
                                                        data-song-id="{{ $song->id }}"
                                                        onclick="toggleCustomDropdown(this)">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <div class="custom-dropdown-menu" id="dropdown-{{ $song->id }}" style="display: none;">
                                                    <div class="custom-dropdown-item">
                                                        <form action="{{ route('playlists.songs.remove', [$playlist, $song]) }}" 
                                                              method="POST" style="margin: 0;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="custom-dropdown-btn text-danger">
                                                                <i class="bi bi-trash me-2"></i>Quitar de la playlist
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
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
        // Añadir animación al panel de búsqueda
        function toggleSpotifySearch() {
            const searchDiv = document.getElementById('spotifySearch');
            
            if (searchDiv.style.display === 'none' || !searchDiv.style.display) {
                // Mostrar el panel con animación
                searchDiv.style.display = 'block';
                searchDiv.style.opacity = 0;
                searchDiv.style.transform = 'translateY(-20px)';
                
                setTimeout(() => {
                    searchDiv.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    searchDiv.style.opacity = 1;
                    searchDiv.style.transform = 'translateY(0)';
                    document.getElementById('searchInput').focus();
                }, 10);
            } else {
                // Ocultar el panel con animación
                searchDiv.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                searchDiv.style.opacity = 0;
                searchDiv.style.transform = 'translateY(-20px)';
                
                setTimeout(() => {
                    searchDiv.style.display = 'none';
                }, 300);
            }
        }

        function searchSpotify() {
            const query = document.getElementById('searchInput').value.trim();
            if (!query) return;

            const resultsDiv = document.getElementById('searchResults');
            resultsDiv.innerHTML = '<div class="text-center p-4 dashboard-card loading-container"><div class="spinner-border" role="status"></div><div class="mt-2 text-white">Buscando canciones...</div></div>';

            fetch(`{{ route('playlists.search') }}?q=${encodeURIComponent(query)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    displaySearchResults(data);
                })
                .catch(error => {
                    console.error('Error details:', error);
                    resultsDiv.innerHTML = `<div class="alert alert-danger dashboard-card">Error al buscar canciones: ${error.message}</div>`;
                });
        }

        function displaySearchResults(data) {
            const resultsDiv = document.getElementById('searchResults');
            
            if (!data.tracks || data.tracks.length === 0) {
                resultsDiv.innerHTML = '<div class="alert alert-info dashboard-card">No se encontraron resultados. Intenta con otra búsqueda.</div>';
                return;
            }

            let html = '<div class="search-results">';
            
            data.tracks.forEach(track => {
                html += `
                    <div class="search-result-item dashboard-card">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <img src="${track.album.images[2]?.url || track.album.images[1]?.url || track.album.images[0]?.url || ''}" 
                                     alt="${track.name}" 
                                     class="search-result-cover-img"
                                     onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none'); this.nextElementSibling.classList.add('d-flex');"
                                     style="${!track.album.images.length ? 'display:none;' : ''}">
                                <div class="search-result-cover-placeholder ${track.album.images.length ? 'd-none' : 'd-flex'} align-items-center justify-content-center">
                                    <i class="bi bi-music-note"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="search-result-title">${track.name}</div>
                                <div class="search-result-artist">${track.artists.map(a => a.name).join(', ')}</div>
                                <div class="search-result-album">${track.album.name}</div>
                            </div>
                            <div class="search-result-duration me-3">${formatDuration(track.duration_ms)}</div>
                            <button class="btn btn-sm btn-purple me-2" onclick="window.open('${track.external_urls.spotify}', '_blank')" title="Abrir en Spotify">
                                <i class="bi bi-spotify"></i>
                            </button>
                            <button class="btn btn-sm btn-primary-playlist add-song-btn" onclick="addToPlaylist('${track.id}')">
                                <i class="bi bi-plus-circle me-1"></i>
                                <span class="d-none d-sm-inline">Agregar</span>
                            </button>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            resultsDiv.innerHTML = html;
        }

        function addToPlaylist(spotifyId) {
            const button = event.target.closest('.add-song-btn');
            const originalContent = button.innerHTML;
            
            // Cambiar el botón a estado de carga
            button.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> <span class="d-none d-sm-inline">Agregando...</span>';
            button.disabled = true;
            
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
                    // Mostrar éxito
                    button.innerHTML = '<i class="bi bi-check-circle me-1"></i> <span class="d-none d-sm-inline">¡Agregada!</span>';
                    button.classList.add('success');
                    
                    // Recargar después de un breve delay
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    // Restaurar botón en caso de error
                    button.innerHTML = originalContent;
                    button.disabled = false;
                    alert('Error al agregar la canción: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                button.innerHTML = originalContent;
                button.disabled = false;
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

        // Función para manejar dropdown personalizado
        function toggleCustomDropdown(trigger) {
            const songId = trigger.getAttribute('data-song-id');
            const dropdown = document.getElementById('dropdown-' + songId);
            
            // Cerrar todos los otros dropdowns
            document.querySelectorAll('.custom-dropdown-menu').forEach(menu => {
                if (menu !== dropdown) {
                    menu.style.display = 'none';
                }
            });
            
            // Toggle del dropdown actual
            if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                // Mostrar dropdown
                dropdown.style.display = 'block';
                
                // Forzar el recálculo del layout
                dropdown.offsetHeight;
                
                // Posicionar el dropdown correctamente
                const triggerRect = trigger.getBoundingClientRect();
                const dropdownRect = dropdown.getBoundingClientRect();
                const viewportHeight = window.innerHeight;
                const viewportWidth = window.innerWidth;
                
                // Calcular posición inicial (centrado con el botón)
                let left = triggerRect.left - (dropdownRect.width / 2) + (triggerRect.width / 2);
                let top = triggerRect.bottom + 8; // 8px de margen
                
                // Ajustar horizontalmente si se sale de la pantalla
                if (left < 16) {
                    left = 16; // Margen de 16px desde el borde izquierdo
                } else if (left + dropdownRect.width > viewportWidth - 16) {
                    left = viewportWidth - dropdownRect.width - 16; // Margen de 16px desde el borde derecho
                }
                
                // Ajustar verticalmente si se sale de la pantalla por abajo
                if (top + dropdownRect.height > viewportHeight - 16) {
                    // Posicionar arriba del botón en lugar de abajo
                    top = triggerRect.top - dropdownRect.height - 8;
                    
                    // Si también se sale por arriba, centrar en la pantalla
                    if (top < 16) {
                        top = Math.max(16, (viewportHeight - dropdownRect.height) / 2);
                    }
                }
                
                // Aplicar posición con position fixed
                dropdown.style.position = 'fixed';
                dropdown.style.top = Math.round(top) + 'px';
                dropdown.style.left = Math.round(left) + 'px';
                dropdown.style.zIndex = '10001';
                dropdown.style.transform = 'none';
                dropdown.style.right = 'auto';
                dropdown.style.bottom = 'auto';
                
                console.log('Dropdown positioned at:', { top: Math.round(top), left: Math.round(left) });
            } else {
                // Ocultar dropdown
                dropdown.style.display = 'none';
            }
        }
        
        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-dropdown-container')) {
                document.querySelectorAll('.custom-dropdown-menu').forEach(menu => {
                    menu.style.display = 'none';
                });
            }
        });

        // Función para abrir Spotify
        function openSpotify(spotifyUrl) {
            // Si no hay URL de Spotify, mostrar mensaje
            if (!spotifyUrl) {
                alert('No hay enlace de Spotify disponible para esta canción');
                return;
            }

            // Abrir Spotify en una nueva pestaña
            window.open(spotifyUrl, '_blank');
        }
    </script>
</body>
</html>
