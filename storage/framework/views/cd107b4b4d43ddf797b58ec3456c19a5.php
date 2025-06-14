

<?php $__env->startSection('title', 'Miembros - ' . $community->name); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/community-fixed.css')); ?>?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/users.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/mobile-responsive.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid py-5 members-page">

    <div class="row justify-content-center">

        <div class="col-12 col-lg-10">

            <div class="d-flex justify-content-between align-items-center mb-4 members-header">

                <div>

                    <h1 class="text-white mb-2 members-title">

                        Miembros de <?php echo e($community->name); ?>


                    </h1>

                    <p class="text-white mb-0 members-subtitle">

                        Gestiona los miembros de tu comunidad

                    </p>

                </div>

                <a href="<?php echo e(route('communities.show', $community)); ?>" class="btn btn-new-playlist">

                    Volver a la Comunidad

                </a>

            </div>

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

            <div class="card dashboard-card mb-4 community-info-card">

                <div class="card-body">

                    <div class="row align-items-center"> 

                        <div class="col-auto">

                            <div class="community-avatar-container">

                                <?php if($community->cover_image): ?>

                                    <img src="<?php echo e(asset('storage/' . $community->cover_image)); ?>" alt="<?php echo e($community->name); ?>" class="community-cover-mini">

                                <?php else: ?>

                                    <div class="community-cover-mini-placeholder">

                                        <i class="fas fa-users"></i>

                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>

                        <div class="col">

                            <h5 class="text-white mb-1"><?php echo e($community->name); ?></h5>

                            <div class="d-flex align-items-center gap-3 flex-wrap">

                                <span class="text-white-50">

                                    <?php echo e($members->total()); ?> miembro/s

                                </span>

                                <span class="text-white-50">

                                    <?php echo e($community->posts_count); ?> publicaciones

                                </span>

                                <?php if($community->is_private): ?>

                                    <span class="community-badge community-badge-private">

                                        Privada

                                    </span>

                                <?php else: ?>

                                    <span class="community-badge community-badge-public">

                                        Pública

                                    </span>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <?php if($members->count() > 0): ?>

                <div class="row g-4 members-grid">

                    <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <div class="col-12 col-md-6 col-lg-4">

                            <div class="card dashboard-card member-card">

                                <div class="card-body">

                                    <div class="d-flex align-items-start member-content">

                                        <div class="me-3 member-avatar">

                                            <?php if($member->profile_photo_path): ?>

                                                <img src="<?php echo e(asset('storage/' . $member->profile_photo_path)); ?>" alt="<?php echo e($member->name); ?>" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                                            
                                            <?php else: ?>

                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                    
                                                    <i class="fas fa-user text-white fs-4"></i>
                                                
                                                </div>

                                            <?php endif; ?>

                                        </div>

                                        <div class="flex-grow-1 member-info">

                                            <div class="d-flex justify-content-between align-items-start mb-2">

                                                <div>

                                                    <h6 class="mb-1 text-white member-name"><?php echo e($member->username); ?></h6>

                                                    <p class="text-white-50 mb-0 small member-username">

                                                        <?php echo e($member->name); ?>


                                                    </p>

                                                </div>

                                                <?php if($member->pivot->role === 'admin' || $community->isOwner($member)): ?>

                                                    <span class="badge glassmorphism-white">

                                                        <?php echo e($community->isOwner($member) ? 'Propietario' : 'Administrador'); ?>


                                                    </span>

                                                <?php else: ?>

                                                    <span class="badge glassmorphism-white">

                                                        Miembro

                                                    </span>

                                                <?php endif; ?>
                                                
                                            </div>

                                            <p class="text-white-50 small mb-3">

                                                Se unió <?php echo e($member->pivot->joined_at ? \Carbon\Carbon::parse($member->pivot->joined_at)->diffForHumans() : 'hace tiempo'); ?>


                                            </p>

                                            <div class="d-flex gap-2 flex-wrap member-actions">

                                                <a href="<?php echo e(route('explore.users.show', $member)); ?>" class="btn btn-sm glassmorphism-white">

                                                    Ver Perfil

                                                </a>

                                                <?php if(!$community->isOwner($member)): ?>

                                                    <button type="button" class="btn btn-sm glassmorphism-danger remove-member-btn" data-member-id="<?php echo e($member->id); ?>" data-member-name="<?php echo e($member->name); ?>">

                                                        Eliminar

                                                    </button>

                                                <?php endif; ?>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>

                <?php if($members->hasPages()): ?>

                    <div class="d-flex justify-content-center mt-4">

                        <?php echo e($members->links('pagination::bootstrap-4', ['class' => 'pagination-custom'])); ?>


                    </div>

                <?php endif; ?>

            <?php else: ?>

                <div class="card dashboard-card text-center py-5">

                    <div class="card-body">

                        <i class="fas fa-users display-1 text-light mb-3"></i>

                        <h4 class="text-light mb-3">No hay miembros aún</h4>

                        <p class="text-light mb-4">

                            Esta comunidad está esperando sus primeros miembros.

                        </p>

                        <a href="<?php echo e(route('communities.show', $community)); ?>" class="btn btn-new-playlist">

                            Volver a la Comunidad

                        </a>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<div class="modal fade" id="removeMemberModal" tabindex="-1" aria-labelledby="removeMemberModalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content bg-dark">

            <div class="modal-header">

                <h5 class="modal-title" id="removeMemberModalLabel">

                    <i class="fas fa-user-times me-2"></i>
                    Remover Miembro

                </h5>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>

            </div>

            <div class="modal-body">

                <p class="text-white mb-3">¿Estás seguro de que quieres remover a <strong id="memberNameToRemove"></strong> de la comunidad?</p>

                <div class="alert alert-warning">

                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Esta acción no se puede deshacer. El usuario tendrá que solicitar membresía nuevamente.

                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn glassmorphism-white" data-bs-dismiss="modal">Cancelar</button>

                <button type="button" class="btn glassmorphism-danger" id="confirmRemoveMember">

                    <i class="fas fa-user-times me-1"></i>
                    Remover Miembro

                </button>

            </div>

        </div>

    </div>
    
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/community-members.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\staytuned\resources\views/communities/members.blade.php ENDPATH**/ ?>