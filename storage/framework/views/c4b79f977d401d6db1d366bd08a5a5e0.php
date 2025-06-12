<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Nueva Publicación en <?php echo e($community->name); ?> | StayTuned</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="<?php echo e(asset('css/dashboard.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/playlists.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/navbar-fix.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/posts.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/community-fixed.css')); ?>" rel="stylesheet">
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
            <a class="navbar-brand text-white fw-bold" href="<?php echo e(url('dashboard')); ?>">StayTuned</a>
        </div>

        <div class="d-none d-lg-flex ms-auto align-items-center gap-3">
            <a href="<?php echo e(route('dashboard')); ?>" class="nav-link-inline">Dashboard</a>
            <a href="<?php echo e(route('explore.users.index')); ?>" class="nav-link-inline">Explorar usuarios</a>
            <a href="<?php echo e(route('playlists.index')); ?>" class="nav-link-inline">Mis playlists</a>
            <a href="<?php echo e(route('posts.index')); ?>" class="nav-link-inline">Mis Publicaciones</a>
            <a href="<?php echo e(route('communities.index')); ?>" class="nav-link-inline active">Mis Comunidades</a>

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

    <!-- Offcanvas para móvil -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title text-white">StayTuned</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <nav class="nav flex-column">
                <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
                <a class="nav-link" href="<?php echo e(route('explore.users.index')); ?>">
                    <i class="fas fa-users me-2"></i> Explorar usuarios
                </a>
                <a class="nav-link" href="<?php echo e(route('playlists.index')); ?>">
                    <i class="fas fa-music me-2"></i> Mis playlists
                </a>
                <a class="nav-link" href="<?php echo e(route('posts.index')); ?>">
                    <i class="fas fa-newspaper me-2"></i> Mis Publicaciones
                </a>
                <a class="nav-link active" href="<?php echo e(route('communities.index')); ?>">
                    <i class="fas fa-users me-2"></i> Mis Comunidades
                </a>
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

    <!-- Contenido principal -->
    <div class="dashboard-container container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('communities.show', $community)); ?>" class="text-white-50">
                                <i class="bi bi-people me-1"></i>
                                <?php echo e($community->name); ?>

                            </a>
                        </li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Nueva Publicación</li>
                    </ol>
                </nav>

                <!-- Community Info Banner -->
                <div class="mb-4 community-card">
                    <div class="d-flex align-items-center">
                        <?php if($community->cover_image): ?>
                            <img src="<?php echo e(asset('storage/' . $community->cover_image)); ?>" 
                                 alt="<?php echo e($community->name); ?>" 
                                 class="rounded me-3" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded me-3 d-flex align-items-center justify-content-center" 
                                 style="width: 60px; height: 60px; background: rgba(255,255,255,0.10);">
                                <i class="fas fa-users" style="color: #fff;"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h5 class="mb-1" style="color: #fff;">Publicando en <?php echo e($community->name); ?></h5>
                            <small style="color: #e0e0e0;">
                                <?php echo e($community->members_count); ?> miembros • 
                                <?php echo e($community->is_private ? 'Privada' : 'Pública'); ?>

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
                        <?php echo $__env->make('components.content-moderation-alert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('posts.store')); ?>" method="POST" class="playlist-form">
                            <?php echo csrf_field(); ?>
                            <!-- Campo oculto para la comunidad -->
                            <input type="hidden" name="community_id" value="<?php echo e($community->id); ?>">
                            
                            <div class="row">
                                <!-- Columna izquierda - Información principal -->
                                <div class="col-md-8 mb-4">
                                    <!-- Título -->
                                    <div class="mb-4">
                                        <label for="title" class="form-label">Título de la publicación</label>
                                        <input type="text" 
                                               class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="title" 
                                               name="title" 
                                               value="<?php echo e(old('title')); ?>" 
                                               placeholder="¿Qué quieres compartir con la comunidad?"
                                               required>
                                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                        <input type="hidden" id="category_id" name="category_id" value="<?php echo e(old('category_id')); ?>" required>
                                        
                                        <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                <a href="<?php echo e(route('communities.show', $community)); ?>" class="btn btn-secondary flex-fill">
                                    <i class="bi bi-arrow-left me-2"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary-playlist flex-fill" id="submitBtn">
                                    <i class="bi bi-plus-lg me-2"></i> Publicar en Comunidad
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
        </div>
    </div>

    <!-- Modal de selección de categorías -->
    <div id="categoryModal" class="category-modal">
        <div class="category-modal-content">
            <div class="category-modal-header">
                <h5 class="category-modal-title">Selecciona el tipo de publicación</h5>
                <button type="button" class="category-modal-close" id="closeCategoryModal">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <div class="category-options">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="category-option" 
                         data-value="<?php echo e($category->id); ?>" 
                         data-type="<?php echo e($category->type); ?>"
                         data-text="<?php echo e($category->text); ?>">
                        <div>
                            <div class="fw-semibold"><?php echo e(ucfirst($category->type)); ?></div>
                            <small class="text-muted"><?php echo e($category->text); ?></small>
                        </div>
                        <div class="category-option-check">
                            <i class="bi bi-check" style="display: none;"></i>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedSpotifyItem = null;
        let currentSearchType   = 'track';

        // Mapeo de tipos (minúsculas, sin acentos)
        const categoryToSpotifyType = {
            cancion : 'track',
            album   : 'album',
            artista : 'artist',
            playlist: 'playlist'
        };

        // Normaliza string: minúsculas + quita acentos
        function normalizeType(str) {
            return str
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '');
        }

        // Actualiza el contenido descriptivo
        function updateCategoryContent() {
            const idInput = document.getElementById('category_id');
            const out     = document.getElementById('category_content');

            if (!idInput.value) {
                out.innerHTML = '<em class="text-muted">Selecciona un tipo de publicación para ver el contenido...</em>';
                return;
            }
            const opt     = document.querySelector(`[data-value="${idInput.value}"]`);
            const txt     = opt.getAttribute('data-text');
            const rawType = opt.getAttribute('data-type');
            const norm    = normalizeType(rawType);

            out.innerHTML = `
                <div class="d-flex align-items-start">
                    <i class="bi bi-quote text-primary me-2 fs-5"></i>
                    <div>
                        <p class="mb-2">"${txt}"</p>
                        <small class="text-muted">
                            Tu publicación compartirá una <strong>${norm}</strong> de Spotify con este mensaje.
                        </small>
                    </div>
                </div>
            `;
        }

        // Actualiza sección Spotify y placeholder
        function updateSpotifySearch() {
            const idInput         = document.getElementById('category_id');
            const secSpotify      = document.getElementById('spotify-section');
            const secNoSpotify    = document.getElementById('no-spotify-section');
            const spotifySearch   = document.getElementById('spotifySearch');
            const spotifySearchText = document.getElementById('spotify-search-text');

            if (!idInput.value) {
                secSpotify.style.display   = 'none';
                secNoSpotify.style.display = 'block';
                clearSelection();
                return;
            }

            const opt     = document.querySelector(`[data-value="${idInput.value}"]`);
            const rawType = opt.getAttribute('data-type');
            const norm    = normalizeType(rawType);

            console.log('rawType =', rawType, '→ norm =', norm);
            currentSearchType = categoryToSpotifyType[norm] || 'track';
            console.log('currentSearchType =', currentSearchType);

            secSpotify.style.display   = 'block';
            secNoSpotify.style.display = 'none';

            const placeholderMap = {
                track   : 'canciones',
                album   : 'álbumes',
                artist  : 'artistas',
                playlist: 'playlists'
            };

            spotifySearchText.textContent   = `Buscar ${placeholderMap[currentSearchType]} en Spotify`;
            spotifySearch.placeholder       = `Buscar ${placeholderMap[currentSearchType]}...`;

            clearSelection();
        }

        // Ejecuta búsqueda en Spotify
        async function searchSpotify() {
            const q = document.getElementById('spotifySearch').value.trim();
            if (!q) return;
            const url = new URL('/posts/search/spotify', window.location.origin);
            url.searchParams.append('query', q);
            url.searchParams.append('type',  currentSearchType);
            const res  = await fetch(url, {
                headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content}
            });
            const data = await res.json().catch(() => ({}));
            displayResults(data.results || [], currentSearchType);
        }

        // Muestra resultados
        function displayResults(items, type) {
            const rd = document.getElementById('spotifyResults');
            if (!items.length) {
                rd.innerHTML     = '<p class="text-white-50 small">No se encontraron resultados</p>';
                rd.style.display = 'block';
                return;
            }
            let html = '<div class="spotify-results-list">';
            items.forEach(item => {
                const name = item.name || item.album?.name || 'Sin título';
                let subtitle = '';
                if (type==='track' || type==='album') subtitle = item.artists?.map(a=>a.name).join(', ') || '';
                if (type==='artist') subtitle = `${item.followers?.total.toLocaleString()||0} seguidores`;
                if (type==='playlist') subtitle = `${item.tracks?.total||0} canciones`;
                const img = item.images?.[0]?.url || item.album?.images?.[0]?.url || '';
                html += `
                    <div class="spotify-result-item" onclick='selectItem(${JSON.stringify(item)}, "${type}")'>
                        <div class="d-flex align-items-center p-2">
                            <i class="bi bi-spotify text-success me-2"></i>
                            ${ img
                                ? `<img src="${img}" class="me-2" style="width:40px;height:40px;border-radius:4px;object-fit:cover;">`
                                : ''
                            }
                            <div class="flex-grow-1">
                                <div class="fw-bold text-white small">${name}</div>
                                <div class="text-white-50 small">${subtitle}</div>
                            </div>
                        </div>
                    </div>
                `;
            });
            rd.innerHTML     = html + '</div>';
            rd.style.display = 'block';
        }

        // Selecciona item
        function selectItem(item, type) {
            document.getElementById('spotify_id').value           = item.id;
            document.getElementById('spotify_type').value         = type;
            document.getElementById('spotify_external_url').value = item.external_urls?.spotify || '';
            document.getElementById('spotify_data').value         = JSON.stringify(item);

            const name = item.name || item.album?.name || 'Sin título';
            let subtitle = '';
            if (type==='track'||type==='album') subtitle = item.artists?.map(a=>a.name).join(', ')||'';
            if (type==='artist') subtitle = `${item.followers?.total.toLocaleString()||0} seguidores`;
            if (type==='playlist') subtitle = `${item.tracks?.total||0} canciones`;
            const img = item.images?.[0]?.url || item.album?.images?.[0]?.url || '';

            const preview = document.getElementById('spotifyPreview');
            preview.className = 'spotify-preview has-content';
            preview.innerHTML = `
                <div class="spotify-preview-content">
                    ${ img
                        ? `<img src="${img}" class="spotify-preview-image" alt="${name}">`
                        : `<div class="spotify-preview-image d-flex align-items-center justify-content-center" style="background:rgba(255,255,255,0.1);"><i class="bi bi-spotify text-success"></i></div>`
                    }
                    <div class="spotify-preview-info">
                        <div class="spotify-preview-title">${name}</div>
                        <div class="spotify-preview-subtitle">${subtitle}</div>
                    </div>
                </div>
            `;
            document.getElementById('selectedSpotifyContent').innerHTML = preview.innerHTML;
            document.getElementById('selectedSpotify').style.display  = 'block';
            document.getElementById('spotifyResults').style.display   = 'none';
        }

        // Limpia selección
        function clearSelection() {
            selectedSpotifyItem = null;
            ['spotify_id','spotify_type','spotify_external_url','spotify_data']
                .forEach(id => document.getElementById(id).value = '');
            document.getElementById('selectedSpotify').style.display = 'none';

            const txt     = document.getElementById('spotify-search-text').textContent;
            const preview = document.getElementById('spotifyPreview');
            preview.className = 'spotify-preview';
            preview.innerHTML = `
                <div class="spotify-placeholder">
                    <i class="bi bi-spotify fs-1"></i>
                    <p class="mt-2 mb-0">${txt}</p>
                </div>
            `;
        }

        // Inicialización
        document.addEventListener('DOMContentLoaded', () => {
            // Buscar con Enter
            document.getElementById('spotifySearch').addEventListener('keypress', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchSpotify();
                }
            });
            // Validación formulario
            document.querySelector('form').addEventListener('submit', e => {
                const cat = document.getElementById('category_id').value;
                const sp  = document.getElementById('spotify_id').value;
                const vm  = document.getElementById('validation-message');
                const vt  = document.getElementById('validation-text');

                if (!cat) {
                    e.preventDefault();
                    vt.textContent = 'Por favor, selecciona un tipo de publicación.';
                    vm.style.display = 'block';
                    return;
                }
                if (!sp) {
                    e.preventDefault();
                    vt.textContent = 'Por favor, selecciona contenido de Spotify para tu publicación.';
                    vm.style.display = 'block';
                    return;
                }
                vm.style.display = 'none';
            });

            // Modal categorías
            const modal    = document.getElementById('categoryModal');
            const btnOpen  = document.getElementById('categorySelector');
            const btnClose = document.getElementById('closeCategoryModal');
            const opts     = document.querySelectorAll('.category-option');
            const hid      = document.getElementById('category_id');
            const dispTxt  = document.getElementById('categorySelectedText');

            function closeCategoryModalFunc() {
                modal.classList.remove('show');
                document.body.classList.remove('modal-open');
            }

            btnOpen.addEventListener('click', () => {
                modal.classList.add('show');
                document.body.classList.add('modal-open');
            });
            btnClose.addEventListener('click', closeCategoryModalFunc);
            modal.addEventListener('click', e => {
                if (e.target === modal) closeCategoryModalFunc();
            });
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && modal.classList.contains('show')) {
                    closeCategoryModalFunc();
                }
            });

            opts.forEach(opt => {
                opt.addEventListener('click', function() {
                    // desmarca todas
                    opts.forEach(o => {
                        o.classList.remove('selected');
                        o.querySelector('.bi-check').style.display = 'none';
                    });
                    // marca esta
                    this.classList.add('selected');
                    this.querySelector('.bi-check').style.display = 'block';
                    // cierra modal de inmediato
                    closeCategoryModalFunc();
                    // guarda valor y texto
                    const val = this.getAttribute('data-value');
                    const txt = this.querySelector('.fw-semibold').textContent;
                    hid.value = val;
                    dispTxt.textContent = txt;
                    btnOpen.classList.add('selected');
                    // actualiza contenido y Spotify
                    updateCategoryContent();
                    updateSpotifySearch();
                });
            });

            // si ya hay categoría al recargar
            if (hid.value) {
                const pre = document.querySelector(`[data-value="${hid.value}"]`);
                if (pre) {
                    pre.classList.add('selected');
                    pre.querySelector('.bi-check').style.display = 'block';
                    dispTxt.textContent = pre.querySelector('.fw-semibold').textContent;
                    btnOpen.classList.add('selected');
                }
            }
        });
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\staytuned\resources\views/communities/create-post.blade.php ENDPATH**/ ?>