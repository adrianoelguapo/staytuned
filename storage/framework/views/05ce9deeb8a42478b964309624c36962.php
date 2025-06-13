<!DOCTYPE html>
<html lang = "es">
    <head>
        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width = device-width, initial-scale = 1.0">
        <title>Dashboard | StayTuned</title>

        <link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel = "stylesheet">
        <link href = "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel = "stylesheet">
        <link href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel = "stylesheet">

        <link href = "<?php echo e(asset('css/dashboard.css')); ?>" rel = "stylesheet">
        <link href = "<?php echo e(asset('css/posts.css')); ?>" rel = "stylesheet">
        <link href = "<?php echo e(asset('css/community-fixed.css')); ?>" rel = "stylesheet">
        <link href = "<?php echo e(asset('css/navbar-fix.css')); ?>?v = <?php echo e(time()); ?>" rel = "stylesheet">

    </head>

    <body class = "dashboard-body">

        <nav class = "navbar navbar-expand-lg px-5 py-3">

            <div class = "d-flex align-items-center">

                <button class = "btn btn-link btn-offcanvas me-3 p-0 d-lg-none" type = "button" data-bs-toggle = "offcanvas" data-bs-target = "#offcanvasMenu" aria-controls = "offcanvasMenu">

                    <i class = "bi bi-list text-white fs-3"></i>

                </button>

                <a class = "navbar-brand text-white fw-bold" href = "<?php echo e(url('dashboard')); ?>">StayTuned</a>

            </div>

            <div class = "d-none d-lg-flex ms-auto align-items-center gap-3">

                <a href = "<?php echo e(route('dashboard')); ?>"  class = "nav-link-inline <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">Dashboard</a>
                <a href = "<?php echo e(route('explore.users.index')); ?>" class = "nav-link-inline <?php echo e(request()->routeIs('explore.users.*') ? 'active' : ''); ?>">Explorar Usuarios</a>
                <a href = "<?php echo e(route('playlists.index')); ?>" class = "nav-link-inline <?php echo e(request()->routeIs('playlists.*') ? 'active' : ''); ?>">Mis Playlists</a>
                <a href = "<?php echo e(route('posts.index')); ?>" class = "nav-link-inline <?php echo e(request()->routeIs('posts.*') ? 'active' : ''); ?>">Mis Publicaciones</a>
                <a href = "<?php echo e(route('communities.index')); ?>" class = "nav-link-inline <?php echo e(request()->routeIs('communities.*') ? 'active' : ''); ?>">Mis Comunidades</a>

                <div class = "dropdown">

                    <a class = "d-flex align-items-center text-white dropdown-toggle nav-link-inline" href = "#" id = "userDropdown" role = "button" data-bs-toggle = "dropdown" aria-expanded = "false">

                        <?php if(Laravel\Jetstream\Jetstream::managesProfilePhotos()): ?>

                            <img src = "<?php echo e(Auth::user()->profile_photo_url); ?>" class = "rounded-circle me-2 user-photo" alt = "<?php echo e(Auth::user()->name); ?>"/>

                        <?php endif; ?>

                        <?php echo e(Auth::user()->username); ?>


                    </a>

                    <ul class = "dropdown-menu dropdown-menu-end">

                        <li>

                            <a class = "dropdown-item d-flex align-items-center" href = "<?php echo e(route('profile.settings')); ?>">

                                <i class = "bi bi-person me-2"></i> Perfil

                            </a>

                        </li>

                        <li>

                            <hr class = "dropdown-divider">

                        </li>

                        <li>

                            <form method = "POST" action = "<?php echo e(route('logout')); ?>">

                                <?php echo csrf_field(); ?>
                                <button type = "submit" class = "dropdown-item d-flex align-items-center text-danger">

                                    <i class = "bi bi-box-arrow-right me-2"></i> Cerrar sesión

                                </button>

                            </form>

                        </li>

                    </ul>

                </div>

            </div>

        </nav>

        <div class = "offcanvas offcanvas-start" tabindex = "-1" id = "offcanvasMenu" aria-labelledby = "offcanvasMenuLabel">

            <div class = "offcanvas-header">

                <h5 class = "offcanvas-title text-white" id = "offcanvasMenuLabel">StayTuned</h5>
                <button type = "button" class = "btn-close btn-close-white" data-bs-dismiss = "offcanvas" aria-label = "Cerrar"></button>

            </div>

            <div class = "offcanvas-body d-flex flex-column p-0">

                <nav class = "nav flex-column">

                    <a class = "nav-link active" href = "<?php echo e(route('dashboard')); ?>">

                        <i class = "fas fa-home me-2"></i> Dashboard

                    </a>

                    <a class = "nav-link" href = "<?php echo e(route('explore.users.index')); ?>">

                        <i class = "fas fa-users me-2"></i> Explorar Usuarios

                    </a>

                    <a class = "nav-link" href = "<?php echo e(route('playlists.index')); ?>">

                        <i class = "fas fa-music me-2"></i> Mis Playlists

                    </a>

                    <a class = "nav-link" href = "<?php echo e(route('posts.index')); ?>">

                        <i class = "fas fa-newspaper me-2"></i> Mis Publicaciones

                    </a>

                    <a class = "nav-link" href = "<?php echo e(route('communities.index')); ?>">

                        <i class = "fas fa-users me-2"></i> Mis Comunidades

                    </a>

                </nav>

                <hr class = "my-0">

                <nav class = "nav flex-column">

                    <a class = "nav-link d-flex align-items-center" href = "<?php echo e(route('profile.settings')); ?>">

                        <i class = "bi bi-person me-2"></i> Perfil

                    </a>

                    <form method = "POST" action = "<?php echo e(route('logout')); ?>">

                        <?php echo csrf_field(); ?>

                        <button type = "submit" class = "btn-link d-flex align-items-center">

                            <i class = "bi bi-box-arrow-right me-2"></i> Cerrar sesión

                        </button>

                    </form>

                </nav>

            </div>

        </div>

        <main class = "dashboard-container container-fluid py-5">

            <div class = "row justify-content-center">

                <div class = "col-12 col-lg-10">
                    
                    <div class = "d-flex justify-content-between align-items-center mb-4 text-center text-md-start">

                        <div class = "w-100 w-md-auto">

                            <h1 class = "text-white mb-1">Dashboard</h1>
                            <p class = "text-light mb-0">Mantente al día con las publicaciones de tus seguidos y comunidades</p>

                        </div>

                    </div>

                    <div class = "row mb-5">

                        <div class = "col-md-4 mb-3">

                            <div class = "card dashboard-card">

                                <div class = "card-body text-center">

                                    <i class = "fas fa-user-friends fs-1 text-primary mb-2"></i>

                                    <h5 class = "card-title text-white">Siguiendo</h5>
                                    
                                    <p class = "card-text fs-4 fw-bold text-white"><?php echo e($stats['following_count'] ?? 0); ?></p>

                                </div>

                            </div>

                        </div>

                        <div class = "col-md-4 mb-3">

                            <div class = "card dashboard-card">

                                <div class = "card-body text-center">

                                    <i class = "fas fa-users fs-1 text-success mb-2"></i>

                                    <h5 class = "card-title text-white">Mis Comunidades</h5>

                                    <p class = "card-text fs-4 fw-bold text-white"><?php echo e($stats['communities_count'] ?? 0); ?></p>

                                </div>

                            </div>

                        </div>

                        <div class = "col-md-4 mb-3">

                            <div class = "card dashboard-card">

                                <div class = "card-body text-center">

                                    <i class = "fas fa-newspaper fs-1 text-info mb-2"></i>

                                    <h5 class = "card-title text-white">Mis Publicaciones</h5>

                                    <p class = "card-text fs-4 fw-bold text-white"><?php echo e($stats['user_posts'] ?? 0); ?></p>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class = "mb-5">

                        <div class = "d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">

                            <div class = "mb-3 mb-lg-0">

                                <h2 class = "text-white mb-0 dashboard-section-title">Publicaciones Recientes de Seguidos</h2>

                                <small class = "text-light">Últimas 24 horas</small>

                            </div>

                            <a href = "<?php echo e(route('explore.users.index')); ?>" class = "btn btn-outline-light btn-sm w-100 dashboard-action-btn">

                                <i class = "fas fa-search me-1"></i> Explorar Más Usuarios

                            </a>

                        </div>

                        <div id = "following-posts-content">

                            <?php echo $__env->make('dashboard.partials.following-posts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        </div>

                    </div>

                    <div class = "mb-5">

                        <div class = "d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">

                            <div class = "mb-3 mb-lg-0">

                                <h2 class = "text-white mb-0 dashboard-section-title">Publicaciones Recientes de Mis Comunidades</h2>

                                <small class = "text-light">Últimas 24 horas</small>

                            </div>

                            <a href = "<?php echo e(route('communities.index')); ?>" class = "btn btn-outline-light btn-sm w-100 dashboard-action-btn">

                                <i class = "fas fa-users me-1"></i> Ver Mis Comunidades

                            </a>

                        </div>

                        <div id = "community-posts-content">

                            <?php echo $__env->make('dashboard.partials.community-posts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        </div>

                    </div>

                </div>

            </div>

        </main>

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

            /* Estilos responsive para botones de acción del dashboard */
            @media (min-width: 992px) {
                .dashboard-action-btn {
                    max-width: 250px !important;
                    width: auto !important;
                }
            }

        </style>

        <div id = "dashboard-config" data-following-route = "<?php echo e(route('dashboard.following-posts')); ?>" data-community-route = "<?php echo e(route('dashboard.community-posts')); ?>" style = "display: none;"></div>

        <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <script src = "<?php echo e(asset('js/dashboard.js')); ?>?v = <?php echo e(time()); ?>"></script>

        <script>
            
            document.addEventListener('DOMContentLoaded', function() {
                const config  =  document.getElementById('dashboard-config');
                if (config && window.setDashboardRoutes) {
                    window.setDashboardRoutes(
                        config.dataset.followingRoute,
                        config.dataset.communityRoute
                    );
                }
            });

        </script>

    </body>

</html><?php /**PATH C:\laragon\www\staytuned\resources\views/dashboard.blade.php ENDPATH**/ ?>