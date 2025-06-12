<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | StayTuned</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/dashboard.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/posts.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/community-fixed.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/navbar-fix.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
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
            <a href="<?php echo e(route('dashboard')); ?>" 
               class="nav-link-inline <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                Dashboard
            </a>
            <a href="<?php echo e(route('explore.users.index')); ?>" 
               class="nav-link-inline <?php echo e(request()->routeIs('explore.users.*') ? 'active' : ''); ?>">
                Explorar usuarios
            </a>
            <a href="<?php echo e(route('playlists.index')); ?>" 
               class="nav-link-inline <?php echo e(request()->routeIs('playlists.*') ? 'active' : ''); ?>">
                Mis playlists
            </a>
            <a href="<?php echo e(route('posts.index')); ?>" 
               class="nav-link-inline <?php echo e(request()->routeIs('posts.*') ? 'active' : ''); ?>">
                Mis Publicaciones
            </a>
            <a href="<?php echo e(route('communities.index')); ?>" 
               class="nav-link-inline <?php echo e(request()->routeIs('communities.*') ? 'active' : ''); ?>">
                Mis comunidades
            </a>

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
                <a class="nav-link active" href="<?php echo e(route('dashboard')); ?>">
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
                <a class="nav-link" href="<?php echo e(route('communities.index')); ?>">
                    <i class="fas fa-users me-2"></i> Mis comunidades
                </a>
            </nav>
            <hr class="my-0">
            <nav class="nav flex-column">
                <a class="nav-link d-flex align-items-center" href="<?php echo e(route('profile.settings')); ?>">
                    <i class="bi bi-person me-2"></i> Perfil
                </a>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-link d-flex align-items-center">
                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                    </button>
                </form>
            </nav>
        </div>
    </div>

    <main class="dashboard-container container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                
                <!-- Header del Dashboard -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>                        <h1 class="text-white mb-1">
                            Dashboard
                        </h1>
                        <p class="text-light mb-0">Mantente al día con las publicaciones de tus seguidos y comunidades</p>
                    </div>
                </div>

                <!-- Estadísticas rápidas -->
                <div class="row mb-5">
                    <div class="col-md-4 mb-3">
                        <div class="card dashboard-card">
                            <div class="card-body text-center">
                                <i class="fas fa-user-friends fs-1 text-primary mb-2"></i>
                                <h5 class="card-title text-white">Siguiendo</h5>
                                <p class="card-text fs-4 fw-bold text-white"><?php echo e($stats['following_count'] ?? 0); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card dashboard-card">
                            <div class="card-body text-center">
                                <i class="fas fa-users fs-1 text-success mb-2"></i>
                                <h5 class="card-title text-white">Mis Comunidades</h5>
                                <p class="card-text fs-4 fw-bold text-white"><?php echo e($stats['communities_count'] ?? 0); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card dashboard-card">
                            <div class="card-body text-center">
                                <i class="fas fa-newspaper fs-1 text-info mb-2"></i>
                                <h5 class="card-title text-white">Mis Publicaciones</h5>
                                <p class="card-text fs-4 fw-bold text-white"><?php echo e($stats['user_posts'] ?? 0); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de Publicaciones de Seguidos -->
                <div class="mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="text-white mb-0">
                                Publicaciones Recientes de Seguidos
                            </h2>
                            <small class="text-light">Últimas 24 horas</small>
                        </div>
                        <a href="<?php echo e(route('explore.users.index')); ?>" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-search me-1"></i>
                            Explorar más usuarios
                        </a>
                    </div>

                    <div id="following-posts-content">
                        <?php echo $__env->make('dashboard.partials.following-posts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>

                <!-- Sección de Publicaciones de Comunidades -->
                <div class="mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="text-white mb-0">
                                Publicaciones Recientes de Mis Comunidades
                            </h2>
                            <small class="text-light">Últimas 24 horas</small>
                        </div>
                        <a href="<?php echo e(route('communities.index')); ?>" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-users me-1"></i>
                            Ver mis comunidades
                        </a>
                    </div>

                    <div id="community-posts-content">
                        <?php echo $__env->make('dashboard.partials.community-posts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Estilos de paginación glassmorphism -->
    <style>
    .pagination-custom .page-link {
        background: rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(12px) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 8px !important;
        color: #fff !important;
        margin: 0 2px;
        transition: all 0.3s ease;
    }

    .pagination-custom .page-link:hover {
        background: rgba(255, 255, 255, 0.2) !important;
        border-color: rgba(255, 255, 255, 0.4) !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
    }

    .pagination-custom .page-item.active .page-link {
        background: rgba(138, 43, 226, 0.8) !important;
        border-color: rgba(138, 43, 226, 0.9) !important;
        box-shadow: 0 0 15px rgba(138, 43, 226, 0.3);
    }

    .pagination-custom .page-item.disabled .page-link {
        background: rgba(255, 255, 255, 0.05) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        color: rgba(255, 255, 255, 0.3) !important;
    }
    </style>

    <!-- JavaScript para paginación AJAX -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Función para convertir URL de paginación normal a AJAX
        function convertToAjaxUrl(originalUrl, contentType) {
            try {
                console.log('Convirtiendo URL:', originalUrl, 'para tipo:', contentType);
                
                // Extraer el número de página de diferentes formas posibles
                let page = 1;
                
                // Método 1: buscar en los parámetros de query
                const urlObj = new URL(originalUrl, window.location.origin);
                page = urlObj.searchParams.get(contentType + '_page') || 
                       urlObj.searchParams.get('page') || 1;
                
                // Método 2: buscar en el path si es una URL relativa con ?page=
                if (page === 1) {
                    const pageMatch = originalUrl.match(/[?&](?:page|following_page|community_page)=(\d+)/);
                    if (pageMatch) {
                        page = pageMatch[1];
                    }
                }
                
                // Método 3: para URLs específicas del dashboard
                if (page === 1) {
                    const pathMatch = originalUrl.match(/following_page=(\d+)|community_page=(\d+)/);
                    if (pathMatch) {
                        page = pathMatch[1] || pathMatch[2] || 1;
                    }
                }
                
                let ajaxUrl;
                if (contentType === 'following') {
                    ajaxUrl = `<?php echo e(route('dashboard.following-posts')); ?>?following_page=${page}`;
                } else if (contentType === 'community') {
                    ajaxUrl = `<?php echo e(route('dashboard.community-posts')); ?>?community_page=${page}`;
                } else {
                    ajaxUrl = originalUrl;
                }
                
                console.log('Página extraída:', page);
                console.log('URL convertida a:', ajaxUrl);
                return ajaxUrl;
            } catch (error) {
                console.error('Error al convertir URL:', error);
                return originalUrl;
            }
        }
        
        // Función para manejar la paginación AJAX
        function handleAjaxPagination() {
            document.addEventListener('click', function(e) {
                // Buscar cualquier enlace de paginación dentro de los contenedores específicos
                const followingLink = e.target.closest('#following-posts-content .page-link');
                const communityLink = e.target.closest('#community-posts-content .page-link');
                
                if (!followingLink && !communityLink) return;
                
                console.log('Clic en enlace de paginación detectado');
                console.log('Following link:', followingLink);
                console.log('Community link:', communityLink);
                
                e.preventDefault();
                
                const target = followingLink || communityLink;
                const originalUrl = target.getAttribute('href');
                
                console.log('URL original:', originalUrl);
                
                if (!originalUrl || originalUrl === '#') {
                    console.log('URL inválida, cancelando');
                    return;
                }
                
                // Determinar el tipo de contenido y el contenedor
                let contentType, containerId, ajaxUrl;
                
                if (followingLink) {
                    contentType = 'following';
                    containerId = 'following-posts-content';
                    ajaxUrl = convertToAjaxUrl(originalUrl, 'following');
                } else {
                    contentType = 'community';
                    containerId = 'community-posts-content';
                    ajaxUrl = convertToAjaxUrl(originalUrl, 'community');
                }
                
                console.log('Tipo de contenido:', contentType);
                console.log('Contenedor:', containerId);
                console.log('URL AJAX final:', ajaxUrl);
                
                const container = document.getElementById(containerId);
                if (!container) {
                    console.error('Contenedor no encontrado:', containerId);
                    return;
                }
                
                // Mostrar indicador de carga
                container.style.opacity = '0.6';
                container.style.pointerEvents = 'none';
                
                // Realizar petición AJAX
                fetch(ajaxUrl, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.text();
                })
                .then(html => {
                    // Actualizar el contenido
                    container.innerHTML = html;
                    
                    // Restaurar estilos
                    container.style.opacity = '1';
                    container.style.pointerEvents = 'auto';
                    
                    // Configurar nuevamente los enlaces de paginación
                    setupPaginationLinks();
                    
                    // Hacer scroll suave hacia el contenido actualizado
                    container.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                })
                .catch(error => {
                    console.error('Error en la paginación:', error);
                    
                    // Restaurar estilos en caso de error
                    container.style.opacity = '1';
                    container.style.pointerEvents = 'auto';
                    
                    // Mostrar mensaje de error
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger mt-3';
                    errorDiv.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Error al cargar el contenido. Por favor, intenta de nuevo.';
                    container.appendChild(errorDiv);
                    
                    // Remover mensaje de error después de 5 segundos
                    setTimeout(() => {
                        if (errorDiv.parentNode) {
                            errorDiv.remove();
                        }
                    }, 5000);
                });
            });
        }
        
        // Función para configurar todos los enlaces de paginación
        function setupPaginationLinks() {
            // Configurar paginación de publicaciones de seguidos
            const followingPagination = document.querySelector('#following-posts-content .pagination');
            if (followingPagination) {
                followingPagination.querySelectorAll('.page-link').forEach(link => {
                    const originalUrl = link.getAttribute('href');
                    if (originalUrl && originalUrl !== '#') {
                        // Marcar como configurado para AJAX
                        link.dataset.ajaxConfigured = 'true';
                    }
                });
            }
            
            // Configurar paginación de publicaciones de comunidades
            const communityPagination = document.querySelector('#community-posts-content .pagination');
            if (communityPagination) {
                communityPagination.querySelectorAll('.page-link').forEach(link => {
                    const originalUrl = link.getAttribute('href');
                    if (originalUrl && originalUrl !== '#') {
                        // Marcar como configurado para AJAX
                        link.dataset.ajaxConfigured = 'true';
                    }
                });
            }
        }
        
        // Función para observar cambios en el DOM y reconfigurar enlaces
        function observeContentChanges() {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList') {
                        // Reconfigurar enlaces cuando el contenido cambie
                        setTimeout(setupPaginationLinks, 100);
                    }
                });
            });
            
            // Observar ambos contenedores
            const followingContainer = document.getElementById('following-posts-content');
            const communityContainer = document.getElementById('community-posts-content');
            
            if (followingContainer) {
                observer.observe(followingContainer, { childList: true, subtree: true });
            }
            
            if (communityContainer) {
                observer.observe(communityContainer, { childList: true, subtree: true });
            }
        }
        
        // Inicializar todo
        handleAjaxPagination();
        setupPaginationLinks();
        observeContentChanges();
        
        // Configuración adicional después de un breve delay para asegurar que todo esté cargado
        setTimeout(() => {
            setupPaginationLinks();
        }, 500);
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\laragon\www\staytuned\resources\views/dashboard.blade.php ENDPATH**/ ?>