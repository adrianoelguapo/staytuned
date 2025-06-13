<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'StayTuned'); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- CSS personalizados -->
    <link href="<?php echo e(asset('css/dashboard.css')); ?>?v=<?php echo e(now()->timestamp); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/playlists.css')); ?>?v=<?php echo e(now()->timestamp); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/mobile-responsive.css')); ?>?v=<?php echo e(now()->timestamp); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/profile.css')); ?>?v=<?php echo e(now()->timestamp); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/navbar-fix.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/offcanvas-fix.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
    <?php echo $__env->yieldPushContent('styles'); ?>
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
            <a class="navbar-brand text-white fw-bold" href="<?php echo e(route('dashboard')); ?>">StayTuned</a>
        </div>

        <!-- Enlaces + usuario: solo ≥lg -->
        <div class="d-none d-lg-flex ms-auto align-items-center gap-3">
            <a href="<?php echo e(route('dashboard')); ?>" 
               class="nav-link-inline <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                Dashboard
            </a>
            <a href="<?php echo e(route('explore.users.index')); ?>" 
               class="nav-link-inline <?php echo e(request()->routeIs('explore.users.*') ? 'active' : ''); ?>">
                Explorar Usuarios
            </a>
            <a href="<?php echo e(route('playlists.index')); ?>" 
               class="nav-link-inline <?php echo e(request()->routeIs('playlists.*') ? 'active' : ''); ?>">
                Mis Playlists
            </a>
            <a href="<?php echo e(route('posts.index')); ?>" 
               class="nav-link-inline <?php echo e(request()->routeIs('posts.*') ? 'active' : ''); ?>">
                Mis Publicaciones
            </a>
            <a href="<?php echo e(route('communities.index')); ?>" 
               class="nav-link-inline <?php echo e(request()->routeIs('communities.*') ? 'active' : ''); ?>">
                Mis Comunidades
                <?php if(isset($pendingCommunityRequests) && $pendingCommunityRequests > 0): ?>
                    <span class="badge bg-danger ms-1"><?php echo e($pendingCommunityRequests); ?></span>
                <?php endif; ?>
            </a>

            <!-- Dropdown de usuario -->
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
                             alt="<?php echo e(Auth::user()->name); ?>"
                             style="width: 32px; height: 32px; object-fit: cover;" />
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
                <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" 
                   href="<?php echo e(route('dashboard')); ?>">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
                <a class="nav-link <?php echo e(request()->routeIs('explore.users.*') ? 'active' : ''); ?>" 
                   href="<?php echo e(route('explore.users.index')); ?>">
                    <i class="fas fa-users me-2"></i> Explorar Usuarios
                </a>
                <a class="nav-link <?php echo e(request()->routeIs('playlists.*') ? 'active' : ''); ?>" 
                   href="<?php echo e(route('playlists.index')); ?>">
                    <i class="fas fa-music me-2"></i> Mis Playlists
                </a>
                <a class="nav-link <?php echo e(request()->routeIs('posts.*') ? 'active' : ''); ?>" 
                   href="<?php echo e(route('posts.index')); ?>">
                    <i class="fas fa-newspaper me-2"></i> Mis Publicaciones
                </a>
                <a class="nav-link <?php echo e(request()->routeIs('communities.*') ? 'active' : ''); ?>" 
                   href="<?php echo e(route('communities.index')); ?>">
                    <i class="fas fa-users me-2"></i> Mis Comunidades
                    <?php if(isset($pendingCommunityRequests) && $pendingCommunityRequests > 0): ?>
                        <span class="badge bg-danger ms-1"><?php echo e($pendingCommunityRequests); ?></span>
                    <?php endif; ?>
                </a>
            </nav>
            <hr class="my-0 border-secondary">
            <nav class="nav flex-column">
                <a class="nav-link d-flex align-items-center" href="<?php echo e(route('profile.settings')); ?>">
                    <i class="bi bi-person me-2"></i> Perfil
                </a>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                            class="nav-link btn btn-link d-flex align-items-center text-danger rounded-0">
                        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                    </button>
                </form>
            </nav>
        </div>
    </div>

    <!-- Contenido principal -->
    <main class="dashboard-container">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personalizados -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>





<?php /**PATH C:\laragon\www\staytuned\resources\views/layouts/dashboard.blade.php ENDPATH**/ ?>