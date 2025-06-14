<!DOCTYPE html>
<html lang = "es">
    <head>

        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width = device-width, initial-scale = 1.0">
        <meta name = "csrf-token" content = "<?php echo e(csrf_token()); ?>">
        <meta name = "user-authenticated" content = "<?php echo e(auth()->check() ? 'true' : 'false'); ?>">
        <title><?php echo e($post->title); ?> | StayTuned</title>
    
        <link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel = "stylesheet">
        <link href = "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel = "stylesheet">
        <link href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel = "stylesheet">
        
        <link href = "<?php echo e(asset('css/posts.css')); ?>" rel = "stylesheet">
        <link href = "<?php echo e(asset('css/navbar-fix.css')); ?>?v = <?php echo e(time()); ?>" rel = "stylesheet">

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
                <a href = "<?php echo e(route('dashboard')); ?>"  class = "nav-link-inline <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">

                    Dashboard

                </a>

                <a href = "<?php echo e(route('explore.users.index')); ?>" class = "nav-link-inline <?php echo e(request()->routeIs('explore.users.*') ? 'active' : ''); ?>">

                    Explorar Usuarios

                </a>

                <a href = "<?php echo e(route('playlists.index')); ?>" class = "nav-link-inline <?php echo e(request()->routeIs('playlists.*') ? 'active' : ''); ?>">

                    Mis playlists

                </a>

                <a href = "<?php echo e(route('posts.index')); ?>" class = "nav-link-inline <?php echo e(request()->routeIs('posts.*') ? 'active' : ''); ?>">

                    Mis Publicaciones

                </a>

                <a href = "<?php echo e(route('communities.index')); ?>" class = "nav-link-inline">

                    Mis comunidades

                </a>

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

                    <a class = "nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href = "<?php echo e(route('dashboard')); ?>">

                        <i class = "fas fa-home me-2"></i> Dashboard

                    </a>

                    <a class = "nav-link <?php echo e(request()->routeIs('explore.users.*') ? 'active' : ''); ?>" href = "<?php echo e(route('explore.users.index')); ?>">

                        <i class = "fas fa-users me-2"></i> Explorar Usuarios

                    </a>

                    <a class = "nav-link <?php echo e(request()->routeIs('playlists.*') ? 'active' : ''); ?>" href = "<?php echo e(route('playlists.index')); ?>">

                        <i class = "fas fa-music me-2"></i> Mis playlists

                    </a>

                    <a class = "nav-link <?php echo e(request()->routeIs('posts.*') ? 'active' : ''); ?>" href = "<?php echo e(route('posts.index')); ?>">

                        <i class = "fas fa-newspaper me-2"></i> Mis Publicaciones

                    </a>

                    <a class = "nav-link" href = "<?php echo e(route('communities.index')); ?>">

                        <i class = "fas fa-users me-2"></i> Mis comunidades

                    </a>

                </nav>

                <hr class = "my-0 border-secondary">

                <nav class = "nav flex-column">

                    <a class = "nav-link d-flex align-items-center" href = "<?php echo e(route('profile.settings')); ?>">

                        <i class = "bi bi-person me-2"></i> Perfil

                    </a>


                    <form method = "POST" action = "<?php echo e(route('logout')); ?>">

                        <?php echo csrf_field(); ?>

                        <button type = "submit" class = "nav-link btn btn-link d-flex align-items-center text-danger rounded-0">

                            <i class = "bi bi-box-arrow-right me-2"></i> Cerrar sesión

                        </button>

                    </form>

                </nav>

            </div>

        </div>

        <main class = "dashboard-container">

            <div class = "container-fluid">

                <div class = "row justify-content-center">

                    <div class = "col-12 col-lg-10">

                        <?php if(session('success')): ?>

                            <div class = "alert alert-success mb-4">

                                <?php echo e(session('success')); ?>


                            </div>

                        <?php endif; ?>

                        <div class = "card dashboard-card mb-4">
                            
                            <div class = "card-body">

                                <div class = "d-flex justify-content-between align-items-start mb-4 pb-4 border-bottom border-light border-opacity-25 flex-column flex-md-row gap-3">

                                    <div class = "d-flex align-items-center flex-grow-1 min-width-0">

                                        <div class = "d-flex align-items-center">

                                            <a href = "<?php echo e(route('explore.users.show', $post->user)); ?>" class = "text-decoration-none">

                                                <?php if($post->user->profile_photo_url): ?>

                                                    <img src = "<?php echo e($post->user->profile_photo_url); ?>" alt = "<?php echo e($post->user->username); ?>" class = "rounded-circle me-3 post-author-photo" style = "width: 48px; height: 48px; object-fit: cover;">
                                               
                                                <?php else: ?>

                                                    <div class = "bg-light bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3 post-author-photo" style = "width: 48px; height: 48px;">

                                                        <span class = "text-white fw-medium"><?php echo e(substr($post->user->username, 0, 1)); ?></span>

                                                    </div>

                                                <?php endif; ?>

                                            </a>

                                            <div class = "overflow-hidden">

                                                <a href = "<?php echo e(route('explore.users.show', $post->user)); ?>" class = "text-decoration-none">

                                                    <h5 class = "text-white fw-semibold mb-1 post-author-name text-truncate"><?php echo e($post->user->username); ?></h5>

                                                </a>

                                                <p class = "text-white-50 mb-0 small"><?php echo e($post->created_at->diffForHumans()); ?></p>

                                            </div>

                                        </div>

                                    </div>
                                    
                                    <div class = "d-flex align-items-center gap-2 flex-shrink-0">

                                        <div class = "d-flex align-items-center gap-2 flex-wrap">

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $post)): ?>

                                                <a href = "<?php echo e(route('posts.edit', $post)); ?>" class = "btn btn-glass-action btn-sm">
                                                    <i class = "fas fa-edit"></i>
                                                    <span class = "d-none d-sm-inline ms-1">Editar</span>
                                                </a>

                                                <form method = "POST" action = "<?php echo e(route('posts.destroy', $post)); ?>" class = "d-inline">

                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>

                                                    <button type = "submit" class = "btn btn-glass-action btn-glass-danger btn-sm">
                                                        <i class = "fas fa-trash"></i>
                                                        <span class = "d-none d-sm-inline ms-1">Eliminar</span>
                                                    </button>

                                                </form>

                                            <?php endif; ?>
                                            
                                            <span class = "badge post-category-badge">

                                                <?php echo e(ucfirst($post->category->type)); ?>


                                            </span>

                                        </div>

                                    </div>

                                </div>

                                <h1 class = "text-white fw-bold mb-4"><?php echo e($post->title); ?></h1>

                                <?php if($post->content || $post->description): ?>

                                    <div class = "mb-4">

                                        <p class = "text-white-75 lh-lg"><?php echo e($post->content ?: $post->description); ?></p>

                                    </div>

                                <?php endif; ?>

                                <?php if($post->spotify_data): ?>

                                    <div class = "spotify-content mb-4">

                                        <div class = "d-flex align-items-center gap-4">

                                            <?php if($post->spotify_image): ?>

                                                <img src = "<?php echo e($post->spotify_image); ?>" alt = "<?php echo e($post->spotify_name); ?>" class = "spotify-image rounded-3 flex-shrink-0">

                                            <?php endif; ?>
                                            
                                            <div class = "flex-grow-1 min-w-0">

                                                <div class = "d-flex align-items-center gap-2 mb-2">

                                                    <i class = "fab fa-spotify text-success fs-5"></i>

                                                    <span class = "spotify-type">

                                                        <?php echo e(ucfirst($post->spotify_type)); ?> de Spotify

                                                    </span>

                                                </div>
                                                
                                                <h4 class = "spotify-title">

                                                    <?php echo e($post->spotify_name); ?>


                                                </h4>
                                                
                                                <?php if($post->spotify_artist): ?>

                                                    <p class = "spotify-artist mb-3">

                                                        <?php echo e($post->spotify_artist); ?>


                                                    </p>

                                                <?php endif; ?>
                                                
                                                <?php if($post->spotify_external_url): ?>

                                                    <a href = "<?php echo e($post->spotify_external_url); ?>" target = "_blank" class = "btn btn-success btn-sm d-inline-flex align-items-center">

                                                        <i class = "fab fa-spotify me-2"></i>

                                                        Abrir en Spotify

                                                        <i class = "fas fa-external-link-alt ms-2 small"></i>

                                                    </a>

                                                <?php endif; ?>

                                            </div>

                                        </div>

                                    </div>

                                <?php elseif($post->cover): ?>

                                    <div class = "mb-4 text-center">

                                        <img src = "<?php echo e($post->cover); ?>" alt = "<?php echo e($post->title); ?>" class = "img-fluid rounded-3 shadow-lg" style = "max-width: 600px;">
                                    
                                    </div>

                                <?php endif; ?>

                                <div class = "d-flex justify-content-between align-items-center pt-4 mt-4 border-top border-light border-opacity-25">

                                    <div class = "d-flex align-items-center gap-4">
                                        
                                        <button onclick = "toggleLike(<?php echo e($post->id); ?>)" class = "btn like-btn p-2 d-flex align-items-center gap-2" data-post-id = "<?php echo e($post->id); ?>" data-liked = "<?php echo e(Auth::check() && $post->isLikedBy(Auth::user()) ? 'true' : 'false'); ?>">
                                            
                                            <i class = "bi <?php echo e(Auth::check() && $post->isLikedBy(Auth::user()) ? 'bi-heart-fill text-danger' : 'bi-heart text-white'); ?>"></i>
                                            <span class = "likes-count"><?php echo e($post->likes_count); ?></span> likes

                                        </button>
                                        
                                        <div class = "d-flex align-items-center gap-2 text-white-50">

                                            <i class = "fas fa-comment"></i>
                                            <span class = "comments-count-actions"><?php echo e($post->comments->count()); ?> comentarios</span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class = "card dashboard-card">

                            <div class = "card-body">

                                <div class = "d-flex justify-content-between align-items-center mb-4">

                                    <h3 class = "text-white fw-semibold mb-0">

                                        <span class = "comments-count-header">Comentarios (<?php echo e($post->comments->count()); ?>)</span>

                                    </h3>

                                </div>
                                
                                <?php if(auth()->guard()->check()): ?>

                                    <div class = "form-container mb-4">

                                        <form id = "comment-form">

                                            <?php echo csrf_field(); ?>

                                            <div class = "mb-3">

                                                <label for = "comment-text" class = "form-label">Agregar comentario</label>

                                                <textarea id = "comment-text" name = "text" rows = "3" class = "form-control" placeholder = "Escribe tu comentario..."></textarea>

                                            </div>

                                            <div class = "d-flex justify-content-end">

                                                <button type = "submit" class = "btn btn-primary">
                                                    
                                                    <i class = "fas fa-comment me-2"></i>
                                                    Comentar

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                <?php endif; ?>

                                <div id = "comments-list">

                                    <?php $__empty_1 = true; $__currentLoopData = $post->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                                        <div class = "comment-item border-bottom border-light border-opacity-25 py-3" data-comment-id = "<?php echo e($comment->id); ?>">

                                            <div class = "d-flex gap-3">

                                                <div class = "flex-shrink-0">

                                                    <a href = "<?php echo e(route('explore.users.show', $comment->user)); ?>" class = "text-decoration-none">

                                                        <?php if($comment->user->profile_photo_path): ?>

                                                            <img class = "rounded-circle comment-author-photo" src = "<?php echo e($comment->user->profile_photo_url); ?>" alt = "<?php echo e($comment->user->username); ?>" style = "width: 32px; height: 32px; object-fit: cover;">
                                                       
                                                        <?php else: ?>
                                                            <div class = "bg-light bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center comment-author-photo" style = "width: 32px; height: 32px;">

                                                                <span class = "text-white small fw-medium">

                                                                    <?php echo e(substr($comment->user->username, 0, 1)); ?>


                                                                </span>

                                                            </div>

                                                        <?php endif; ?>

                                                    </a>

                                                </div>

                                                <div class = "flex-grow-1 min-w-0">

                                                    <div class = "d-flex align-items-center gap-2 mb-1">

                                                        <a href = "<?php echo e(route('explore.users.show', $comment->user)); ?>" class = "text-decoration-none">

                                                            <span class = "text-white fw-medium small comment-author-name"><?php echo e($comment->user->username); ?></span>

                                                        </a>

                                                        <span class = "text-white-50 small">

                                                            <?php echo e($comment->created_at->diffForHumans()); ?>


                                                        </span>

                                                    </div>

                                                    <div class = "mb-2">

                                                        <p class = "comment-text text-white-75 small mb-0">

                                                            <?php echo e($comment->text); ?>


                                                        </p>
                                                        
                                                    </div>
                                                    
                                                    <?php if(auth()->guard()->check()): ?>

                                                        <?php if($comment->user_id  ===  auth()->id()): ?>

                                                            <div class = "d-flex gap-2">

                                                                <button onclick = "editComment(<?php echo e($comment->id); ?>)" class = "btn btn-link btn-sm text-white-50 p-0">

                                                                    Editar

                                                                </button>

                                                                <button onclick = "deleteComment(<?php echo e($comment->id); ?>)" class = "btn btn-link btn-sm text-danger p-0">

                                                                    Eliminar

                                                                </button>

                                                            </div>

                                                        <?php endif; ?>

                                                    <?php endif; ?>

                                                </div>

                                            </div>

                                        </div>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                                        <div class = "text-center py-5">

                                            <div class = "empty-state-icon mb-3">

                                                <i class = "fas fa-comments text-white-50 fs-1"></i>

                                            </div>

                                            <h4 class = "text-white mb-2">No hay comentarios aún</h4>

                                            <p class = "text-white-50 mb-0">

                                                <?php if(auth()->guard()->guest()): ?>

                                                    <a href = "<?php echo e(route('login')); ?>" class = "text-decoration-none">Inicia sesión</a> 
                                                    para ser el primero en comentar.

                                                <?php else: ?>

                                                    Sé el primero en comentar esta publicación.

                                                <?php endif; ?>

                                            </p>

                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                        <div class = "mt-4 mb-4">

                            <a href = "<?php echo e(route('posts.index')); ?>" class = "btn btn-secondary">

                                <i class = "fas fa-arrow-left me-2"></i>
                                Volver a publicaciones

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </main>

        <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src = "<?php echo e(asset('js/posts-show.js')); ?>"></script>

    </body>

</html><?php /**PATH C:\laragon\www\staytuned\resources\views/posts/show.blade.php ENDPATH**/ ?>