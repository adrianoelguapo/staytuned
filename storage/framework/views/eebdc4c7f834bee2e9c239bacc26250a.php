<?php if($followingPosts && $followingPosts->count() > 0): ?>

    <div class = "posts-list">

        <?php $__currentLoopData = $followingPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <div class = "post-card-full-width mb-4">

                <div class = "post-card-body">

                    <div class = "post-content-wrapper">

                        <div class = "post-cover-container">

                            <?php if($post->cover || ($post->spotify_data && isset($post->spotify_data['images']) && count($post->spotify_data['images']) > 0)): ?>

                                <img src = "<?php echo e($post->cover ?: $post->spotify_data['images'][0]['url']); ?>" alt = "<?php echo e($post->title); ?>" class = "post-cover-image" onerror = "this.style.display = 'none'; this.nextElementSibling.style.display = 'flex';">

                                <div class = "post-cover-placeholder" style = "display: none;">

                                    <i class = "fas fa-newspaper"></i>

                                </div>

                            <?php else: ?>

                                <div class = "post-cover-placeholder">

                                    <i class = "fas fa-newspaper"></i>

                                </div>

                            <?php endif; ?>

                        </div>
                        
                        <div class = "post-info-container">

                            <div class = "post-header-section">

                                <a href = "<?php echo e(route('posts.show', $post)); ?>" class = "post-title-link">

                                    <h3 class = "post-title"><?php echo e($post->title); ?></h3>

                                </a>

                                <?php if($post->category): ?>

                                    <span class = "post-category-badge"><?php echo e(ucfirst($post->category->type)); ?></span>

                                <?php endif; ?>

                            </div>
                            
                            <?php if($post->content || $post->description): ?>

                                <p class = "post-description"><?php echo e(Str::limit($post->content ?: $post->description, 150)); ?></p>

                            <?php endif; ?>
                            
                            <?php if($post->spotify_data): ?>

                                <div class = "spotify-info-card">

                                    <i class = "fab fa-spotify spotify-icon"></i>

                                    <div class = "spotify-text">

                                        <div class = "spotify-track-name"><?php echo e($post->spotify_name); ?></div>

                                        <?php if($post->spotify_artist): ?>

                                            <div class = "spotify-artist-name"><?php echo e($post->spotify_artist); ?></div>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            <?php endif; ?>
                            
                            <div class = "post-footer-section">

                                <div class = "post-meta-info">

                                    <span class = "post-author">

                                        <a href = "<?php echo e(route('explore.users.show', $post->user)); ?>" class = "d-inline-flex align-items-center text-decoration-none">

                                            <?php if(Laravel\Jetstream\Jetstream::managesProfilePhotos()): ?>

                                                <img src = "<?php echo e($post->user->profile_photo_url); ?>" class = "rounded-circle me-2" alt = "<?php echo e($post->user->name); ?>" style = "width: 20px; height: 20px; object-fit: cover;" />
                                            
                                            <?php else: ?>

                                                <i class = "fas fa-user me-1"></i>

                                            <?php endif; ?>

                                            <span class = "text-white"><?php echo e($post->user->username); ?></span>

                                        </a>

                                    </span>

                                    <span class = "post-date">

                                        <i class = "fas fa-calendar me-1"></i>
                                        <?php echo e($post->created_at->diffForHumans()); ?>


                                    </span>

                                    <span class = "post-stat">

                                        <i class = "fas fa-heart me-1"></i>
                                        <?php echo e($post->likes_count ?? 0); ?> likes

                                    </span>

                                </div>
                                
                                <div class = "post-actions-section">

                                    <a href = "<?php echo e(route('posts.show', $post)); ?>" class = "btn-glass btn-sm">

                                        Ver

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>

    <?php if($followingPosts->hasPages()): ?>

        <div class = "d-flex justify-content-center mt-4">

            <?php echo e($followingPosts->appends(request()->except('following_page'))->links('pagination::bootstrap-4', ['class'  => 'pagination-custom'])); ?>


        </div>

    <?php endif; ?>

<?php else: ?>    
    <div class = "card dashboard-card">

        <div class = "card-body text-center py-5">

            <i class = "fas fa-user-friends fa-3x text-muted mb-3"></i>

            <h5 class = "text-white mb-2">Sin publicaciones recientes</h5>
            
            <p class = "text-light mb-3">No hay publicaciones de los últimos usuarios que sigues en las últimas 24 horas.</p>

            <div class = "d-flex justify-content-center">

                <a href = "<?php echo e(route('explore.users.index')); ?>" class = "btn btn-outline-light d-inline-flex align-items-center">

                    Explorar Usuarios

                </a>

            </div>

        </div>

    </div>

<?php endif; ?><?php /**PATH C:\laragon\www\staytuned\resources\views/dashboard/partials/following-posts.blade.php ENDPATH**/ ?>