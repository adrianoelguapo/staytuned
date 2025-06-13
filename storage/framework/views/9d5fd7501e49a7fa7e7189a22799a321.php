<?php if($ownedCommunities->count() > 0): ?>
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
                            <form action="<?php echo e(route('communities.destroy', $community)); ?>" 
                                  method="POST" 
                                  class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-community btn-community-danger btn-sm">
                                    <i class="fas fa-trash me-1"></i>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php else: ?>
    <!-- Mensaje cuando no hay comunidades propias -->
    <div class="card dashboard-card text-center py-4">
        <div class="card-body">
            <i class="fas fa-users display-4 text-white-50 mb-3"></i>
            <h5 class="text-white mb-3">No has creado ninguna comunidad</h5>
            <p class="text-white-50 mb-4">
                Crea tu primera comunidad y conecta con otros amantes de la música que comparten tus gustos.
            </p>
            <a href="<?php echo e(route('communities.create')); ?>" class="btn-new-playlist">
                <i class="fas fa-plus me-2"></i>
                Crear Mi Primera Comunidad
            </a>
        </div>
    </div>
<?php endif; ?>

<!-- Paginación para comunidades propias -->
<?php if($ownedCommunities->hasPages()): ?>
<div class="pagination-container mt-4">
    <div class="d-flex justify-content-center align-items-center">
        <nav aria-label="Paginación de mis comunidades">
            <ul class="pagination pagination-dark mb-0">
                
                <?php if($ownedCommunities->onFirstPage()): ?>
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </li>
                <?php else: ?>
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="<?php echo e($ownedCommunities->currentPage() - 1); ?>" data-type="owned">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                <?php endif; ?>

                
                <?php for($i = 1; $i <= $ownedCommunities->lastPage(); $i++): ?>
                    <?php if($i == $ownedCommunities->currentPage()): ?>
                        <li class="page-item active">
                            <span class="page-link"><?php echo e($i); ?></span>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a class="page-link" href="#" data-page="<?php echo e($i); ?>" data-type="owned"><?php echo e($i); ?></a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>

                
                <?php if($ownedCommunities->hasMorePages()): ?>
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="<?php echo e($ownedCommunities->currentPage() + 1); ?>" data-type="owned">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    
    
    <div class="pagination-info text-center mt-2">
        <small class="text-white-50">
            Mostrando <?php echo e($ownedCommunities->firstItem() ?? 0); ?> a <?php echo e($ownedCommunities->lastItem() ?? 0); ?> 
            de <?php echo e($ownedCommunities->total()); ?> comunidades
        </small>
    </div>
</div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\staytuned\resources\views/communities/partials/owned-communities.blade.php ENDPATH**/ ?>