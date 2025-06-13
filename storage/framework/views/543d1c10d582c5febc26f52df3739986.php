<?php $__env->startSection('title', 'Solicitudes de Membresía - ' . $community->name); ?>

<?php $__env->startPush('styles'); ?>
<link rel = "stylesheet" href = "<?php echo e(asset('css/community-fixed.css')); ?>">
<link rel = "stylesheet" href = "<?php echo e(asset('css/users.css')); ?>">
<link rel = "stylesheet" href = "<?php echo e(asset('css/community-requests.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class = "container-fluid py-5 requests-page">

    <div class = "row justify-content-center">
        <div class = "col-12 col-lg-10">

            <div class = "d-flex justify-content-between align-items-center mb-4">
                <div>

                    <h1 class = "text-white mb-2" style = "font-size: 2.5rem;">
                        Solicitudes de Acceso
                    </h1>

                    <p class = "text-white mb-0">Gestiona las solicitudes para unirse a <strong><?php echo e($community->name); ?></strong></p>

                </div>
                <a href = "<?php echo e(route('communities.show', $community)); ?>" class = "btn btn-new-playlist">

                    <i class = "bi bi-arrow-left me-2"></i>
                    Volver a la Comunidad

                </a>

            </div>

            <?php if(session('success')): ?>

                <div class = "alert alert-success alert-dismissible fade show" role = "alert">
                    
                    <?php echo e(session('success')); ?>

                    <button type = "button" class = "btn-close" data-bs-dismiss = "alert" aria-label = "Cerrar"></button>

                </div>

            <?php endif; ?>

            <?php if(session('error')): ?>

                <div class = "alert alert-danger alert-dismissible fade show" role = "alert">

                    <?php echo e(session('error')); ?>

                    <button type = "button" class = "btn-close" data-bs-dismiss = "alert" aria-label = "Cerrar"></button>

                </div>

            <?php endif; ?>

            <?php if($pendingRequests->count() > 0): ?>

                <div class = "row g-4">

                    <?php $__currentLoopData = $pendingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <div class = "col-12">

                            <div class = "card dashboard-card request-card">

                                <div class = "card-body">

                                    <div class = "d-flex align-items-start request-content">

                                        <div class = "me-3 request-avatar">

                                            <?php if($request->user->profile_photo_path): ?>

                                                <img src = "<?php echo e(asset('storage/' . $request->user->profile_photo_path)); ?>" lt = "<?php echo e($request->user->name); ?>" class = "rounded-circle" style = "width: 60px; height: 60px; object-fit: cover;">
                                            
                                            <?php else: ?>

                                                <div class = "rounded-circle bg-secondary d-flex align-items-center justify-content-center" style = "width: 60px; height: 60px;">
                                                    
                                                    <i class = "bi bi-person text-white fs-4"></i>
                                                
                                                </div>
                                                
                                            <?php endif; ?>

                                        </div>

                                        <div class = "flex-grow-1 request-info">

                                            <div class = "d-flex justify-content-between align-items-start mb-2">

                                                <div>

                                                    <h5 class = "mb-1 text-white"><?php echo e($request->user->name); ?></h5>

                                                    <p class = "text-white-50 mb-0">

                                                        <i class = "bi bi-at me-1"></i><?php echo e($request->user->username); ?>


                                                    </p>

                                                </div>

                                                <span class = "badge glassmorphism-warning">

                                                    <i class = "bi bi-clock me-1"></i>
                                                    Pendiente

                                                </span>

                                            </div>

                                            <p class = "text-white-50 small mb-2">

                                                <i class = "bi bi-clock me-1"></i>
                                                Solicitó hace <?php echo e($request->created_at->diffForHumans()); ?>


                                            </p>

                                            <?php if($request->message): ?>

                                                <div class = "mb-3">

                                                    <h6 class = "text-white-50 small mb-2">Mensaje del usuario:</h6>

                                                    <div class = "p-3 rounded" style = "background: rgba(255, 255, 255, 0.1);">

                                                        <p class = "text-white mb-0"><?php echo e($request->message); ?></p>

                                                    </div>

                                                </div>

                                            <?php endif; ?>


                                            <div class = "d-flex gap-2 flex-wrap request-actions">


                                                <form action = "<?php echo e(route('community-requests.approve', $request)); ?>" method = "POST" class = "d-inline request-action-form">

                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>

                                                    <button type = "submit" class = "btn btn-sm glassmorphism-success request-btn-approve">

                                                        Aprobar

                                                    </button>

                                                </form>

                                                <form action = "<?php echo e(route('community-requests.reject', $request)); ?>" method = "POST" class = "d-inline request-action-form">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>

                                                    <button type = "submit" class = "btn btn-sm glassmorphism-danger request-btn-reject">

                                                        Rechazar

                                                    </button>

                                                </form>

                                                <a href = "<?php echo e(route('explore.users.show', $request->user)); ?>" class = "btn btn-sm glassmorphism-white request-btn-profile">
                                                    
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

                <div class = "card dashboard-card text-center py-5">

                    <div class = "card-body text-center">

                        <i class = "bi bi-inbox display-1 text-light mb-3"></i>
                        <h4 class = "text-light text-center mb-3">No hay solicitudes pendientes</h4>

                        <p class = "text-light mb-4">

                            No tienes solicitudes de acceso pendientes para esta comunidad.

                        </p>

                        <a href = "<?php echo e(route('communities.show', $community)); ?>" class = "btn btn-new-playlist">

                            <i class = "bi bi-arrow-left me-2"></i>
                            Volver a la Comunidad

                        </a>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

    <script src = "<?php echo e(asset('js/community-requests.js')); ?>"></script>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\staytuned\resources\views/communities/requests.blade.php ENDPATH**/ ?>