<!DOCTYPE html>
<html lang="es">
    
    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Configuración de Perfil | StayTuned</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <link href="<?php echo e(asset('css/profile.css')); ?>" rel="stylesheet" />
        <link href="<?php echo e(asset('css/navbar-fix.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('css/livewire-modal-fix.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">

    </head>

    <body class="dashboard-body">

        <nav class="navbar navbar-expand-lg px-5 py-3">

            <div class="d-flex align-items-center">

                <button class="btn btn-link btn-offcanvas me-3 p-0 d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu">

                    <i class="bi bi-list text-white fs-3"></i>

                </button>

                <a class="navbar-brand text-white fw-bold" href="<?php echo e(url('dashboard')); ?>">StayTuned</a>
            </div>

            <div class="d-none d-lg-flex ms-auto align-items-center gap-3">

                <a href="<?php echo e(route('dashboard')); ?>" class="nav-link-inline">Dashboard</a>
                <a href="<?php echo e(route('explore.users.index')); ?>" class="nav-link-inline">Explorar Usuarios</a>
                <a href="<?php echo e(route('playlists.index')); ?>" class="nav-link-inline">Mis Playlists</a>
                <a href="<?php echo e(route('posts.index')); ?>" class="nav-link-inline">Mis Publicaciones</a>
                <a href="<?php echo e(route('communities.index')); ?>" class="nav-link-inline">Mis Comunidades</a>

                <div class="dropdown">

                    <a class="d-flex align-items-center text-white dropdown-toggle nav-link-inline" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                        <?php if(Laravel\Jetstream\Jetstream::managesProfilePhotos()): ?>

                            <img src="<?php echo e(Auth::user()->profile_photo_url); ?>" class="rounded-circle me-2 user-photo" alt="<?php echo e(Auth::user()->username); ?>"/>

                        <?php endif; ?>

                        <?php echo e(Auth::user()->username); ?>


                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>

                            <a class="dropdown-item d-flex align-items-center" href="<?php echo e(route('profile.settings')); ?>">

                                <i class="bi bi-person me-2"></i> Perfil

                            </a>

                        </li>

                        <li>

                            <hr class="dropdown-divider"/>

                        </li>

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

                        <i class="fas fa-users me-2"></i> Explorar Usuarios

                    </a>

                    <a class="nav-link" href="<?php echo e(route('playlists.index')); ?>">

                        <i class="fas fa-music me-2"></i> Mis Playlists

                    </a>


                    <a class="nav-link" href="<?php echo e(route('posts.index')); ?>">

                        <i class="fas fa-newspaper me-2"></i> Mis Publicaciones

                    </a>

                    <a class="nav-link" href="<?php echo e(route('communities.index')); ?>">

                        <i class="fas fa-users me-2"></i> Mis Comunidades

                    </a>

                </nav>

                <hr class="my-0"/>

                <nav class="nav flex-column">

                    <a class="nav-link" href="<?php echo e(route('profile.show')); ?>">

                        <i class="bi bi-person me-2"></i> Perfil

                    </a>

                    <form method="POST" action="<?php echo e(route('logout')); ?>">

                        <?php echo csrf_field(); ?>
                        <button type="submit" class="nav-link btn btn-link d-flex align-items-center text-start text-danger">

                            <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión

                        </button>

                    </form>

                </nav>

            </div>

        </div>

        <main class="container py-5">

            <div class="profile-header text-center mb-5">

                <?php if(Laravel\Jetstream\Jetstream::managesProfilePhotos()): ?>

                    <div class="profile-photo-container" onclick="document.getElementById('profile-photo-input').click();">

                        <img src="<?php echo e(Auth::user()->profile_photo_url); ?>" class="profile-header-img" alt="<?php echo e(Auth::user()->username); ?>"/>

                        <div class="profile-photo-overlay">

                            <i class="bi bi-camera-fill"></i>

                            <span>Cambiar foto</span>

                        </div>

                    </div>

                    <input type="file" id="profile-photo-input" style="display: none;" accept="image/*" onchange="uploadProfilePhoto(this)">

                <?php else: ?>

                    <div class="profile-header-img-original mb-3 bg-secondary"></div>

                <?php endif; ?>

                <h2 class="username mb-1"><?php echo e(Auth::user()->username); ?></h2>

                <div class="profile-stats d-flex justify-content-center gap-4">

                    <div><strong><?php echo e($stats['playlists_count']); ?></strong> playlists</div>
                    <div><strong><?php echo e($stats['followers_count']); ?></strong> seguidores</div>
                    <div><strong><?php echo e($stats['following_count']); ?></strong> siguiendo</div>

                </div>

            </div>

            <?php if(Laravel\Fortify\Features::canUpdateProfileInformation()): ?>

                <div class="mb-5 profile-form-section">

                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('profile.update-profile-information-form');

$__html = app('livewire')->mount($__name, $__params, 'lw-1731986693-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

                </div>

            <?php endif; ?>

            <?php if(Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords())): ?>

                <hr class="profile-divider"/>

                <div class="mb-5 profile-form-section">

                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('profile.update-password-form');

$__html = app('livewire')->mount($__name, $__params, 'lw-1731986693-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

                </div>

            <?php endif; ?>

            <?php if(Laravel\Fortify\Features::canManageTwoFactorAuthentication()): ?>

                <hr class="profile-divider"/>

                <div class="mb-5 profile-form-section">

                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('profile.two-factor-authentication-form');

$__html = app('livewire')->mount($__name, $__params, 'lw-1731986693-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

                </div>

            <?php endif; ?>

            <hr class="profile-divider"/>

            <div class="mb-5 profile-form-section">

                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('profile.logout-other-browser-sessions-form');

$__html = app('livewire')->mount($__name, $__params, 'lw-1731986693-3', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

            </div>

            <?php if(Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures()): ?>

                <hr class="profile-divider"/>

                <div class="mb-5 profile-form-section">

                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('profile.delete-user-form');

$__html = app('livewire')->mount($__name, $__params, 'lw-1731986693-4', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

                </div>

            <?php endif; ?>

        </main>

        <script>

            const csrfToken = '<?php echo e(csrf_token()); ?>';
            
            // Crear meta tag para CSRF token si no existe
            if (!document.querySelector('meta[name="csrf-token"]')) {
                const meta = document.createElement('meta');
                meta.name = 'csrf-token';
                meta.content = csrfToken;
                document.head.appendChild(meta);
            }

        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Modal Fix System -->
        <script src="<?php echo e(asset('js/modal-fix.js')); ?>?v=<?php echo e(time()); ?>"></script>
        
        <!-- Profile Settings System -->
        <script src="<?php echo e(asset('js/profile-settings.js')); ?>?v=<?php echo e(time()); ?>"></script>

    </body>

</html><?php /**PATH C:\laragon\www\staytuned\resources\views/profile/settings.blade.php ENDPATH**/ ?>