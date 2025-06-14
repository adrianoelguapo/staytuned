<!DOCTYPE html>
<html lang = "es">

    <head>

        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width = device-width, initial-scale = 1.0">
        <meta name = "csrf-token" content = "<?php echo e(csrf_token()); ?>">
        <title>Editar: <?php echo e($post->title); ?> | StayTuned</title>

        <link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel = "stylesheet">
        <link href = "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel = "stylesheet">
        <link href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel = "stylesheet">
        
        <link href = "<?php echo e(asset('css/dashboard.css')); ?>" rel = "stylesheet">
        <link href = "<?php echo e(asset('css/playlists.css')); ?>" rel = "stylesheet">
        <link href = "<?php echo e(asset('css/navbar-fix.css')); ?>?v = <?php echo e(time()); ?>" rel = "stylesheet"><link href = "<?php echo e(asset('css/posts.css')); ?>" rel = "stylesheet">
        <link href = "<?php echo e(asset('css/icon-alignment-fix.css')); ?>?v = <?php echo e(time()); ?>" rel = "stylesheet">

    </head>

    <body class = "dashboard-body">

        <nav class = "navbar navbar-expand-lg px-5 py-3">

            <div class = "d-flex align-items-center">

                <button class = "btn btn-link btn-offcanvas me-3 p-0 d-lg-none" type = "button" data-bs-toggle = "offcanvas" data-bs-target = "#offcanvasMenu" aria-controls = "offcanvasMenu">

                    <i class = "bi bi-list text-white fs-3"></i>

                </button>

                <a class = "navbar-brand text-white fw-bold" href = "<?php echo e(route('dashboard')); ?>">StayTuned</a>

            </div>

            <div class = "d-none d-lg-flex ms-auto align-items-center gap-3">

                <a href = "<?php echo e(route('dashboard')); ?>" class = "nav-link-inline <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">

                    Dashboard

                </a>

                <a href = "<?php echo e(route('explore.users.index')); ?>" class = "nav-link-inline <?php echo e(request()->routeIs('explore.users.*') ? 'active' : ''); ?>">

                    Explorar Usuarios

                </a>

                <a href = "<?php echo e(route('playlists.index')); ?>" class = "nav-link-inline <?php echo e(request()->routeIs('playlists.*') ? 'active' : ''); ?>">

                    Mis Playlists

                </a>

                <a href = "<?php echo e(route('posts.index')); ?>" class = "nav-link-inline <?php echo e(request()->routeIs('posts.*') ? 'active' : ''); ?>">

                    Mis Publicaciones

                </a>

                <div class = "dropdown">

                    <button class = "btn btn-link dropdown-toggle d-flex align-items-center p-0" type = "button" id = "userDropdown" data-bs-toggle = "dropdown">

                        <img src = "<?php echo e(Auth::user()->profile_photo_url); ?>" alt = "<?php echo e(Auth::user()->name); ?>" class = "user-photo rounded-circle me-2">
                        <span class = "text-white"><?php echo e(Auth::user()->name); ?></span>

                    </button>

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
                    <a class = "nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href = "<?php echo e(route('dashboard')); ?>">

                        <i class = "fas fa-home me-2"></i> Dashboard

                    </a>

                    <a class = "nav-link <?php echo e(request()->routeIs('explore.users.*') ? 'active' : ''); ?>" href = "<?php echo e(route('explore.users.index')); ?>">

                        <i class = "fas fa-users me-2"></i> Explorar Usuarios

                    </a>

                    <a class = "nav-link <?php echo e(request()->routeIs('playlists.*') ? 'active' : ''); ?>" href = "<?php echo e(route('playlists.index')); ?>">

                        <i class = "fas fa-music me-2"></i> Mis Playlists

                    </a>

                    <a class = "nav-link <?php echo e(request()->routeIs('posts.*') ? 'active' : ''); ?>" href = "<?php echo e(route('posts.index')); ?>">

                        <i class = "fas fa-newspaper me-2"></i> Mis Publicaciones

                    </a>

                    <a class = "nav-link <?php echo e(request()->routeIs('communities.*') ? 'active' : ''); ?>" href = "<?php echo e(route('communities.index')); ?>">

                        <i class = "fas fa-users me-2"></i> Mis Comunidades

                    </a>

                </nav>

                <hr class = "my-0 border-secondary">

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

                <div class = "col-12 col-lg-8">
                    
                    <nav aria-label = "breadcrumb" class = "mb-4">

                        <ol class = "breadcrumb">

                            <li class = "breadcrumb-item">

                                <a href = "<?php echo e(route('posts.index')); ?>" class = "text-white-50">

                                    <i class = "fas fa-newspaper me-1"></i>
                                    Mis Publicaciones

                                </a>

                            </li>

                            <li class = "breadcrumb-item">

                                <a href = "<?php echo e(route('posts.show', $post)); ?>" class = "text-white-50"><?php echo e($post->title); ?></a>

                            </li>

                            <li class = "breadcrumb-item active text-white" aria-current = "page">Editar</li>

                        </ol>

                    </nav>

                    <?php if(session('success')): ?>

                        <div class = "alert alert-success mb-4">

                            <?php echo e(session('success')); ?>


                        </div>

                    <?php endif; ?>

                    <div class = "card create-playlist-card">

                        <div class = "card-body">

                            <div class = "d-flex align-items-center mb-4">

                                <i class = "fas fa-edit text-white me-3 fs-3"></i>
                                <h1 class = "h3 mb-0 create-playlist-title">Editar Publicación</h1>

                            </div>

                            <form method = "POST" action = "<?php echo e(route('posts.update', $post)); ?>" class = "playlist-form">

                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                    
                                <div class = "mb-3">

                                    <label for = "title" class = "form-label">

                                        Título <span class = "text-danger">*</span>

                                    </label>

                                    <input type = "text"  name = "title" id = "title" value = "<?php echo e(old('title', $post->title)); ?>" class = "form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" maxlength = "100" required>

                                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                                        <div class = "invalid-feedback">

                                            <?php echo e($message); ?>


                                        </div>

                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                </div>

                                <?php if($post->spotify_data): ?>

                                    <div class = "mb-4">

                                        <h5 class = "text-light mb-3">

                                            <i class = "fab fa-spotify text-white me-2"></i>
                                            Contenido de Spotify actual

                                        </h5>

                                        <div class = "spotify-content p-4 rounded-3" style = "background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">

                                            <div class = "d-flex align-items-center gap-3">

                                                <?php if($post->spotify_image): ?>

                                                    <img src = "<?php echo e($post->spotify_image); ?>" alt = "<?php echo e($post->spotify_name); ?>" class = "rounded-3 flex-shrink-0" style = "width: 64px; height: 64px; object-fit: cover;">

                                                <?php endif; ?>

                                                <div class = "flex-grow-1">

                                                    <h6 class = "text-white mb-1"><?php echo e($post->spotify_name); ?></h6>

                                                    <?php if($post->spotify_artist): ?>

                                                        <p class = "text-white-50 mb-2 small"><?php echo e($post->spotify_artist); ?></p>

                                                    <?php endif; ?>

                                                    <span class = "badge bg-white bg-opacity-25 text-white border border-white border-opacity-50">
                                                        
                                                        <?php echo e($post->spotify_type  ===  'track' ? 'Canción' : ($post->spotify_type  ===  'artist' ? 'Artista' : 'Álbum')); ?>

                                                    
                                                    </span>

                                                </div>

                                            </div>

                                        </div>

                                        <div class = "alert alert-info mt-3">

                                            <i class = "fas fa-info-circle me-2"></i>
                                            <strong>Nota:</strong> No es posible cambiar el contenido de Spotify asociado a esta publicación. 
                                            Si deseas asociar contenido diferente, deberás crear una nueva publicación.

                                        </div>

                                    </div>

                                <?php endif; ?>

                                    <div class = "d-flex gap-3 justify-content-end">

                                        <a href = "<?php echo e(route('posts.show', $post)); ?>" class = "btn btn-secondary">

                                            Cancelar

                                        </a>

                                        <button type = "submit" class = "btn btn-primary-playlist">

                                            Actualizar Publicación

                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                        </div>

                    </div>

                </div>

            </div>

        </main>


        <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    </body>
    
</html><?php /**PATH C:\laragon\www\staytuned\resources\views/posts/edit.blade.php ENDPATH**/ ?>