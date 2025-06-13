<?php if($publicCommunities->count() > 0): ?>

    <?php $__currentLoopData = $publicCommunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $community): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <div class = "community-card-full-width">

        <div class = "community-card-body">

            <div class = "community-content-wrapper">

                <div class = "community-cover-container">

                    <?php if($community->cover_image): ?>

                        <img src = "<?php echo e(asset('storage/' . $community->cover_image)); ?>" alt = "<?php echo e($community->name); ?>" class = "community-cover-image">

                    <?php else: ?>

                        <div class = "community-cover-placeholder">

                            <i class = "fas fa-users"></i>

                        </div>

                    <?php endif; ?>

                </div>
                
                <div class = "community-info-container">

                    <div class = "community-header-section">

                        <a href = "<?php echo e(route('communities.show', $community)); ?>" class = "community-title-link">

                            <h3 class = "community-title"><?php echo e($community->name); ?></h3>

                        </a>

                        <?php if($community->is_private): ?>

                            <span class = "community-badge community-badge-private">

                                <i class = "fas fa-lock"></i>
                                Privada

                            </span>

                        <?php else: ?>
                            <span class = "community-badge community-badge-public">

                                <i class = "fas fa-globe"></i>
                                Pública

                            </span>

                        <?php endif; ?>

                    </div>
                    
                    <?php if($community->description): ?>

                        <p class = "community-description"><?php echo e(Str::limit($community->description, 150)); ?></p>

                    <?php endif; ?>
                    
                    <div class = "community-footer-section">

                        <div class = "community-meta-info">

                            <span class = "community-stat">

                                <i class = "fas fa-users me-1"></i>
                                <?php echo e($community->members_count); ?> miembros

                            </span>

                            <span class = "community-stat">

                                <i class = "fas fa-newspaper me-1"></i>
                                <?php echo e($community->posts_count); ?> posts

                            </span>        

                            <span class = "community-author">

                                <a href = "<?php echo e(route('explore.users.show', $community->owner)); ?>" class = "d-inline-flex align-items-center text-decoration-none text-white-50 hover-text-white">

                                    <?php if($community->owner->profile_photo_path): ?>

                                        <img src = "<?php echo e($community->owner->profile_photo_url); ?>" alt = "<?php echo e($community->owner->name); ?>" class = "rounded-circle me-2" style = "width: 20px; height: 20px; object-fit: cover;">

                                    <?php else: ?>

                                        <div class = "bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" style = "width: 20px; height: 20px; font-size: 10px;">

                                            <i class = "fas fa-user text-white"></i>

                                        </div>

                                    <?php endif; ?>

                                    <?php echo e($community->owner->username ?? $community->owner->name); ?>


                                </a>

                            </span>

                        </div>
                        
                        <div class = "community-actions-section">

                            <a href = "<?php echo e(route('communities.show', $community)); ?>" class = "btn-community btn-community-primary btn-sm">

                                Ver

                            </a>

                            <?php if($community->is_private): ?>

                                <?php if($community->hasPendingRequest(Auth::user())): ?>

                                    <button class = "btn-community btn-community-secondary btn-sm" disabled>

                                        Solicitud Enviada

                                    </button>

                                <?php else: ?>
                                
                                    <button type = "button" class = "btn-community btn-community-primary btn-sm" data-bs-toggle = "modal" data-bs-target = "#requestModal<?php echo e($community->id); ?>">

                                        Solicitar Unirse

                                    </button>

                                <?php endif; ?>

                            <?php else: ?>

                                <form action = "<?php echo e(route('communities.join', $community)); ?>" method = "POST" class = "d-inline">

                                    <?php echo csrf_field(); ?>

                                    <button type = "submit" class = "btn-community btn-community-primary btn-sm">

                                        Unirse

                                    </button>

                                </form>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php else: ?>

    <div class = "card dashboard-card text-center py-4">

        <div class = "card-body">

            <i class = "fas fa-compass display-4 text-white-50 mb-3"></i>
            <h5 class = "text-white mb-3">No hay comunidades públicas disponibles</h5>

            <p class = "text-white-50 mb-4">

                Sé el primero en crear una comunidad pública para que otros usuarios puedan unirse.

            </p>

            <a href = "<?php echo e(route('communities.create')); ?>" class = "btn-new-playlist d-inline-flex align-items-center justify-content-center">

                Crear Comunidad Pública

            </a>

        </div>

    </div>

<?php endif; ?>


<?php if($publicCommunities->hasPages()): ?>

<div class = "pagination-container mt-4">

    <div class = "d-flex justify-content-center align-items-center">

        <nav aria-label = "Paginación de comunidades públicas">

            <ul class = "pagination pagination-dark mb-0">

                <?php if($publicCommunities->onFirstPage()): ?>

                    <li class = "page-item disabled">

                        <span class = "page-link">

                            <i class = "fas fa-chevron-left"></i>

                        </span>

                    </li>

                <?php else: ?>

                    <li class = "page-item">

                        <a class = "page-link" href = "#" data-page = "<?php echo e($publicCommunities->currentPage() - 1); ?>" data-type = "public">

                            <i class = "fas fa-chevron-left"></i>

                        </a>

                    </li>

                <?php endif; ?>

                <?php for($i = 1; $i <= $publicCommunities->lastPage(); $i++): ?>

                    <?php if($i == $publicCommunities->currentPage()): ?>

                        <li class = "page-item active">

                            <span class = "page-link"><?php echo e($i); ?></span>

                        </li>

                    <?php else: ?>

                        <li class = "page-item">

                            <a class = "page-link" href = "#" data-page = "<?php echo e($i); ?>" data-type = "public"><?php echo e($i); ?></a>

                        </li>

                    <?php endif; ?>

                <?php endfor; ?>

                <?php if($publicCommunities->hasMorePages()): ?>

                    <li class = "page-item">

                        <a class = "page-link" href = "#" data-page = "<?php echo e($publicCommunities->currentPage() + 1); ?>" data-type = "public">

                            <i class = "fas fa-chevron-right"></i>

                        </a>

                    </li>

                <?php else: ?>

                    <li class = "page-item disabled">

                        <span class = "page-link">

                            <i class = "fas fa-chevron-right"></i>

                        </span>

                    </li>

                <?php endif; ?>

            </ul>

        </nav>

    </div>
    
    <div class = "pagination-info text-center mt-2">

        <small class = "text-white-50">

            Mostrando <?php echo e($publicCommunities->firstItem() ?? 0); ?> a <?php echo e($publicCommunities->lastItem() ?? 0); ?> 
            de <?php echo e($publicCommunities->total()); ?> comunidades

        </small>

    </div>

</div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\staytuned\resources\views/communities/partials/public-communities.blade.php ENDPATH**/ ?>