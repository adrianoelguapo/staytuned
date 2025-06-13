<?php if($playlists->count() > 0): ?>
    <div class="playlists-list">
        <?php $__currentLoopData = $playlists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $playlist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="playlist-card-full-width">
                <div class="playlist-card-body">
                    <!-- Contenido principal -->
                    <div class="playlist-content-wrapper">
                        <!-- Imagen de la playlist -->
                        <div class="playlist-cover-container">
                            <?php if($playlist->cover): ?>
                                <img src="<?php echo e(asset('storage/' . $playlist->cover)); ?>"
                                     alt="<?php echo e($playlist->name); ?>"
                                     class="playlist-cover-image">
                            <?php else: ?>
                                <div class="playlist-cover-placeholder">
                                    <i class="bi bi-music-note-beamed"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Información de la playlist -->
                        <div class="playlist-info-container">
                            <div class="playlist-header-section">
                                <div class="playlist-title-wrapper flex-grow-1">
                                    <a href="<?php echo e(route('playlists.show', $playlist)); ?>" class="playlist-title-link">
                                        <h3 class="playlist-title"><?php echo e($playlist->name); ?></h3>
                                    </a>
                                    <div class="playlist-badges mt-2">
                                        <span class="playlist-privacy-badge">
                                            <i class="bi bi-<?php echo e($playlist->is_public ? 'globe' : 'lock'); ?> me-1"></i>
                                            <?php echo e($playlist->is_public ? 'Pública' : 'Privada'); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>

                            <?php if($playlist->description): ?>
                                <p class="playlist-description"><?php echo e(Str::limit($playlist->description, 150)); ?></p>
                            <?php endif; ?>

                            <!-- Meta información y acciones -->
                            <div class="playlist-footer-section">
                                <div class="playlist-meta-info">
                                    <span class="playlist-stat">
                                        <i class="bi bi-music-note me-1"></i>
                                        <?php echo e($playlist->songs_count ?? 0); ?> canciones
                                    </span>
                                    <span class="playlist-author">
                                        <i class="bi bi-person me-1"></i>
                                        <?php echo e($playlist->user->username ?? $user->username); ?>

                                    </span>
                                    <span class="playlist-date">
                                        <i class="bi bi-calendar me-1"></i>
                                        <span class="d-none d-sm-inline"><?php echo e($playlist->created_at->diffForHumans()); ?></span>
                                        <span class="d-sm-none"><?php echo e($playlist->created_at->format('d/m/Y')); ?></span>
                                    </span>
                                </div>

                                <div class="playlist-actions-section">
                                    <a href="<?php echo e(route('playlists.show', $playlist)); ?>" class="btn-playlist btn-playlist-primary btn-sm">
                                        <i class="bi bi-play-fill me-1"></i>
                                        Ver
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>    <!-- Paginación de playlists -->
    <?php if($playlists->hasPages()): ?>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($playlists->appends(request()->except('playlists_page'))->links('pagination::bootstrap-4', ['class' => 'pagination-custom'])); ?>

        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="card dashboard-card">
        <div class="card-body text-center py-5">
            <i class="fas fa-music fa-3x text-light mb-3"></i>
            <h5 class="text-white mb-2">Sin playlists públicas</h5>
            <p class="text-light"><?php echo e($user->name ?? 'Este usuario'); ?> aún no ha creado playlists públicas.</p>
        </div>
    </div>
<?php endif; ?>





<?php /**PATH C:\laragon\www\staytuned\resources\views/explore/users/partials/playlists.blade.php ENDPATH**/ ?>