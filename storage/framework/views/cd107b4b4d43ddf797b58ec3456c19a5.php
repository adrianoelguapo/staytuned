

<?php $__env->startSection('title', 'Miembros - ' . $community->name); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/community-fixed.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/users.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/mobile-responsive.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-5 members-page">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 members-header">
                <div>
                    <h1 class="text-white mb-2 members-title">
                        Miembros de <?php echo e($community->name); ?>

                    </h1>
                    <p class="text-white mb-0 members-subtitle">
                        Gestiona los miembros de tu comunidad
                    </p>
                </div>
                <a href="<?php echo e(route('communities.show', $community)); ?>" class="btn btn-new-playlist">
                    <i class="fas fa-arrow-left me-2"></i>
                    Volver a la Comunidad
                </a>
            </div>

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

            <!-- Información de la comunidad -->
            <div class="card dashboard-card mb-4 community-info-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <?php if($community->cover_image): ?>
                                <img src="<?php echo e(asset('storage/' . $community->cover_image)); ?>" 
                                     alt="<?php echo e($community->name); ?>"
                                     class="community-cover-mini">
                            <?php else: ?>
                                <div class="community-cover-mini-placeholder">
                                    <i class="fas fa-users"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col">
                            <h5 class="text-white mb-1"><?php echo e($community->name); ?></h5>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <span class="text-white-50">
                                    <i class="fas fa-users me-1"></i>
                                    <?php echo e($members->total()); ?> miembros
                                </span>
                                <span class="text-white-50">
                                    <i class="fas fa-newspaper me-1"></i>
                                    <?php echo e($community->posts_count); ?> publicaciones
                                </span>
                                <?php if($community->is_private): ?>
                                    <span class="community-badge community-badge-private">
                                        <i class="fas fa-lock me-1"></i>
                                        Privada
                                    </span>
                                <?php else: ?>
                                    <span class="community-badge community-badge-public">
                                        <i class="fas fa-globe me-1"></i>
                                        Pública
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($members->count() > 0): ?>
                <!-- Lista de miembros -->
                <div class="row g-4 members-grid">
                    <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card dashboard-card member-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start member-content">
                                        <!-- Avatar del miembro -->
                                        <div class="me-3 member-avatar">
                                            <?php if($member->profile_photo_path): ?>
                                                <img src="<?php echo e(asset('storage/' . $member->profile_photo_path)); ?>" 
                                                     alt="<?php echo e($member->name); ?>"
                                                     class="rounded-circle"
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px;">
                                                    <i class="fas fa-user text-white fs-4"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Información del miembro -->
                                        <div class="flex-grow-1 member-info">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h6 class="mb-1 text-white member-name"><?php echo e($member->name); ?></h6>
                                                    <p class="text-white-50 mb-0 small member-username">
                                                        <i class="fas fa-at me-1"></i><?php echo e($member->username); ?>

                                                    </p>
                                                </div>
                                                <?php if($member->pivot->role === 'admin' || $community->isOwner($member)): ?>
                                                    <span class="badge glassmorphism-success">
                                                        <i class="fas fa-crown me-1"></i>
                                                        <?php echo e($community->isOwner($member) ? 'Propietario' : 'Administrador'); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge glassmorphism-white">
                                                        <i class="fas fa-user me-1"></i>
                                                        Miembro
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <p class="text-white-50 small mb-3">
                                                <i class="fas fa-calendar me-1"></i>
                                                Se unió <?php echo e($member->pivot->joined_at ? \Carbon\Carbon::parse($member->pivot->joined_at)->diffForHumans() : 'hace tiempo'); ?>

                                            </p>

                                            <!-- Acciones -->
                                            <div class="d-flex gap-2 flex-wrap member-actions">
                                                <!-- Ver perfil -->
                                                <a href="<?php echo e(route('explore.users.show', $member)); ?>" 
                                                   class="btn btn-sm glassmorphism-white">
                                                    <i class="fas fa-eye me-1"></i>
                                                    Ver Perfil
                                                </a>

                                                <!-- Remover miembro (solo si no es el propietario) -->
                                                <?php if(!$community->isOwner($member)): ?>
                                                    <button type="button" 
                                                            class="btn btn-sm glassmorphism-danger remove-member-btn"
                                                            data-member-id="<?php echo e($member->id); ?>"
                                                            data-member-name="<?php echo e($member->name); ?>">
                                                        <i class="fas fa-user-times me-1"></i>
                                                        Remover
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Paginación -->
                <?php if($members->hasPages()): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($members->links('pagination::bootstrap-4', ['class' => 'pagination-custom'])); ?>

                    </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- Estado vacío -->
                <div class="card dashboard-card text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-users display-1 text-light mb-3"></i>
                        <h4 class="text-light mb-3">No hay miembros aún</h4>
                        <p class="text-light mb-4">
                            Esta comunidad está esperando sus primeros miembros.
                        </p>
                        <a href="<?php echo e(route('communities.show', $community)); ?>" class="btn btn-new-playlist">
                            <i class="fas fa-arrow-left me-2"></i>
                            Volver a la Comunidad
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de confirmación para remover miembro -->
<div class="modal fade" id="removeMemberModal" tabindex="-1" aria-labelledby="removeMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="removeMemberModalLabel">
                    <i class="fas fa-user-times me-2"></i>
                    Remover Miembro
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p class="text-white mb-3">¿Estás seguro de que quieres remover a <strong id="memberNameToRemove"></strong> de la comunidad?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Esta acción no se puede deshacer. El usuario tendrá que solicitar membresía nuevamente.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn glassmorphism-white" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn glassmorphism-danger" id="confirmRemoveMember">
                    <i class="fas fa-user-times me-1"></i>
                    Remover Miembro
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let memberToRemove = null;
    const removeMemberModal = new bootstrap.Modal(document.getElementById('removeMemberModal'));
    
    // Manejar clicks en botones de remover
    document.querySelectorAll('.remove-member-btn').forEach(button => {
        button.addEventListener('click', function() {
            memberToRemove = {
                id: this.dataset.memberId,
                name: this.dataset.memberName
            };
            
            document.getElementById('memberNameToRemove').textContent = memberToRemove.name;
            removeMemberModal.show();
        });
    });
    
    // Manejar confirmación de remoción
    document.getElementById('confirmRemoveMember').addEventListener('click', function() {
        if (!memberToRemove) return;
        
        const button = this;
        const originalText = button.innerHTML;
        
        // Mostrar loading
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Removiendo...';
        button.disabled = true;
        
        // Hacer petición AJAX
        fetch(`/communities/<?php echo e($community->id); ?>/members/${memberToRemove.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cerrar modal
                removeMemberModal.hide();
                  // Mostrar mensaje de éxito
                showAlert('success', data.success);
                
                // Limpiar cualquier caché relacionado con búsquedas de comunidades
                if (typeof(Storage) !== "undefined") {
                    // Limpiar localStorage relacionado con búsquedas de comunidades
                    Object.keys(localStorage).forEach(key => {
                        if (key.includes('community_search') || key.includes('search_results')) {
                            localStorage.removeItem(key);
                        }
                    });
                }
                
                // Recargar la página para actualizar la lista
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(data.error || 'Error al remover miembro');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', error.message || 'Error al remover miembro');
        })
        .finally(() => {
            // Restaurar botón
            button.innerHTML = originalText;
            button.disabled = false;
        });
    });
    
    // Función para mostrar alertas
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        `;
        
        // Insertar al inicio del contenedor principal
        const container = document.querySelector('.members-page .container-fluid > .row > .col-12');
        const header = container.querySelector('.members-header');
        header.insertAdjacentHTML('afterend', alertHtml);
        
        // Auto-hide después de 5 segundos
        setTimeout(() => {
            const alert = container.querySelector('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\staytuned\resources\views/communities/members.blade.php ENDPATH**/ ?>