<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($playlist->name); ?> | StayTuned</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="<?php echo e(asset('css/dashboard.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/playlists.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/navbar-fix.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/dropdown-fix.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
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
            <a class="navbar-brand text-white fw-bold" href="<?php echo e(url('dashboard')); ?>">StayTuned</a>
        </div>

        <!-- Enlaces + usuario: solo ≥lg -->
        <div class="d-none d-lg-flex ms-auto align-items-center gap-3">
            <a href="<?php echo e(route('dashboard')); ?>" class="nav-link-inline">Dashboard</a>
            <a href="<?php echo e(route('explore.users.index')); ?>" class="nav-link-inline">Explorar Usuarios</a>
            <a href="<?php echo e(route('playlists.index')); ?>" class="nav-link-inline">Mis Playlists</a>
            <a href="<?php echo e(route('posts.index')); ?>" class="nav-link-inline">Mis Publicaciones</a>
            <a href="<?php echo e(route('communities.index')); ?>" class="nav-link-inline">Mis Comunidades</a>

            <div class="dropdown">
                <a class="d-flex align-items-center text-white dropdown-toggle nav-link-inline"
                   href="#"
                   id="userDropdown"
                   role="button"
                   data-bs-toggle="dropdown"
                   aria-expanded="false">
                    <?php if(Laravel\Jetstream\Jetstream::managesProfilePhotos()): ?>
                        <img src="<?php echo e(Auth::user()->profile_photo_url); ?>"
                             class="rounded-circle me-2 user-photo"
                             alt="<?php echo e(Auth::user()->name); ?>" />
                    <?php endif; ?>
                    <?php echo e(Auth::user()->username); ?>

                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="<?php echo e(route('profile.settings')); ?>">
                            <i class="bi bi-person me-2"></i> Perfil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
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
                <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                <a class="nav-link" href="<?php echo e(route('explore.users.index')); ?>">Explorar Usuarios</a>
                <a class="nav-link active" href="<?php echo e(route('playlists.index')); ?>">Mis Playlists</a>
                <a class="nav-link" href="<?php echo e(route('posts.index')); ?>">Mis Publicaciones</a>
                <a class="nav-link" href="<?php echo e(route('communities.index')); ?>">Mis Comunidades</a>
            </nav>
            <hr class="my-0">
            <nav class="nav flex-column">
                <a class="nav-link d-flex align-items-center" href="<?php echo e(route('profile.settings')); ?>">
                    <i class="bi bi-person me-2"></i> Perfil
                </a>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
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
                            <a href="<?php echo e(route('playlists.index')); ?>" class="text-white-50">
                                <i class="bi bi-music-note-list me-1"></i>
                                Mis Playlists
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-white" aria-current="page"><?php echo e($playlist->name); ?></li>
                    </ol>
                </nav>

                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                <?php endif; ?>

                <!-- Header de la playlist -->
                <div class="playlist-header card dashboard-card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <!-- Imagen de la playlist -->
                                <div class="playlist-cover-large">                                    <?php if($playlist->cover): ?>
                                        <img src="<?php echo e(asset('storage/' . $playlist->cover)); ?>" 
                                             alt="<?php echo e($playlist->name); ?>" 
                                             class="playlist-header-cover"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="playlist-header-cover d-none align-items-center justify-content-center">
                                            <i class="bi bi-music-note-beamed"></i>
                                        </div>
                                    <?php else: ?>
                                        <div class="playlist-header-cover d-flex align-items-center justify-content-center">
                                            <i class="bi bi-music-note-beamed"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col">
                                <!-- Info de la playlist -->
                                <div class="playlist-header-info">
                                    <span class="badge bg-secondary mb-2">PLAYLIST</span>
                                    <h1 class="playlist-title-large"><?php echo e($playlist->name); ?></h1>
                                    
                                    <?php if($playlist->description): ?>
                                        <p class="playlist-description-large text-muted"><?php echo e($playlist->description); ?></p>
                                    <?php endif; ?>
                                    
                                    <div class="playlist-meta-large">
                                        <span class="me-3">
                                            <img src="<?php echo e($playlist->user->profile_photo_url); ?>" 
                                                 class="rounded-circle me-2 user-photo-small" 
                                                 alt="<?php echo e($playlist->user->name); ?>">
                                            <?php echo e($playlist->user->username); ?>

                                        </span>
                                        <span class="me-3">
                                            <i class="bi bi-music-note me-1"></i>
                                            <?php echo e($playlist->songs->count()); ?> canciones
                                        </span>
                                        <span class="me-3">
                                            <i class="bi bi-<?php echo e($playlist->is_public ? 'globe' : 'lock'); ?> me-1"></i>
                                            <?php echo e($playlist->is_public ? 'Pública' : 'Privada'); ?>

                                        </span>
                                        <span class="text-light">
                                            Creada <?php echo e($playlist->created_at->diffForHumans()); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <!-- Acciones (solo para el propietario) -->
                                <?php if(Auth::check() && Auth::id() === $playlist->user_id): ?>
                                    <div class="playlist-actions-header">
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-options-large btn-purple" type="button" 
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <button class="dropdown-item" onclick="toggleSpotifySearch()">
                                                        <i class="bi bi-plus me-2"></i>Agregar canciones
                                                    </button>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="<?php echo e(route('playlists.edit', $playlist)); ?>">
                                                        <i class="bi bi-pencil me-2"></i>Editar playlist
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="<?php echo e(route('playlists.destroy', $playlist)); ?>" 
                                                          method="POST" 
                                                          onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta playlist?')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bi bi-trash me-2"></i>Eliminar playlist
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buscar canciones en Spotify (inicialmente oculto) - Solo para el propietario -->
                <?php if(Auth::check() && Auth::id() === $playlist->user_id): ?>
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
                                <div class="col-md-2">
                                    <button class="btn btn-purple w-100" onclick="searchSpotify()">
                                        <i class="bi bi-search me-2"></i>
                                        Buscar
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-purple w-100" id="saveChangesBtn" onclick="saveChanges()">
                                        <i class="bi bi-check-circle me-2"></i>
                                        <span class="d-none d-lg-inline">Guardar</span>
                                    </button>
                                </div>
                            </div>
                            
                            <div id="searchResults" class="mt-4 search-results-container"></div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Lista de canciones -->
                <div class="card dashboard-card">
                    <div class="card-body">
                        <?php if($playlist->songs->count() > 0): ?>
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
                                
                                <?php $__currentLoopData = $playlist->songs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $song): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="song-row">
                                        <div class="song-number">
                                            <span class="song-index"><?php echo e($index + 1); ?></span>
                                            <button class="btn-play-song" 
                                                    onclick="openSpotify('<?php echo e($song->spotify_url); ?>')" 
                                                    title="Abrir en Spotify"
                                                    <?php echo e(!$song->spotify_url ? 'disabled style="opacity: 0.3; cursor: not-allowed;" title="No disponible en Spotify"' : ''); ?>>
                                                <i class="bi bi-spotify"></i>
                                            </button>
                                        </div>
                                        <div class="song-title">
                                            <div class="song-info">
                                                <?php if($song->album_image): ?>
                                                    <img src="<?php echo e($song->album_image); ?>" 
                                                         alt="<?php echo e($song->title); ?>" 
                                                         class="song-cover-img"
                                                         onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none'); this.nextElementSibling.classList.add('d-flex');">
                                                    <div class="song-cover-placeholder d-none align-items-center justify-content-center">
                                                        <i class="bi bi-music-note"></i>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="song-cover-placeholder d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-music-note"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="song-name"><?php echo e($song->title ?: $song->name ?: 'Canción sin título'); ?></div>
                                                    <div class="song-artist"><?php echo e($song->artist ?: $song->author ?: 'Artista desconocido'); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="song-album d-none d-md-block">
                                            <?php echo e($song->album ?? 'Desconocido'); ?>

                                        </div>
                                        <div class="song-duration">
                                            <?php echo e($song->duration_formatted ?? $song->duration ?? '--:--'); ?>

                                        </div>
                                        <div class="song-actions">
                                            <!-- Solo mostrar acciones si es el propietario -->
                                            <?php if(Auth::check() && Auth::id() === $playlist->user_id): ?>
                                                <form action="<?php echo e(route('playlists.songs.remove', [$playlist, $song])); ?>" 
                                                      method="POST" 
                                                      style="margin: 0;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-delete-song" 
                                                            title="Quitar de la playlist">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <!-- Estado vacío -->
                            <div class="text-center py-5">
                                <i class="bi bi-music-note display-1 text-muted mb-3"></i>
                                <h4 class="text-muted mb-3">
                                    <?php if(Auth::check() && Auth::id() === $playlist->user_id): ?>
                                        Tu playlist está vacía
                                    <?php else: ?>
                                        Esta playlist está vacía
                                    <?php endif; ?>
                                </h4>
                                <p class="text-muted mb-4">
                                    <?php if(Auth::check() && Auth::id() === $playlist->user_id): ?>
                                        Busca y agrega tus canciones favoritas para comenzar a disfrutar tu música
                                    <?php else: ?>
                                        El propietario de esta playlist aún no ha agregado canciones
                                    <?php endif; ?>
                                </p>
                                <?php if(Auth::check() && Auth::id() === $playlist->user_id): ?>
                                    <button class="btn btn-primary-playlist" onclick="toggleSpotifySearch()">
                                        <i class="bi bi-search me-2"></i>
                                        Buscar canciones
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
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
                
                // Limpiar búsqueda y canciones pendientes cuando se cancela
                clearPendingSongs();
            }
        }

        function clearPendingSongs() {
            pendingSongs.clear();
            allSearchedTracks = {};
            document.getElementById('searchInput').value = '';
            document.getElementById('searchResults').innerHTML = '';
            updatePendingSongsIndicator();
        }

        function searchSpotify() {
            const query = document.getElementById('searchInput').value.trim();
            if (!query) {
                alert('Por favor, ingresa un término de búsqueda');
                return;
            }

            const resultsDiv = document.getElementById('searchResults');
            resultsDiv.innerHTML = '<div class="text-center p-4 dashboard-card loading-container"><div class="spinner-border" role="status"></div><div class="mt-2 text-white">Buscando canciones...</div></div>';

            fetch(`<?php echo e(route('playlists.search')); ?>?q=${encodeURIComponent(query)}`)
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
                // Guardar información del track para uso posterior
                allSearchedTracks[track.id] = track;
                
                // Verificar si la canción ya está agregada (en playlist o pendiente)
                const isInPlaylist = existingSongsInPlaylist.has(track.id);
                const isPending = pendingSongs.has(track.id);
                const isAdded = isInPlaylist || isPending;
                const buttonClass = isAdded ? 'btn-success' : 'btn-primary-playlist';
                const buttonText = isAdded ? 
                    '<i class="bi bi-check-circle me-1"></i><span class="d-none d-sm-inline">Agregada</span>' : 
                    '<i class="bi bi-plus-circle me-1"></i><span class="d-none d-sm-inline">Agregar</span>';
                
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
                            <button class="btn btn-sm spotify-btn me-2" onclick="window.open('${track.external_urls.spotify}', '_blank')" title="Abrir en Spotify">
                                <i class="bi bi-spotify" style="color: white;"></i>
                            </button>
                            <button class="btn btn-sm ${buttonClass} add-song-btn fixed-size-btn" 
                                    onclick="togglePlaylistSong('${track.id}')"
                                    data-track-id="${track.id}">
                                ${buttonText}
                            </button>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            resultsDiv.innerHTML = html;
        }

        // Variables globales para tracking de canciones pendientes y existentes
        let pendingSongs = new Set();
        let allSearchedTracks = {};
        let existingSongsInPlaylist = new Set();

        // Cargar canciones existentes en la playlist
        <?php if($playlist->songs->count() > 0): ?>
            <?php $__currentLoopData = $playlist->songs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $song): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($song->spotify_id): ?>
                    existingSongsInPlaylist.add('<?php echo e($song->spotify_id); ?>');
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

        function togglePlaylistSong(spotifyId) {
            const button = event.target.closest('.add-song-btn');
            const track = allSearchedTracks[spotifyId];
            
            // Verificar el estado actual de la canción
            const isInPlaylist = existingSongsInPlaylist.has(spotifyId);
            const isPending = pendingSongs.has(spotifyId);
            const isCurrentlyAdded = button.classList.contains('btn-success');
            
            if (isCurrentlyAdded) {
                // Si está marcada como agregada, permitir removerla
                removeSongFromPlaylist(spotifyId, button);
            } else {
                // Agregar a la playlist
                addSongToPlaylist(spotifyId, button);
            }
        }

        function addSongToPlaylist(spotifyId, button) {
            // Verificar si ya está en la playlist
            if (existingSongsInPlaylist.has(spotifyId)) {
                return; // No hacer nada si ya está en la playlist
            }
            
            // Agregar a la lista de canciones pendientes
            pendingSongs.add(spotifyId);
            
            // Actualizar el botón visualmente
            button.innerHTML = '<i class="bi bi-check-circle me-1"></i><span class="d-none d-sm-inline">Agregada</span>';
            button.classList.remove('btn-primary-playlist');
            button.classList.add('btn-success');
            
            // Guardar inmediatamente en la base de datos
            saveToDatabase(spotifyId, 'add');
        }

        function removeSongFromPlaylist(spotifyId, button) {
            // Remover de la lista de canciones pendientes si estaba ahí
            pendingSongs.delete(spotifyId);
            
            // Actualizar el botón visualmente
            button.innerHTML = '<i class="bi bi-plus-circle me-1"></i><span class="d-none d-sm-inline">Agregar</span>';
            button.classList.remove('btn-success');
            button.classList.add('btn-primary-playlist');
            
            // Remover inmediatamente de la base de datos
            saveToDatabase(spotifyId, 'remove');
        }

        function saveToDatabase(spotifyId, action) {
            const route = action === 'add' ? 
                `<?php echo e(route('playlists.songs.add', $playlist)); ?>` : 
                `<?php echo e(route('playlists.songs.removeBySpotifyId', $playlist)); ?>`;
            
            fetch(route, {
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
                    // Si la operación fue exitosa, actualizar las listas correspondientes
                    if (action === 'add') {
                        existingSongsInPlaylist.add(spotifyId);
                        pendingSongs.delete(spotifyId); // Mover de pendiente a existente
                    } else {
                        existingSongsInPlaylist.delete(spotifyId);
                        pendingSongs.delete(spotifyId);
                    }
                } else {
                    // Si hay error, revertir el cambio visual
                    const button = document.querySelector(`[data-track-id="${spotifyId}"]`);
                    if (action === 'add') {
                        button.innerHTML = '<i class="bi bi-plus-circle me-1"></i><span class="d-none d-sm-inline">Agregar</span>';
                        button.classList.remove('btn-success');
                        button.classList.add('btn-primary-playlist');
                        pendingSongs.delete(spotifyId);
                    } else {
                        button.innerHTML = '<i class="bi bi-check-circle me-1"></i><span class="d-none d-sm-inline">Agregada</span>';
                        button.classList.remove('btn-primary-playlist');
                        button.classList.add('btn-success');
                    }
                    
                    // Mostrar mensaje de error en console en lugar de alert
                    console.error('Error:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Error se muestra en console en lugar de alert
            });
        }

        // Función simplificada ya que no usamos el indicador visual
        function updatePendingSongsIndicator() {
            // Esta función se mantiene por compatibilidad pero ya no muestra indicadores
            return;
        }

        function saveChanges() {
            // Mostrar estado de carga
            const saveBtn = document.getElementById('saveChangesBtn');
            const originalContent = saveBtn.innerHTML;
            
            saveBtn.innerHTML = '<i class="bi bi-arrow-repeat spin me-2"></i>Aplicando cambios...';
            saveBtn.disabled = true;
            
            // Esperar un momento para mostrar el estado de carga y luego recargar
            setTimeout(() => {
                location.reload();
            }, 800);
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





<?php /**PATH C:\laragon\www\staytuned\resources\views/playlists/show.blade.php ENDPATH**/ ?>