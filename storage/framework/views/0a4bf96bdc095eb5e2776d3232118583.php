<?php if($posts->count() > 0): ?>
    <div class="space-y-4">
        <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card dashboard-card mb-4 post-card-hover" onclick="window.location.href='<?php echo e(route('posts.show', $post)); ?>'" style="cursor: pointer;">
                <div class="card-body">
                    <!-- Header del post -->
                    <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom border-light border-opacity-25">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo e($post->user->profile_photo_url); ?>" 
                                 alt="<?php echo e($post->user->username); ?>"
                                 class="rounded-circle me-3"
                                 style="width: 48px; height: 48px; object-fit: cover;">
                            <div>
                                <h6 class="text-white fw-semibold mb-1"><?php echo e($post->user->username); ?></h6>
                                <small class="text-white-50"><?php echo e($post->created_at->diffForHumans()); ?></small>
                            </div>
                        </div>
                        <span class="badge bg-primary bg-opacity-25 text-white border border-primary border-opacity-50 px-3 py-2">
                            <?php echo e(ucfirst($post->category->type)); ?>

                        </span>
                    </div>

                    <!-- Título del post -->
                    <?php if($post->title): ?>
                        <h5 class="text-white fw-bold mb-3"><?php echo e($post->title); ?></h5>
                    <?php endif; ?>

                    <!-- Contenido del post -->
                    <?php if($post->description): ?>
                        <div class="text-white-75 mb-3">
                            <?php echo e(Str::limit($post->description, 200)); ?>

                        </div>
                    <?php endif; ?>

                    <!-- Contenido de Spotify si existe -->
                    <?php if($post->spotify_data): ?>
                        <div class="spotify-preview-card mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <?php if($post->spotify_image): ?>
                                    <img src="<?php echo e($post->spotify_image); ?>" 
                                         alt="<?php echo e($post->spotify_name); ?>"
                                         class="rounded-3 flex-shrink-0"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                <?php endif; ?>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="text-white fw-medium"><?php echo e($post->spotify_name); ?></div>
                                    <?php if($post->spotify_artist): ?>
                                        <div class="text-white-50 small"><?php echo e($post->spotify_artist); ?></div>
                                    <?php endif; ?>
                                    <div class="d-flex align-items-center mt-1">
                                        <i class="fab fa-spotify text-success me-1"></i>
                                        <span class="text-white-50 small">Spotify</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Estadísticas del post -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top border-light border-opacity-25">
                        <div class="d-flex gap-4">
                            <span class="text-light small d-flex align-items-center">
                                <i class="fas fa-heart me-1 text-danger"></i><?php echo e($post->likes_count ?? 0); ?> likes
                            </span>
                            <span class="text-light small d-flex align-items-center">
                                <i class="fas fa-comment me-1 text-info"></i><?php echo e($post->comments_count ?? 0); ?> comentarios
                            </span>
                        </div>
                        <div class="text-light small">
                            <i class="fas fa-eye me-1"></i>Ver completo
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>    <!-- Paginación de publicaciones -->
    <?php if($posts->hasPages()): ?>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($posts->appends(request()->except('posts_page'))->links('pagination::bootstrap-4', ['class' => 'pagination-custom'])); ?>

        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="card dashboard-card">
        <div class="card-body text-center py-5">
            <i class="fas fa-newspaper fa-3x text-light mb-3"></i>
            <h5 class="text-white mb-2">Sin publicaciones</h5>
            <p class="text-light"><?php echo e($user->name ?? 'Este usuario'); ?> aún no ha realizado ninguna publicación.</p>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\staytuned\resources\views/explore/users/partials/posts.blade.php ENDPATH**/ ?>