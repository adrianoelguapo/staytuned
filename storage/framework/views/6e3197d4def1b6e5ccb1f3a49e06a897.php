<?php $__env->startSection('title', $user->name . ' | StayTuned'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="row">
        <!-- Información principal del usuario -->
        <div class="col-lg-4 mb-4">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <!-- Foto de perfil -->
                    <div class="position-relative mb-3">
                        <img src="<?php echo e($user->profile_photo_url); ?>" 
                             alt="<?php echo e($user->name); ?>" 
                             class="rounded-circle border border-3 border-light"
                             style="width: 120px; height: 120px; object-fit: cover;">
                    </div>

                    <!-- Información básica -->
                    <h2 class="text-white mb-1"><?php echo e($user->name); ?></h2>
                    <p class="text-light mb-3"><?php echo e('@' . $user->username); ?></p>
                    
                    <?php if($user->bio): ?>
                        <p class="text-light mb-4"><?php echo e($user->bio); ?></p>
                    <?php endif; ?>

                    <!-- Estadísticas -->
                    <div class="row text-center mb-4">
                        <div class="col-4">
                            <a href="<?php echo e(route('explore.users.followers', $user)); ?>" 
                               class="text-decoration-none text-white d-flex flex-column">
                                <span class="fw-bold fs-5"><?php echo e($stats['followers_count']); ?></span>
                                <span class="text-light small">Seguidores</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="<?php echo e(route('explore.users.following', $user)); ?>" 
                               class="text-decoration-none text-white d-flex flex-column">
                                <span class="fw-bold fs-5"><?php echo e($stats['following_count']); ?></span>
                                <span class="text-light small">Siguiendo</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <div class="d-flex flex-column">
                                <span class="fw-bold fs-5 text-white"><?php echo e($stats['playlists_count']); ?></span>
                                <span class="text-light small">Playlists</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botón de seguir (solo si no es el usuario actual) -->
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(Auth::id() !== $user->id): ?>
                            <button type="button" 
                                    class="btn btn-lg w-100 follow-btn <?php echo e($isFollowing ? 'btn-secondary' : 'btn-primary'); ?>"
                                    data-user-id="<?php echo e($user->id); ?>"
                                    data-following="<?php echo e($isFollowing ? 'true' : 'false'); ?>">
                                <i class="fas <?php echo e($isFollowing ? 'fa-user-minus' : 'fa-user-plus'); ?> me-2"></i>
                                <span class="follow-text"><?php echo e($isFollowing ? 'Siguiendo' : 'Seguir'); ?></span>
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Información adicional -->
                    <div class="mt-4 pt-3 border-top border-secondary">
                        <div class="text-light small">
                            Se unió en <?php echo e($user->created_at->format('F Y')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido del usuario -->
        <div class="col-lg-8">
            <!-- Navegación de pestañas -->
            <ul class="nav nav-tabs nav-pills-custom mb-4" id="userContentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" 
                            id="playlists-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#playlists" 
                            type="button" 
                            role="tab">
                        Playlists (<?php echo e($stats['playlists_count']); ?>)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" 
                            id="posts-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#posts" 
                            type="button" 
                            role="tab">
                        </i>Publicaciones (<?php echo e($stats['posts_count']); ?>)
                    </button>
                </li>
            </ul>

            <!-- Contenido de las pestañas -->
            <div class="tab-content" id="userContentTabsContent">
                <!-- Pestana de Playlists -->
                <div class="tab-pane fade show active" id="playlists" role="tabpanel">
                    <div id="playlists-content">
                        <?php echo $__env->make('explore.users.partials.playlists', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>

                <!-- Pestana de Publicaciones -->
                <div class="tab-pane fade" id="posts" role="tabpanel">
                    <div id="posts-content">
                        <?php echo $__env->make('explore.users.partials.posts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>
            </div>        </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar botón de seguir/dejar de seguir
    const followBtn = document.querySelector('.follow-btn');
    if (followBtn) {
        followBtn.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const isFollowing = this.dataset.following === 'true';
            const button = this;
            
            // Deshabilitar botón durante la petición
            button.disabled = true;
            
            const url = isFollowing 
                ? `/explore/users/${userId}/unfollow`
                : `/explore/users/${userId}/follow`;
            
            const method = isFollowing ? 'DELETE' : 'POST';
            
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar botón
                    const newIsFollowing = !isFollowing;
                    button.dataset.following = newIsFollowing;
                    
                    const icon = button.querySelector('i');
                    const text = button.querySelector('.follow-text');
                    
                    if (newIsFollowing) {
                        button.className = 'btn btn-lg w-100 follow-btn btn-secondary';
                        icon.className = 'fas fa-user-minus me-2';
                        text.textContent = 'Siguiendo';
                    } else {
                        button.className = 'btn btn-lg w-100 follow-btn btn-primary';
                        icon.className = 'fas fa-user-plus me-2';
                        text.textContent = 'Seguir';
                    }
                    
                    // Actualizar contador de seguidores
                    const followersCount = document.querySelector('.col-4:first-child .fw-bold');
                    if (followersCount) {
                        followersCount.textContent = data.followers_count;
                    }
                } else {
                    alert(data.error || 'Error al procesar la solicitud');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            })
            .finally(() => {
                button.disabled = false;
            });
        });
    }
});
</script>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\staytuned\resources\views/explore/users/show.blade.php ENDPATH**/ ?>