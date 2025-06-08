

<?php $__env->startSection('title', 'Editar Comunidad'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/community-fixed.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-xl">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex align-items-center mb-2">
            <a href="<?php echo e(route('communities.show', $community)); ?>" class="text-success me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-white mb-0">
                <i class="fas fa-edit text-success me-2"></i>
                Editar Comunidad
            </h2>
        </div>
        <p class="text-muted">Actualiza la información de tu comunidad</p>
    </div>

    <!-- Formulario -->
    <div class="community-form">
        <form action="<?php echo e(route('communities.update', $community)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <!-- Nombre -->
            <div class="community-form-group">
                <label for="name" class="community-form-label">
                    <i class="fas fa-tag me-2"></i>
                    Nombre de la Comunidad *
                </label>
                <input type="text" 
                       class="community-form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                       id="name" 
                       name="name" 
                       value="<?php echo e(old('name', $community->name)); ?>"
                       placeholder="Ej: Amantes del Rock Clásico"
                       required>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Descripción -->
            <div class="community-form-group">
                <label for="description" class="community-form-label">
                    <i class="fas fa-align-left me-2"></i>
                    Descripción
                </label>
                <textarea class="community-form-control community-form-textarea <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                          id="description" 
                          name="description" 
                          placeholder="Describe de qué trata tu comunidad, qué tipo de música comparten, reglas, etc."><?php echo e(old('description', $community->description)); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <small class="text-muted">Ayuda a otros usuarios a entender el propósito de tu comunidad</small>
            </div>

            <!-- Imagen actual -->
            <?php if($community->cover_image): ?>
            <div class="community-form-group">
                <label class="community-form-label">Imagen Actual</label>
                <div>
                    <img src="<?php echo e(asset('storage/' . $community->cover_image)); ?>" 
                         alt="<?php echo e($community->name); ?>" 
                         style="max-width: 300px; height: 150px; object-fit: cover; border-radius: 8px;">
                </div>
            </div>
            <?php endif; ?>

            <!-- Nueva imagen de portada -->
            <div class="community-form-group">
                <label for="cover_image" class="community-form-label">
                    <i class="fas fa-image me-2"></i>
                    <?php echo e($community->cover_image ? 'Cambiar Imagen de Portada' : 'Imagen de Portada'); ?>

                </label>
                <input type="file" 
                       class="community-form-control <?php $__errorArgs = ['cover_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                       id="cover_image" 
                       name="cover_image"
                       accept="image/jpeg,image/png,image/jpg,image/gif">
                <?php $__errorArgs = ['cover_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <small class="text-muted">
                    Formatos soportados: JPEG, PNG, JPG, GIF. Tamaño máximo: 2MB
                    <?php if($community->cover_image): ?>
                        <br>Deja en blanco para mantener la imagen actual
                    <?php endif; ?>
                </small>
            </div>

            <!-- Vista previa de nueva imagen -->
            <div id="image-preview" style="display: none;" class="community-form-group">
                <label class="community-form-label">Vista Previa</label>
                <img id="preview-img" src="" alt="Vista previa" style="max-width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
            </div>

            <!-- Privacidad -->
            <div class="community-form-check">
                <input type="checkbox" 
                       id="is_private" 
                       name="is_private" 
                       value="1"
                       <?php echo e(old('is_private', $community->is_private) ? 'checked' : ''); ?>>
                <label for="is_private">
                    <i class="fas fa-lock me-2"></i>
                    Hacer esta comunidad privada
                </label>
            </div>
            <small class="text-muted d-block mt-2">
                Las comunidades privadas solo son visibles para los miembros invitados
            </small>

            <!-- Estadísticas -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="text-center p-3 bg-secondary bg-opacity-20 rounded">
                        <div class="text-success h4 mb-1"><?php echo e($community->members_count); ?></div>
                        <div class="text-muted small">Miembros</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3 bg-secondary bg-opacity-20 rounded">
                        <div class="text-success h4 mb-1"><?php echo e($community->posts_count); ?></div>
                        <div class="text-muted small">Publicaciones</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3 bg-secondary bg-opacity-20 rounded">
                        <div class="text-success h4 mb-1"><?php echo e($community->created_at->diffForHumans()); ?></div>
                        <div class="text-muted small">Creada</div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn-community btn-community-primary flex-fill">
                    <i class="fas fa-save me-2"></i>
                    Guardar Cambios
                </button>
                <a href="<?php echo e(route('communities.show', $community)); ?>" class="btn-community btn-community-secondary">
                    <i class="fas fa-times me-2"></i>
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <!-- Zona peligrosa -->
    <div class="mt-5">
        <div class="border border-danger rounded p-4">
            <h5 class="text-danger mb-3">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Zona Peligrosa
            </h5>
            <p class="text-muted mb-3">
                Una vez que elimines la comunidad, no hay vuelta atrás. Por favor, ten cuidado.
            </p>
            <form action="<?php echo e(route('communities.destroy', $community)); ?>" method="POST" class="d-inline">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn-community btn-community-danger"
                        onclick="return confirm('¿Estás absolutamente seguro? Esta acción eliminará la comunidad y todas sus publicaciones de forma permanente.')">
                    <i class="fas fa-trash me-2"></i>
                    Eliminar Comunidad
                </button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.getElementById('cover_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\staytuned\resources\views/communities/edit.blade.php ENDPATH**/ ?>