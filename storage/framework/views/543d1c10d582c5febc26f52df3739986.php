<?php $__env->startSection('title', 'Solicitudes de Membresía - ' . $community->name); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/community-fixed.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Header -->
            <div class="mb-4">
                <div class="d-flex align-items-center mb-2">
                    <a href="<?php echo e(route('communities.show', $community)); ?>" class="text-success me-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h2 class="text-white mb-0">
                        <i class="fas fa-user-plus text-success me-2"></i>
                        Solicitudes de Membresía
                    </h2>
                </div>
                <p class="text-muted">
                    Gestiona las solicitudes para unirse a <strong><?php echo e($community->name); ?></strong>
                </p>
            </div>

            <!-- Mensajes de éxito/error -->
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
                <div class="row">
                    <?php $__currentLoopData = $pendingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-12 mb-4">
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
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Información del usuario y solicitud -->
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h5 class="text-white mb-1"><?php echo e($request->user->name); ?></h5>
                                                    <p class="text-muted small mb-0">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Solicitó hace <?php echo e($request->created_at->diffForHumans()); ?>

                                                    </p>
                                                </div>
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-hourglass-half me-1"></i>
                                                    Pendiente
                                                </span>
                                            </div>

                                            <?php if($request->message): ?>
                                                <div class="mb-3">
                                                    <h6 class="text-white-50 small mb-1">Mensaje del usuario:</h6>
                                                    <p class="text-white bg-dark bg-opacity-25 p-3 rounded">
                                                        <?php echo e($request->message); ?>

                                                    </p>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Acciones -->
                                            <div class="d-flex gap-2">
                                                <!-- Botón Aprobar -->
                                                <form action="<?php echo e(route('community-requests.approve', $request)); ?>" method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-check me-1"></i>
                                                        Aprobar
                                                    </button>
                                                </form>

                                                <!-- Botón Rechazar -->
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectModal<?php echo e($request->id); ?>">
                                                    <i class="fas fa-times me-1"></i>
                                                    Rechazar
                                                </button>

                                                <!-- Ver perfil del usuario -->
                                                <a href="<?php echo e(route('explore.users.show', $request->user)); ?>" 
                                                   class="btn btn-outline-light btn-sm">
                                                    <i class="fas fa-user me-1"></i>
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
                <!-- Estado vacío -->
                <div class="card dashboard-card text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-inbox display-1 text-muted mb-3"></i>
                        <h4 class="text-white mb-3">No hay solicitudes pendientes</h4>
                        <p class="text-muted mb-4">
                            No tienes solicitudes de membresía pendientes para esta comunidad.
                        </p>
                        <a href="<?php echo e(route('communities.show', $community)); ?>" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>
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
            <div class="modal-content" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
                <div class="modal-header border-bottom border-white border-opacity-25">
                    <h5 class="modal-title text-white" id="rejectModalLabel<?php echo e($request->id); ?>">
                        Rechazar solicitud de <?php echo e($request->user->name); ?>

                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form action="<?php echo e(route('community-requests.reject', $request)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="admin_message<?php echo e($request->id); ?>" class="form-label text-white">Mensaje para el usuario (opcional)</label>
                            <textarea 
                                class="form-control" 
                                id="admin_message<?php echo e($request->id); ?>" 
                                name="admin_message" 
                                rows="3" 
                                placeholder="Explica el motivo del rechazo (opcional)..."
                                style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.3); color: white;"
                            ></textarea>
                        </div>
                        <p class="text-white-50 small">
                            ¿Estás seguro de que quieres rechazar esta solicitud? Esta acción no se puede deshacer.
                        </p>
                    </div>
                    <div class="modal-footer border-top border-white border-opacity-25">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times me-1"></i>
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