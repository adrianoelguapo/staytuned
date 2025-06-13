<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Mis Publicaciones | StayTuned</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="<?php echo e(asset('css/dashboard.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/playlists.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/navbar-fix.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/posts.css')); ?>" rel="stylesheet">
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
            <a class="navbar-brand text-white fw-bold" href="<?php echo e(url('dashboard')); ?>">
                StayTuned
            </a>
        </div>

        <div class="d-none d-lg-flex ms-auto align-items-center gap-3">
            <a href="<?php echo e(route('dashboard')); ?>" class="nav-link-inline">Dashboard</a>
            <a href="<?php echo e(route('explore.users.index')); ?>" class="nav-link-inline">Explorar Usuarios</a>
            <a href="<?php echo e(route('playlists.index')); ?>" class="nav-link-inline">Mis Playlists</a>
            <a href="<?php echo e(route('posts.index')); ?>" class="nav-link-inline active">Mis Publicaciones</a>
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
                <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
                <a class="nav-link" href="<?php echo e(route('explore.users.index')); ?>">
                    <i class="fas fa-users me-2"></i> Explorar Usuarios
                </a>
                <a class="nav-link" href="<?php echo e(route('playlists.index')); ?>">
                    <i class="fas fa-music me-2"></i> Mis playlists
                </a>
                <a class="nav-link active" href="<?php echo e(route('posts.index')); ?>">
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
                    <button type="submit"
                            class="nav-link btn btn-link d-flex align-items-center text-danger rounded-0">
                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                    </button>
                </form>
            </nav>
        </div>
    </div>    <!-- Contenido principal -->
    <main class="dashboard-container container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                
                <!-- Header con botón de crear publicación -->
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center align-items-lg-center mb-4 text-center text-lg-start">
                    <div class="w-100 w-lg-auto mb-3 mb-lg-0">
                        <h1 class="text-white mb-2" style="font-size: 2.5rem;">
                            Mis Publicaciones
                        </h1>
                        <p class="text-white mb-0">Crea y administra tus publicaciones musicales para compartir con la comunidad</p>
                    </div>
                    <a href="<?php echo e(route('posts.create')); ?>" class="btn btn-new-playlist">
                        <i class="bi bi-plus-circle me-2"></i>
                        Nueva Publicación
                    </a>
                </div>

                <!-- Mensaje de éxito -->
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                <?php endif; ?>

                <?php if($posts->count() > 0): ?>
                    <!-- Lista de publicaciones -->
                    <div class="posts-list">
                        <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="post-card-full-width">
                                <div class="post-card-body">
                                    <!-- Contenido principal -->
                                    <div class="post-content-wrapper">
                                        <!-- Imagen/Cover de la publicación -->
                                        <div class="post-cover-container">
                                            <?php if($post->cover || $post->spotify_image): ?>
                                                <img src="<?php echo e($post->cover ?: $post->spotify_image); ?>" 
                                                     alt="<?php echo e($post->title); ?>"
                                                     class="post-cover-image"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="post-cover-placeholder" style="display: none;">
                                                    <i class="bi bi-newspaper"></i>
                                                </div>
                                            <?php else: ?>
                                                <div class="post-cover-placeholder">
                                                    <i class="bi bi-newspaper"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Información del post -->
                                        <div class="post-info-container">
                                            <div class="post-header-section">
                                                <a href="<?php echo e(route('posts.show', $post)); ?>" class="post-title-link">
                                                    <h3 class="post-title"><?php echo e($post->title); ?></h3>
                                                </a>
                                                <span class="post-category-badge"><?php echo e(ucfirst($post->category->type)); ?></span>
                                            </div>
                                            
                                            <?php if($post->content || $post->description): ?>
                                                <p class="post-description"><?php echo e(Str::limit($post->content ?: $post->description, 150)); ?></p>
                                            <?php endif; ?>
                                            
                                            <?php if($post->spotify_data): ?>
                                                <div class="spotify-info-card">
                                                    <i class="bi bi-spotify spotify-icon"></i>
                                                    <div class="spotify-text">
                                                        <div class="spotify-track-name"><?php echo e($post->spotify_name); ?></div>
                                                        <?php if($post->spotify_artist): ?>
                                                            <div class="spotify-artist-name"><?php echo e($post->spotify_artist); ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Meta información y acciones -->
                                            <div class="post-footer-section">
                                                <div class="post-meta-info">
                                                    <a href="<?php echo e(route('explore.users.show', $post->user)); ?>" class="post-author d-flex align-items-center text-decoration-none">
                                                        <img src="<?php echo e($post->user->profile_photo_url); ?>" 
                                                             class="rounded-circle me-2" 
                                                             style="width: 24px; height: 24px; object-fit: cover;"
                                                             alt="<?php echo e($post->user->name); ?>">
                                                        <span class="text-white"><?php echo e($post->user->username); ?></span>
                                                    </a>
                                                    <span class="post-date">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        <?php echo e($post->created_at->diffForHumans()); ?>

                                                    </span>
                                                </div>
                                                
                                                <div class="post-actions-section">
                                                    <button onclick="toggleLike(<?php echo e($post->id); ?>)" 
                                                            class="like-btn <?php echo e(Auth::check() && $post->isLikedBy(Auth::user()) ? 'liked' : ''); ?>"
                                                            data-post-id="<?php echo e($post->id); ?>"
                                                            data-liked="<?php echo e(Auth::check() && $post->isLikedBy(Auth::user()) ? 'true' : 'false'); ?>">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" 
                                                             class="<?php echo e(Auth::check() && $post->isLikedBy(Auth::user()) ? 'fill-current' : 'stroke-current fill-none'); ?>">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                        </svg>
                                                        <span class="likes-count"><?php echo e($post->likes_count); ?></span>
                                                    </button>
                                                    
                                                    <?php if($post->user_id === Auth::id()): ?>
                                                        <div class="post-actions-buttons">
                                                            <a href="<?php echo e(route('posts.show', $post)); ?>" 
                                                               class="btn btn-glass-action" 
                                                               title="Ver publicación">
                                                                <i class="bi bi-eye me-1"></i>Ver
                                                            </a>
                                                            <a href="<?php echo e(route('posts.edit', $post)); ?>" 
                                                               class="btn btn-glass-action" 
                                                               title="Editar publicación">
                                                                <i class="bi bi-pencil me-1"></i>Editar
                                                            </a>
                                                            <form action="<?php echo e(route('posts.destroy', $post)); ?>" 
                                                                  method="POST" 
                                                                  style="display: inline;"
                                                                  onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta publicación?')">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                                <button type="submit" 
                                                                        class="btn btn-glass-action btn-glass-danger" 
                                                                        title="Eliminar publicación">
                                                                    <i class="bi bi-trash me-1"></i>Eliminar
                                                                </button>
                                                            </form>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Paginación con estilo glassmorphism -->
                    <?php if($posts->hasPages()): ?>
                        <div class="text-center mt-5">
                            <nav aria-label="Navegación de publicaciones" class="d-flex justify-content-center">
                                <ul class="pagination pagination-custom">
                                    
                                    <?php if($posts->onFirstPage()): ?>
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                ‹
                                            </span>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo e($posts->previousPageUrl()); ?>" rel="prev">
                                                ‹
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    
                                    <?php $__currentLoopData = $posts->getUrlRange(1, $posts->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($page == $posts->currentPage()): ?>
                                            <li class="page-item active">
                                                <span class="page-link"><?php echo e($page); ?></span>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    
                                    <?php if($posts->hasMorePages()): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?php echo e($posts->nextPageUrl()); ?>" rel="next">
                                                ›
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                ›
                                            </span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                            
                            
                            <div class="pagination-info mt-3">
                                <small class="text-light">
                                    Mostrando <?php echo e($posts->firstItem() ?? 0); ?> - <?php echo e($posts->lastItem() ?? 0); ?> 
                                    de <?php echo e($posts->total()); ?> publicaciones
                                </small>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- Estado vacío -->
                    <div class="card dashboard-card text-center py-5">
                        <div class="card-body">
                            <i class="bi bi-newspaper display-1 text-muted mb-3"></i>
                            <h4 class="text-muted mb-3">No tienes publicaciones aún</h4>
                            <p class="text-muted mb-4">
                                Crea tu primera publicación y comienza a compartir tu música favorita
                            </p>
                            <a href="<?php echo e(route('posts.create')); ?>" class="btn btn-new-playlist">
                                <i class="bi bi-plus-circle me-2"></i>
                                Crear mi primera publicación
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </main>    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Función para manejar los likes
        function toggleLike(postId) {
            const btn = document.querySelector(`[data-post-id="${postId}"]`);
            const likesCountElement = btn.querySelector('.likes-count');
            const heartIcon = btn.querySelector('svg');
            
            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                likesCountElement.textContent = data.likes_count;
                
                if (data.liked) {
                    btn.classList.add('liked');
                    heartIcon.classList.add('fill-current');
                    heartIcon.classList.remove('stroke-current', 'fill-none');
                } else {
                    btn.classList.remove('liked');
                    heartIcon.classList.remove('fill-current');
                    heartIcon.classList.add('stroke-current', 'fill-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar el like');
            });
        }
    </script>
</body>
</html>





<?php /**PATH C:\laragon\www\staytuned\resources\views/posts/index.blade.php ENDPATH**/ ?>