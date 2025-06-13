<?php $__env->startSection('title', $user->name . ' | StayTuned'); ?>

<?php $__env->startSection('content'); ?>

<div class = "container-fluid py-5">

    <div class = "row justify-content-center">

        <div class = "col-12 col-lg-10">

            <div class = "row">

        <div class = "col-lg-4 mb-4">

            <div class = "card dashboard-card">

                <div class = "card-body text-center">

                    <div class = "position-relative mb-3">

                        <img src = "<?php echo e($user->profile_photo_url); ?>" alt = "<?php echo e($user->name); ?>" class = "rounded-circle border border-3 border-light" style = "width: 120px; height: 120px; object-fit: cover;">

                    </div>

                    <h2 class = "text-white mb-1"><?php echo e('@' . $user->username); ?></h2>
                    <p class = "text-light mb-3"><?php echo e($user->name); ?></p>
                    
                    <?php if($user->bio): ?>

                        <p class = "text-light mb-4"><?php echo e($user->bio); ?></p>

                    <?php endif; ?>

                    <div class = "row text-center mb-4">

                        <div class = "col-4">

                            <a href = "<?php echo e(route('explore.users.followers', $user)); ?>" class = "text-decoration-none text-white d-flex flex-column">

                                <span class = "fw-bold fs-5"><?php echo e($stats['followers_count']); ?></span>
                                <span class = "text-light small">Seguidores</span>

                            </a>

                        </div>

                        <div class = "col-4">

                            <a href = "<?php echo e(route('explore.users.following', $user)); ?>" class = "text-decoration-none text-white d-flex flex-column">

                                <span class = "fw-bold fs-5"><?php echo e($stats['following_count']); ?></span>
                                <span class = "text-light small">Siguiendo</span>

                            </a>

                        </div>

                        <div class = "col-4">

                            <div class = "d-flex flex-column">

                                <span class = "fw-bold fs-5 text-white"><?php echo e($stats['playlists_count']); ?></span>
                                <span class = "text-light small">Playlists</span>

                            </div>

                        </div>

                    </div>

                    <?php if(auth()->guard()->check()): ?>

                        <?php if(Auth::id() !==  $user->id): ?>

                            <button type = "button" class = "btn btn-lg w-100 follow-btn <?php echo e($isFollowing ? 'btn-secondary' : 'btn-primary'); ?>" data-user-id = "<?php echo e($user->id); ?>" data-following = "<?php echo e($isFollowing ? 'true' : 'false'); ?>">

                                <i class = "fas <?php echo e($isFollowing ? 'fa-user-minus' : 'fa-user-plus'); ?> me-2"></i>
                                <span class = "follow-text"><?php echo e($isFollowing ? 'Siguiendo' : 'Seguir'); ?></span>

                            </button>

                        <?php endif; ?>

                    <?php endif; ?>

                    <div class = "mt-4 pt-3 border-top border-secondary">

                        <div class = "text-light small">

                            Se unió en <?php echo e($user->created_at->format('F Y')); ?>


                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class = "col-lg-8">

            <ul class = "nav nav-tabs nav-pills-custom mb-4" id = "userContentTabs" role = "tablist">

                <li class = "nav-item" role = "presentation">

                    <button class = "nav-link active" id = "playlists-tab" data-bs-toggle = "tab" data-bs-target = "#playlists" type = "button" role = "tab">

                        Playlists (<?php echo e($stats['playlists_count']); ?>)

                    </button>

                </li>
                
                <li class = "nav-item" role = "presentation">

                    <button class = "nav-link" id = "posts-tab" data-bs-toggle = "tab" data-bs-target = "#posts" type = "button" role = "tab">

                        Publicaciones (<?php echo e($stats['posts_count']); ?>)

                    </button>

                </li>

            </ul>

            <div class = "tab-content" id = "userContentTabsContent">

                <div class = "tab-pane fade show active" id = "playlists" role = "tabpanel">

                    <div id = "playlists-content">

                        <?php echo $__env->make('explore.users.partials.playlists', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    </div>

                </div>

                <div class = "tab-pane fade" id = "posts" role = "tabpanel">

                    <div id = "posts-content">

                        <?php echo $__env->make('explore.users.partials.posts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    </div>

                </div>

            </div>
                </div>

            </div>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

    <script src = "<?php echo e(asset('js/explore-users.js')); ?>"></script>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\staytuned\resources\views/explore/users/show.blade.php ENDPATH**/ ?>