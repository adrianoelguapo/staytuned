<?php if($playlists->count() > 0): ?>
    <div class="row">
        <?php $__currentLoopData = $playlists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $playlist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-6 mb-4">
                <div class="card dashboard-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <!-- Imagen de la playlist -->
                            <div class="playlist-image me-3">
                                <?php if($playlist->cover): ?>
                                    <img src="<?php echo e(asset('storage/' . $playlist->cover)); ?>" 
                                         alt="<?php echo e($playlist->name); ?>"
                                         class="rounded"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-music text-light"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Información de la playlist -->
                            <div class="flex-grow-1">
                                <h6 class="text-white mb-1"><?php echo e($playlist->name); ?></h6>
                                <?php if($playlist->description): ?>
                                    <p class="text-light small mb-2">
                                        <?php echo e(Str::limit($playlist->description, 80)); ?>

                                    </p>
                                <?php endif; ?>
                                <div class="text-light small">
                                    <i class="fas fa-music me-1"></i>
                                    <?php echo e($playlist->songs_count ?? 0); ?> canciones
                                </div>
                            </div>
                        </div>

                        <!-- Botón de ver playlist -->
                        <div class="mt-3">
                            <a href="<?php echo e(route('playlists.show', $playlist)); ?>" 
               class="btn btn-outline-light btn-sm w-100">
                                <i class="fas fa-play me-1"></i>Ver Playlist
                            </a>
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