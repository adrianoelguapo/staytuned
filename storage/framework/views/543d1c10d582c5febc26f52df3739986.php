<?php $__env->startSection('title', 'Solicitudes de Membresía - ' . $community->name); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/community-fixed.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="text-white mb-2" style="font-size: 2.5rem;">
                        Solicitudes de Membresía
                    </h1>
                    <p class="text-white mb-0">Gestiona las solicitudes para unirse a <strong><?php echo e($community->name); ?></strong></p>
                </div>
                <a href="<?php echo e(route('communities.show', $community)); ?>" class="btn btn-new-playlist">
                    <i class="bi bi-arrow-left me-2"></i>
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

            <?php if($pendingRequests->count() > 0): ?>
                <!-- Lista de solicitudes pendientes -->
                <div class="row g-4">
                    <?php $__currentLoopData = $pendingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-12">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <!-- Avatar del usuario -->
                                        <div class="me-3">
                                            <?php if($request->user->profile_photo_path): ?>
                                                <img src="<?php echo e(asset('storage/' . $request->user->profile_photo_path)); ?>" 
                                                     alt="<?php echo e($request->user->name); ?>"
                                                     class="rounded-circle"
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px;">
                                                    <i class="bi bi-person text-white fs-4"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Información del usuario y solicitud -->
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h5 class="mb-1 text-white"><?php echo e($request->user->name); ?></h5>
                                                    <p class="text-white-50 mb-0">
                                                        <i class="bi bi-at me-1"></i><?php echo e($request->user->username); ?>

                                                    </p>
                                                </div>
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-clock me-1"></i>
                                                    Pendiente
                                                </span>
                                            </div>

                                            <p class="text-white-50 small mb-2">
                                                <i class="bi bi-clock me-1"></i>
                                                Solicitó hace <?php echo e($request->created_at->diffForHumans()); ?>

                                            </p>

                                            <?php if($request->message): ?>
                                                <div class="mb-3">
                                                    <h6 class="text-white-50 small mb-2">Mensaje del usuario:</h6>
                                                    <div class="p-3 rounded" style="background: rgba(255, 255, 255, 0.1);">
                                                        <p class="text-white mb-0"><?php echo e($request->message); ?></p>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Acciones -->
                                            <div class="d-flex gap-2 flex-wrap">
                                                <!-- Botón Aprobar -->
                                                <form action="<?php echo e(route('community-requests.approve', $request)); ?>" method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="bi bi-check-lg me-1"></i>
                                                        Aprobar
                                                    </button>
                                                </form>

                                                <!-- Botón Rechazar -->
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectModal<?php echo e($request->id); ?>">
                                                    <i class="bi bi-x-lg me-1"></i>
                                                    Rechazar
                                                </button>

                                                <!-- Ver perfil del usuario -->
                                                <a href="<?php echo e(route('explore.users.show', $request->user)); ?>" 
                                                   class="btn btn-outline-light btn-sm">
                                                    <i class="bi bi-person me-1"></i>
                                                    Ver Perfil
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <!-- Estado vacío - Idéntico al diseño de playlists -->
                <div class="card dashboard-card text-center py-5">
                    <div class="card-body">
                        <i class="bi bi-inbox display-1 text-light mb-3"></i>
                        <h4 class="text-light mb-3">No hay solicitudes pendientes</h4>
                        <p class="text-light mb-4">
                            No tienes solicitudes de membresía pendientes para esta comunidad.
                        </p>
                        <a href="<?php echo e(route('communities.show', $community)); ?>" class="btn btn-new-playlist">
                            <i class="bi bi-arrow-left me-2"></i>
                            Volver a la Comunidad
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modales para rechazar solicitudes -->
<?php $__currentLoopData = $pendingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="rejectModal<?php echo e($request->id); ?>" tabindex="-1" aria-labelledby="rejectModalLabel<?php echo e($request->id); ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel<?php echo e($request->id); ?>">
                        <i class="bi bi-x-circle me-2"></i>
                        Rechazar solicitud de <?php echo e($request->user->name); ?>

                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form action="<?php echo e(route('community-requests.reject', $request)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="admin_message<?php echo e($request->id); ?>" class="form-label">Mensaje para el usuario (opcional)</label>
                            <textarea 
                                class="form-control" 
                                id="admin_message<?php echo e($request->id); ?>" 
                                name="admin_message" 
                                rows="3" 
                                placeholder="Explica el motivo del rechazo (opcional)..."
                            ></textarea>
                        </div>
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            ¿Estás seguro de que quieres rechazar esta solicitud? Esta acción no se puede deshacer.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-lg me-1"></i>
                            Rechazar Solicitud
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\staytuned\resources\views/communities/requests.blade.php ENDPATH**/ ?>