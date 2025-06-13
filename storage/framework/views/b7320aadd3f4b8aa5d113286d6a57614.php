<?php $__env->startSection('title', $community->name . ' | StayTuned'); ?>

<?php $__env->startPush('head'); ?>
<meta name="user-authenticated" content="<?php echo e(auth()->check() ? 'true' : 'false'); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>

    <link rel="stylesheet" href="<?php echo e(asset('css/community-fixed.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/posts.css')); ?>">

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid py-5">

    <div class="row justify-content-center">

        <div class="col-12 col-lg-10">

    <div class="community-header card dashboard-card mb-4">

        <div class="card-body">

            <div class="row align-items-center community-header-row">

                <div class="col-auto community-cover-col">

                    <div class="community-cover-large">

                        <?php if($community->cover_image): ?>

                            <img src="<?php echo e(asset('storage/' . $community->cover_image)); ?>" alt="<?php echo e($community->name); ?>" class="community-header-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            
                            <div class="community-header-cover d-none align-items-center justify-content-center">

                                <i class="fas fa-users"></i>

                            </div>

                        <?php else: ?>

                            <div class="community-header-cover d-flex align-items-center justify-content-center">

                                <i class="fas fa-users"></i>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

                <div class="col community-info-col">

                    <div class="community-header-info">

                        <?php if($community->is_private): ?>

                            <span class="badge playlist-badge-glassmorphism mb-2">

                                PRIVADA

                            </span>

                        <?php else: ?>

                            <span class="badge playlist-badge-glassmorphism mb-2">

                                PÚBLICA

                            </span>

                        <?php endif; ?>

                        <h1 class="playlist-title-large">

                            <?php echo e($community->name); ?>


                        </h1>
                        
                        <?php if($community->description): ?>

                            <p class="playlist-description-large text-muted">
                                <?php echo e($community->description); ?>

                            </p>

                        <?php endif; ?>

                          <div class="playlist-meta-large">

                            <span class="me-3">
                                
                                <img src="<?php echo e($community->owner->profile_photo_url); ?>" class="rounded-circle me-2 user-photo-small"  alt="<?php echo e($community->owner->name); ?>">
                                <?php echo e($community->owner->username); ?>


                            </span>

                            <span class="me-3">

                                <?php echo e($community->members_count); ?> miembros

                            </span>
                            
                            <span class="me-3">

                                <?php echo e($community->posts_count); ?> publicaciones

                            </span>
                            
                            <span class="text-light">

                                Creada <?php echo e($community->created_at->diffForHumans()); ?>


                            </span>


                        </div>

                    </div>

                </div>

                <div class="col-auto community-actions-col">

                    <div class="playlist-actions-header d-flex flex-column gap-2" style="min-width: 140px;">

                        <?php if($isOwner): ?>

                            <a href="<?php echo e(route('communities.members', $community)); ?>" class="btn btn-outline-light btn-sm w-100">

                                <span class="d-none d-md-inline">Miembros</span>

                            </a>

                        <?php endif; ?>
                        
                        <?php if($isOwner && $community->is_private): ?>

                            <a href="<?php echo e(route('communities.requests', $community)); ?>" class="btn btn-outline-light btn-sm w-100">

                                <span class="d-none d-md-inline">Solicitudes</span>

                                <?php if($community->pendingRequestsCount() > 0): ?>

                                    <span class="badge bg-danger ms-1"><?php echo e($community->pendingRequestsCount()); ?></span>

                                <?php endif; ?>

                            </a>

                        <?php endif; ?>

                        <?php if($isOwner): ?>

                            <a href="<?php echo e(route('communities.edit', $community)); ?>" class="btn btn-outline-light btn-sm w-100">

                                <span class="d-none d-md-inline">Editar</span>

                            </a>

                            <form action="<?php echo e(route('communities.destroy', $community)); ?>" method="POST" class="d-block w-100">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>

                                <button type="submit" class="btn btn-glass-action btn-glass-danger btn-sm w-100">

                                    <span class="d-none d-md-inline">Eliminar</span>

                                </button>

                            </form>

                        <?php elseif($isMember): ?>

                            <form action="<?php echo e(route('communities.leave', $community)); ?>" method="POST" class="d-block w-100">

                                <?php echo csrf_field(); ?>

                                <button type="submit" class="btn btn-glass-action btn-glass-danger btn-sm w-100">

                                    <i class="fas fa-sign-out-alt me-1"></i>
                                    <span class="d-none d-md-inline">Salir</span>

                                </button>

                            </form>

                        <?php else: ?>

                            <?php if(!$community->is_private): ?>

                                <form action="<?php echo e(route('communities.join', $community)); ?>" method="POST" class="d-block w-100">

                                    <?php echo csrf_field(); ?>

                                    <button type="submit" class="btn btn-outline-light btn-sm w-100">

                                        <i class="fas fa-plus me-1"></i>
                                        <span class="d-none d-md-inline">Unirse</span>

                                    </button>

                                </form>

                            <?php else: ?>

                                <?php if($hasPendingRequest): ?>

                                    <button type="button" class="btn btn-outline-light btn-sm w-100" disabled>

                                        <i class="fas fa-clock me-1"></i>
                                        <span class="d-none d-md-inline">Pendiente</span>

                                    </button>

                                <?php else: ?>

                                    <button type="button" class="btn btn-outline-light btn-sm w-100" data-bs-toggle="modal" data-bs-target="#requestMembershipModal">

                                        <i class="fas fa-paper-plane me-1"></i>
                                        <span class="d-none d-md-inline">Solicitar</span>

                                    </button>

                                <?php endif; ?>

                            <?php endif; ?>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="community-posts">

        <div class="community-posts-header">

            <h3 class="community-posts-title">

                Publicaciones de la Comunidad

            </h3>
            
            <?php if($isMember || $isOwner): ?>

                <a href="<?php echo e(route('communities.create-post', $community)); ?>" class="btn-community btn-community-primary">

                    <i class="fas fa-plus me-2"></i>
                    Nueva Publicación

                </a>

            <?php endif; ?>

        </div>
        
        <?php if(!$community->is_private || $isMember || $isOwner): ?>

            <?php if($posts->count() > 0): ?>

                <div class="posts-list">

                    <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <div class="post-card-full-width">

                        <div class="post-card-body">

                            <div class="post-content-wrapper">

                                <div class="post-cover-container">

                                    <?php if($post->cover || $post->spotify_image): ?>
                                        <img src="<?php echo e($post->cover ?: $post->spotify_image); ?>" alt="<?php echo e($post->title); ?>" class="post-cover-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                        <div class="post-cover-placeholder" style="display: none;">

                                            <i class="fas fa-newspaper"></i>

                                        </div>

                                    <?php else: ?>

                                        <div class="post-cover-placeholder">

                                            <i class="fas fa-newspaper"></i>

                                        </div>

                                    <?php endif; ?>

                                </div>

                                <div class="post-info-container">

                                    <div class="post-header-section">

                                        <a href="<?php echo e(route('posts.show', $post)); ?>" class="post-title-link">

                                            <h3 class="post-title">

                                                <?php echo e($post->title); ?>


                                            </h3>

                                        </a>

                                        <span class="post-category-badge"><?php echo e(ucfirst($post->category->type)); ?></span>

                                    </div>
                                    
                                    <?php if($post->content || $post->description): ?>

                                        <p class="post-description"><?php echo e(Str::limit($post->content ?: $post->description, 150)); ?></p>

                                    <?php endif; ?>
                                    
                                    <?php if($post->spotify_data): ?>

                                        <div class="spotify-info-card">

                                            <i class="fab fa-spotify spotify-icon"></i>

                                            <div class="spotify-text">

                                                <div class="spotify-track-name">

                                                    <?php echo e($post->spotify_name); ?>


                                                </div>

                                                <?php if($post->spotify_artist): ?>

                                                    <div class="spotify-artist-name"><?php echo e($post->spotify_artist); ?></div>

                                                <?php endif; ?>

                                            </div>

                                        </div>

                                    <?php endif; ?>
                                    
                                    <div class="post-footer-section">

                                        <div class="post-meta-info">

                                            <a href="<?php echo e(route('explore.users.show', $post->user)); ?>" class="post-author d-flex align-items-center text-decoration-none">

                                                <img src="<?php echo e($post->user->profile_photo_url); ?>"  class="rounded-circle me-2" style="width: 24px; height: 24px; object-fit: cover;" alt="<?php echo e($post->user->name); ?>">
                                                <span class="text-white"><?php echo e($post->user->username); ?></span>

                                            </a>

                                            <span class="post-date">

                                                <i class="fas fa-calendar me-1"></i>
                                                <?php echo e($post->created_at->diffForHumans()); ?>


                                            </span>

                                        </div>
                                        
                                        <div class="post-actions-section">

                                            <button onclick="toggleLike(<?php echo e($post->id); ?>)"  class="like-btn <?php echo e(Auth::check() && $post->isLikedBy(Auth::user()) ? 'liked' : ''); ?>" data-post-id="<?php echo e($post->id); ?>"ata-liked="<?php echo e(Auth::check() && $post->isLikedBy(Auth::user()) ? 'true' : 'false'); ?>">

                                                <i class="bi <?php echo e(Auth::check() && $post->isLikedBy(Auth::user()) ? 'bi-heart-fill text-danger' : 'bi-heart text-white'); ?>"></i>
                                                <span class="likes-count"><?php echo e($post->likes_count); ?></span>

                                            </button>
                                            
                                            <?php if($post->user_id === Auth::id()): ?>

                                                <div class="post-actions-buttons d-flex gap-2">
                                                    
                                                    <a href="<?php echo e(route('posts.show', $post)); ?>" class="btn-glass-action">

                                                        Ver

                                                    </a>

                                                    <a href="<?php echo e(route('posts.edit', $post)); ?>" class="btn-glass-action">

                                                        Editar

                                                    </a>

                                                    <form action="<?php echo e(route('posts.destroy', $post)); ?>" method="POST" class="d-inline">

                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>

                                                        <button type="submit" class="btn-glass-danger">
                                                            
                                                            Eliminar

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

            <div class="d-flex justify-content-center mt-4">

                <?php echo e($posts->links()); ?>


            </div>
            <?php else: ?>
            <div class="card dashboard-card text-center py-5">

                <div class="card-body">
                    
                    <i class="fas fa-newspaper display-1 text-muted mb-3"></i>
                    <h4 class="text-muted mb-3">No hay publicaciones aún</h4>

                    <p class="text-muted mb-4">

                        Esta comunidad está esperando su primera publicación

                    </p>

                    <?php if($isMember || $isOwner): ?>

                        <a href="<?php echo e(route('communities.create-post', $community)); ?>" class="btn btn-new-playlist">

                            <i class="fas fa-plus me-2"></i>
                            Crear Primera Publicación

                        </a>

                    <?php endif; ?>

                </div>

            </div>

        <?php endif; ?>

        <?php endif; ?>
        </div>

    </div>

