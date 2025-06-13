<?php $__env->startSection('title', 'Explorar Usuarios | StayTuned'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Encabezado -->
            <div class="d-flex justify-content-between align-items-center mb-4 text-center text-md-start">
                <div class="w-100 w-md-auto">
                    <h1 class="h2 text-white mb-1">Explorar Usuarios</h1>
                    <p class="text-light mb-0">Descubre nuevos usuarios y conecta con ellos</p>
                </div>
            </div>

            <!-- Barra de búsqueda y filtros -->
            <div class="card dashboard-card mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('explore.users.index')); ?>" class="row g-3">
                        <div class="col-md-6">
                            <label for="search" class="form-label text-white">Buscar usuarios</label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       id="search" 
                                       name="search" 
                                       value="<?php echo e(request('search')); ?>"
                                       placeholder="Buscar por nombre, usuario o biografía...">
                                <button class="btn btn-outline-light" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="sort_by" class="form-label text-white">Ordenar por</label>
                            <select class="form-select custom-sort-select" 
                                    id="sort_by" 
                                    name="sort_by" 
                                    onchange="this.form.submit()">
                                <option value="followers" <?php echo e(request('sort_by') == 'followers' ? 'selected' : ''); ?>>
                                    Más seguidos
                                </option>
                                <option value="name" <?php echo e(request('sort_by') == 'name' ? 'selected' : ''); ?>>
                                    Nombre A-Z
                                </option>
                                <option value="newest" <?php echo e(request('sort_by') == 'newest' ? 'selected' : ''); ?>>
                                    Más recientes
                                </option>
                                <option value="playlists" <?php echo e(request('sort_by') == 'playlists' ? 'selected' : ''); ?>>
                                    Más playlists
                                </option>
                                <option value="posts" <?php echo e(request('sort_by') == 'posts' ? 'selected' : ''); ?>>
                                    Más publicaciones
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label d-block">&nbsp;</label>
                            <?php if(request()->hasAny(['search', 'sort_by'])): ?>
                                <a href="<?php echo e(route('explore.users.index')); ?>" class="btn btn-clear-filter w-100">
                                    <i class="fas fa-times"></i> Limpiar
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resultados -->
            <?php if($users->count() > 0): ?>
                <div class="row">
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card dashboard-card h-100">
                                <div class="card-body text-center">
                                    <!-- Foto de perfil -->
                                    <div class="position-relative mb-3">
                                        <img src="<?php echo e($user->profile_photo_url); ?>" 
                                             alt="<?php echo e($user->name); ?>" 
                                             class="rounded-circle border border-2 border-light"
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>

                                    <!-- Información del usuario -->
                                    <h5 class="text-white mb-1"><?php echo e($user->name); ?></h5>
                                    <p class="text-light small mb-2"><?php echo e('@' . $user->username); ?></p>
                                    
                                    <?php if($user->bio): ?>
                                        <p class="text-light small mb-3" style="max-height: 60px; overflow: hidden;">
                                            <?php echo e(Str::limit($user->bio, 100)); ?>

                                        </p>
                                    <?php endif; ?>

                                    <!-- Estadísticas -->
                                    <div class="row text-center mb-3">
                                        <div class="col-4">
                                            <div class="text-white fw-bold"><?php echo e($user->followers_count); ?></div>
                                            <div class="text-light small">Seguidores</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-white fw-bold"><?php echo e($user->playlists_count); ?></div>
                                            <div class="text-light small">Playlists</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-white fw-bold"><?php echo e($user->posts_count); ?></div>
                                            <div class="text-light small">Posts</div>
                                        </div>
                                    </div>                                    <!-- Botones de acción -->
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="<?php echo e(route('explore.users.show', $user)); ?>" 
                                           class="btn btn-outline-light btn-sm flex-fill">
                                            <i class="fas fa-eye me-1"></i>
                                            <span class="d-none d-sm-inline">Ver perfil</span>
                                        </a>
                                        
                                        <?php if(auth()->guard()->check()): ?>
                                            <?php if(Auth::id() !== $user->id): ?>
                                                <button type="button" 
                                                        class="btn btn-sm follow-btn flex-fill <?php echo e(in_array($user->id, $followingIds) ? 'btn-secondary' : 'btn-primary'); ?>"
                                                        data-user-id="<?php echo e($user->id); ?>"
                                                        data-following="<?php echo e(in_array($user->id, $followingIds) ? 'true' : 'false'); ?>">
                                                    <i class="fas <?php echo e(in_array($user->id, $followingIds) ? 'fa-user-check' : 'fa-user-plus'); ?> me-1"></i>
                                                    <span class="follow-text d-none d-sm-inline"><?php echo e(in_array($user->id, $followingIds) ? 'Siguiendo' : 'Seguir'); ?></span>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-4">
                    <?php echo e($users->appends(request()->query())->links('pagination::bootstrap-4', ['class' => 'pagination-custom'])); ?>

                </div>
            <?php else: ?>
                <!-- Sin resultados -->
                <div class="card dashboard-card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-users fa-3x text-light mb-3"></i>
                        <h4 class="text-white mb-2">No se encontraron usuarios</h4>
                        <p class="text-light mb-3">
                            <?php if(request('search')): ?>
                                No hay usuarios que coincidan con "<?php echo e(request('search')); ?>"
                            <?php else: ?>
                                Aún no hay usuarios para mostrar
                            <?php endif; ?>
                        </p>
                        <?php if(request('search')): ?>
                            <a href="<?php echo e(route('explore.users.index')); ?>" class="btn btn-outline-light">
                                <i class="fas fa-arrow-left"></i> Ver todos los usuarios
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar botones de seguir/dejar de seguir
    document.querySelectorAll('.follow-btn').forEach(function(button) {
        button.addEventListener('click', function() {
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
                        button.className = 'btn btn-sm follow-btn flex-fill btn-secondary';
                        icon.className = 'fas fa-user-check me-1';
                        text.textContent = 'Siguiendo';
                    } else {
                        button.className = 'btn btn-sm follow-btn flex-fill btn-primary';
                        icon.className = 'fas fa-user-plus me-1';
                        text.textContent = 'Seguir';
                    }
                    
                    // Mostrar mensaje (opcional)
                    // alert(data.message);
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
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<link href="<?php echo e(asset('css/users.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>






<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\staytuned\resources\views/explore/users/index.blade.php ENDPATH**/ ?>