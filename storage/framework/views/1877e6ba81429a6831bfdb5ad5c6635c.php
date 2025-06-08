

<?php $__env->startSection('title', 'Crear Comunidad'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/community-fixed.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-xl">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex align-items-center mb-2">
            <a href="<?php echo e(route('communities.index')); ?>" class="text-success me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-white mb-0">
                <i class="fas fa-plus text-success me-2"></i>
                Crear Nueva Comunidad
            </h2>
        </div>
        <p class="text-muted">Crea un espacio único para compartir música con personas afines</p>
    </div>

    <!-- Formulario -->
    <div class="community-form">
        <form action="<?php echo e(route('communities.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
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
                       value="<?php echo e(old('name')); ?>"
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
                          placeholder="Describe de qué trata tu comunidad, qué tipo de música comparten, reglas, etc."><?php echo e(old('description')); ?></textarea>
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

            <!-- Imagen de portada -->
            <div class="community-form-group">
                <label for="cover_image" class="community-form-label">
                    <i class="fas fa-image me-2"></i>
                    Imagen de Portada
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
                <small class="text-muted">Formatos soportados: JPEG, PNG, JPG, GIF. Tamaño máximo: 2MB</small>
            </div>

            <!-- Vista previa de imagen -->
            <div id="image-preview" style="display: none;" class="community-form-group">
                <img id="preview-img" src="" alt="Vista previa" style="max-width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
            </div>

            <!-- Privacidad -->
            <div class="community-form-check">
                <input type="checkbox" 
                       id="is_private" 
                       name="is_private" 
                       value="1"
                       <?php echo e(old('is_private') ? 'checked' : ''); ?>>
                <label for="is_private">
                    <i class="fas fa-lock me-2"></i>
                    Hacer esta comunidad privada
                </label>
            </div>
            <small class="text-muted d-block mt-2">
                Las comunidades privadas solo son visibles para los miembros invitados
            </small>

            <!-- Botones -->
            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn-community btn-community-primary flex-fill">
                    <i class="fas fa-plus me-2"></i>
                    Crear Comunidad
                </button>
                <a href="<?php echo e(route('communities.index')); ?>" class="btn-community btn-community-secondary">
                    <i class="fas fa-times me-2"></i>
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <!-- Información adicional -->
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="bg-dark border border-secondary rounded p-4">
                <h5 class="text-success mb-3">
                    <i class="fas fa-lightbulb me-2"></i>
                    Consejos para tu Comunidad
                </h5>
                <ul class="text-muted mb-0">
                    <li class="mb-2">Elige un nombre descriptivo y atractivo</li>
                    <li class="mb-2">Describe claramente el género o tema musical</li>
                    <li class="mb-2">Establece reglas claras para las publicaciones</li>
                    <li class="mb-2">Usa una imagen de portada llamativa</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="bg-dark border border-secondary rounded p-4">
                <h5 class="text-info mb-3">
                    <i class="fas fa-users me-2"></i>
                    ¿Privada o Pública?
                </h5>
                <div class="text-muted">
                    <p class="mb-2"><strong>Pública:</strong> Cualquier usuario puede encontrar y unirse</p>
                    <p class="mb-0"><strong>Privada:</strong> Solo los usuarios invitados pueden ver y participar</p>
                </div>
            </div>
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

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\staytuned\resources\views/communities/create.blade.php ENDPATH**/ ?>