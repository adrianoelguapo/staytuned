<?php $__env->startSection('title', 'Seguidores de ' . $user->name . ' | StayTuned'); ?>

<?php $__env->startSection('content'); ?>

<div class = "container-fluid py-5">

    <div class = "row justify-content-center">

        <div class = "col-12 col-lg-10">

            <div class = "d-flex align-items-center mb-4">

                <a href = "<?php echo e(route('explore.users.show', $user)); ?>"  class = "btn btn-outline-light me-3">

                    <i class = "fas fa-arrow-left"></i>

                </a>

                <div>

                    <h1 class = "h2 text-white mb-0">Seguidores de <?php echo e($user->username); ?></h1>
                    <p class = "text-light mb-0"><?php echo e($followers->total()); ?> seguidor/es</p>

                </div>

            </div>

            <?php if($followers->count() > 0): ?>

                <div class = "row">

                    <?php $__currentLoopData = $followers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $follow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php $follower  =  $follow->follower ?>

                        <div class = "col-lg-4 col-md-6 mb-4">

                            <div class = "card dashboard-card">

                                <div class = "card-body">

                                    <div class = "d-flex align-items-center">

                                        <img src = "<?php echo e($follower->profile_photo_url); ?>"  alt = "<?php echo e($follower->name); ?>"  class = "rounded-circle me-3" style = "width: 60px; height: 60px; object-fit: cover;">
                                        
                                        <div class = "flex-grow-1">

                                            <h6 class = "text-white mb-1"><?php echo e($follower->name); ?></h6>

                                            <p class = "text-light small mb-1"><?php echo e('@' . $follower->username); ?></p>

                                            <?php if($follower->bio): ?>

                                                <p class = "text-light small mb-2">

                                                    <?php echo e(Str::limit($follower->bio, 60)); ?>


                                                </p>

                                            <?php endif; ?>

                                            <div class = "text-light small">

                                                Siguiendo desde <?php echo e($follow->followed_at->diffForHumans()); ?>


                                            </div>

                                        </div>

                                    </div>

                                    <div class = "mt-3 d-flex gap-2">

                                        <a href = "<?php echo e(route('explore.users.show', $follower)); ?>" class = "btn btn-outline-light btn-sm flex-fill">

                                            Ver perfil

                                        </a>
                                        
                                        <?php if(auth()->guard()->check()): ?>

                                            <?php if(Auth::id() !==  $follower->id): ?>

                                                <?php

                                                    $isFollowing  =  Auth::user()->isFollowing($follower);

                                                ?>

                                                <button type = "button" class = "btn btn-sm follow-btn <?php echo e($isFollowing ? 'btn-secondary' : 'btn-primary'); ?>" data-user-id = "<?php echo e($follower->id); ?>" data-following = "<?php echo e($isFollowing ? 'true' : 'false'); ?>">

                                                    <i class = "fas <?php echo e($isFollowing ? 'fa-user-minus' : 'fa-user-plus'); ?>"></i>

                                                </button>

                                            <?php endif; ?>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>

                <div class = "d-flex justify-content-center mt-4">

                    <?php echo e($followers->links('pagination::bootstrap-4', ['class'  => 'pagination-custom'])); ?>


                </div>

            <?php else: ?>

                <div class = "card dashboard-card">

                    <div class = "card-body text-center py-5">

                        <i class = "fas fa-users fa-3x text-light mb-3"></i>

                        <h4 class = "text-white mb-2">Sin seguidores</h4>

                        <p class = "text-light"><?php echo e($user->name); ?> aún no tiene seguidores.</p>

                        <a href = "<?php echo e(route('explore.users.show', $user)); ?>" class = "btn btn-outline-light">

                            Volver al perfil

                        </a>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

    <script src = "<?php echo e(asset('js/explore-users.js')); ?>"></script>

<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .dashboard-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }

    .follow-btn {
        transition: all 0.3s ease;
        width: 40px;
        height: 32px;
    }

    .follow-btn:hover {
        transform: translateY(-1px);
    }

    .pagination-custom .page-link {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
    }

    .pagination-custom .page-link:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.4);
        color: white;
    }

    .pagination-custom .page-item.active .page-link {
        background: rgba(124, 58, 237, 0.8);
        border-color: rgba(124, 58, 237, 0.8);
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\staytuned\resources\views/explore/users/followers.blade.php ENDPATH**/ ?>