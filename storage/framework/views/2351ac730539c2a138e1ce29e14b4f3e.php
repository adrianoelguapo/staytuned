

<?php $__env->startSection('title', 'Comunidades'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/community-fixed.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-xl mt-5">    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-white mb-2">
                Comunidades
            </h2>
        </div>
        <a href="<?php echo e(route('communities.create')); ?>" class="btn-new-playlist">
            <i class="fas fa-plus me-2"></i>
            Crear Comunidad
        </a>
    </div>    <!-- Mis Comunidades -->
    <?php if($ownedCommunities->count() > 0): ?>
    <div class="community-section">
        <h3 class="community-section-title">
            <i class="fas fa-crown"></i>
            Mis Comunidades
        </h3>
        <div class="communities-list">
            <?php $__currentLoopData = $ownedCommunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $community): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="community-card-full-width">
                <div class="community-card-body">
                    <!-- Contenido principal -->
                    <div class="community-content-wrapper">
                        <!-- Imagen/Cover de la comunidad -->
                        <div class="community-cover-container">
                            <?php if($community->cover_image): ?>
                                <img src="<?php echo e(asset('storage/' . $community->cover_image)); ?>" 
                                     alt="<?php echo e($community->name); ?>"
                                     class="community-cover-image">
                            <?php else: ?>
                                <div class="community-cover-placeholder">
                                    <i class="fas fa-users"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Información de la comunidad -->
                        <div class="community-info-container">
                            <div class="community-header-section">
                                <a href="<?php echo e(route('communities.show', $community)); ?>" class="community-title-link">
                                    <h3 class="community-title"><?php echo e($community->name); ?></h3>
                                </a>
                                <?php if($community->is_private): ?>
                                    <span class="community-badge community-badge-private">
                                        <i class="fas fa-lock"></i>
                                        Privada
                                    </span>
                                <?php else: ?>
                                    <span class="community-badge community-badge-public">
                                        <i class="fas fa-globe"></i>
                                        Pública
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if($community->description): ?>
                                <p class="community-description"><?php echo e(Str::limit($community->description, 150)); ?></p>
                            <?php endif; ?>
                            
                            <!-- Meta información y acciones -->
                            <div class="community-footer-section">
                                <div class="community-meta-info">
                                    <span class="community-stat">
                                        <i class="fas fa-users me-1"></i>
                                        <?php echo e($community->members_count); ?> miembros
                                    </span>
                                    <span class="community-stat">
                                        <i class="fas fa-newspaper me-1"></i>
                                        <?php echo e($community->posts_count); ?> posts
                                    </span>
                                    <span class="community-date">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?php echo e($community->created_at->diffForHumans()); ?>

                                    </span>
                                </div>
                                
                                <div class="community-actions-section">
                                    <a href="<?php echo e(route('communities.show', $community)); ?>" class="btn-community btn-community-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>
                                        Ver
                                    </a>
                                    <a href="<?php echo e(route('communities.edit', $community)); ?>" class="btn-community btn-community-secondary btn-sm">
                                        <i class="fas fa-edit me-1"></i>
                                        Editar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>    <!-- Comunidades Unidas -->
    <?php if($userCommunities->count() > 0): ?>
    <div class="community-section">
        <h3 class="community-section-title">
            <i class="fas fa-handshake"></i>
            Comunidades Unidas
        </h3>
        <div class="communities-list">
            <?php $__currentLoopData = $userCommunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $community): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="community-card-full-width">
                <div class="community-card-body">
                    <!-- Contenido principal -->
                    <div class="community-content-wrapper">
                        <!-- Imagen/Cover de la comunidad -->
                        <div class="community-cover-container">
                            <?php if($community->cover_image): ?>
                                <img src="<?php echo e(asset('storage/' . $community->cover_image)); ?>" 
                                     alt="<?php echo e($community->name); ?>"
                                     class="community-cover-image">
                            <?php else: ?>
                                <div class="community-cover-placeholder">
                                    <i class="fas fa-users"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Información de la comunidad -->
                        <div class="community-info-container">
                            <div class="community-header-section">
                                <a href="<?php echo e(route('communities.show', $community)); ?>" class="community-title-link">
                                    <h3 class="community-title"><?php echo e($community->name); ?></h3>
                                </a>
                                <?php if($community->is_private): ?>
                                    <span class="community-badge community-badge-private">
                                        <i class="fas fa-lock"></i>
                                        Privada
                                    </span>
                                <?php else: ?>
                                    <span class="community-badge community-badge-public">
                                        <i class="fas fa-globe"></i>
                                        Pública
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if($community->description): ?>
                                <p class="community-description"><?php echo e(Str::limit($community->description, 150)); ?></p>
                            <?php endif; ?>
                            
                            <!-- Meta información y acciones -->
                            <div class="community-footer-section">
                                <div class="community-meta-info">
                                    <span class="community-stat">
                                        <i class="fas fa-users me-1"></i>
                                        <?php echo e($community->members_count); ?> miembros
                                    </span>
                                    <span class="community-stat">
                                        <i class="fas fa-newspaper me-1"></i>
                                        <?php echo e($community->posts_count); ?> posts
                                    </span>
                                    <span class="community-author">
                                        <i class="fas fa-user me-1"></i>
                                        <?php echo e($community->owner->name); ?>

                                    </span>
                                </div>
                                
                                <div class="community-actions-section">
                                    <a href="<?php echo e(route('communities.show', $community)); ?>" class="btn-community btn-community-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>
                                        Ver
                                    </a>
                                    <form action="<?php echo e(route('communities.leave', $community)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn-community btn-community-danger btn-sm" 
                                                onclick="return confirm('¿Estás seguro de que quieres salir de esta comunidad?')">
                                            <i class="fas fa-sign-out-alt me-1"></i>
                                            Salir
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>    <!-- Comunidades Públicas -->
    <?php if($publicCommunities->count() > 0): ?>
    <div class="community-section">
        <h3 class="community-section-title">
            <i class="fas fa-compass"></i>
            Descubrir Comunidades
        </h3>
        <div class="communities-list">
            <?php $__currentLoopData = $publicCommunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $community): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="community-card-full-width">
                <div class="community-card-body">
                    <!-- Contenido principal -->
                    <div class="community-content-wrapper">
                        <!-- Imagen/Cover de la comunidad -->
                        <div class="community-cover-container">
                            <?php if($community->cover_image): ?>
                                <img src="<?php echo e(asset('storage/' . $community->cover_image)); ?>" 
                                     alt="<?php echo e($community->name); ?>"
                                     class="community-cover-image">
                            <?php else: ?>
                                <div class="community-cover-placeholder">
                                    <i class="fas fa-users"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Información de la comunidad -->
                        <div class="community-info-container">
                            <div class="community-header-section">
                                <a href="<?php echo e(route('communities.show', $community)); ?>" class="community-title-link">
                                    <h3 class="community-title"><?php echo e($community->name); ?></h3>
                                </a>
                                <?php if($community->is_private): ?>
                                    <span class="community-badge community-badge-private">
                                        <i class="fas fa-lock"></i>
                                        Privada
                                    </span>
                                <?php else: ?>
                                    <span class="community-badge community-badge-public">
                                        <i class="fas fa-globe"></i>
                                        Pública
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if($community->description): ?>
                                <p class="community-description"><?php echo e(Str::limit($community->description, 150)); ?></p>
                            <?php endif; ?>
                            
                            <!-- Meta información y acciones -->
                            <div class="community-footer-section">
                                <div class="community-meta-info">
                                    <span class="community-stat">
                                        <i class="fas fa-users me-1"></i>
                                        <?php echo e($community->members_count); ?> miembros
                                    </span>
                                    <span class="community-stat">
                                        <i class="fas fa-newspaper me-1"></i>
                                        <?php echo e($community->posts_count); ?> posts
                                    </span>
                                    <span class="community-author">
                                        <i class="fas fa-user me-1"></i>
                                        <?php echo e($community->owner->name); ?>

                                    </span>
                                </div>
                                
                                <div class="community-actions-section">
                                    <a href="<?php echo e(route('communities.show', $community)); ?>" class="btn-community btn-community-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>
                                        Ver
                                    </a>
                                    <form action="<?php echo e(route('communities.join', $community)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn-community btn-community-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i>
                                            Unirse
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Estado vacío -->
    <?php if($ownedCommunities->count() == 0 && $userCommunities->count() == 0 && $publicCommunities->count() == 0): ?>
        <div class="card dashboard-card text-center py-5">
            <div class="card-body">
                <i class="bi bi-people display-1 text-light mb-3"></i>
                <h4 class="text-light mb-3">No hay comunidades disponibles</h4>
                <p class="text-light mb-4">
                    Crea la primera comunidad y conecta con otros amantes de la música
                </p>
                <a href="<?php echo e(route('communities.create')); ?>" class="btn-new-playlist">
                    <i class="bi bi-plus-circle me-2"></i>
                    Crear Primera Comunidad
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\staytuned\resources\views/communities/index.blade.php ENDPATH**/ ?>