</div>

<?php if(!$isOwner && !$isMember && $community->is_private && !$hasPendingRequest): ?>

<div class="modal fade" id="requestMembershipModal" tabindex="-1" aria-labelledby="requestMembershipModalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content bg-dark">

            <div class="modal-header">

                <h5 class="modal-title" id="requestMembershipModalLabel">

                    Solicitar membresía a <?php echo e($community->name); ?>


                </h5>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <form action="<?php echo e(route('communities.request', $community)); ?>" method="POST">

                <?php echo csrf_field(); ?>

                <div class="modal-body">

                    <div class="mb-3">

                        <label for="message" class="form-label">Mensaje para el administrador (opcional)</label>

                        <textarea class="form-control" id="message" name="message" rows="3" placeholder="¿Por qué te gustaría unirte a esta comunidad?"></textarea>
                    
                    </div>

                    <div class="alert alert-info mb-0">

                        <i class="fas fa-info-circle me-2"></i>
                        Tu solicitud será revisada por el administrador de la comunidad.

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                    <button type="submit" class="btn btn-purple">

                        <i class="fas fa-paper-plane me-2"></i>
                        Enviar solicitud

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<?php endif; ?>

<?php if(!$isOwner && !$isMember && $community->is_private): ?>

<div class="alert alert-warning mt-3">

    <div class="row align-items-center">

        <div class="col-auto">

            <i class="fas fa-lock fa-2x"></i>

        </div>

        <div class="col">

            <h6 class="mb-1">Esta es una comunidad privada</h6>

            <p class="mb-0 small">

                <?php if($hasPendingRequest): ?>

                    Tu solicitud de membresía está pendiente de aprobación.

                <?php else: ?>

                    Necesitas solicitar membresía para ver las publicaciones y participar.

                <?php endif; ?>

            </p>

        </div>

    </div>

</div>

<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

    <script src="<?php echo e(asset('js/community-show.js')); ?>"></script>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\staytuned\resources\views/communities/show.blade.php ENDPATH**/ ?>