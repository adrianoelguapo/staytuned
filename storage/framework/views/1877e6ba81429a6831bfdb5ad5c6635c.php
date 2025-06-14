<?php $__env->startSection('title', 'Crear Comunidad'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/community-fixed.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/playlists.css')); ?>">

    <link href="<?php echo e(asset('css/navbar-fix.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet"><?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('communities.index')); ?>" class="text-white-50">
                            <i class="fas fa-users me-1"></i>
                            Mis Comunidades
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Nueva Comunidad</li>
                </ol>
            </nav>

            <!-- Formulario de creación -->
            <div class="card create-playlist-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <i class="fas fa-plus-circle text-white me-3 fs-3"></i>
                        <h1 class="h3 mb-0 create-playlist-title">Crear Nueva Comunidad</h1>
                    </div>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('communities.store')); ?>" method="POST" enctype="multipart/form-data" class="playlist-form">
                        <?php echo csrf_field(); ?>
                        
                        <div class="row">
                            <!-- Columna izquierda - Imagen -->
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Imagen de la comunidad</label>
                                <div class="playlist-image-upload">
                                    <div class="image-preview" id="imagePreview">
                                        <div class="image-placeholder">
                                            <i class="fas fa-image fs-1"></i>
                                            <p class="mt-2 mb-0">Seleccionar imagen</p>
                                        </div>
                                    </div>
                                    <input type="file" 
                                           class="form-control mt-3" 
                                           id="cover_image"
                                           name="cover_image" 
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                    <small class="text-muted">JPG, PNG, GIF. Máximo 2MB.</small>
                                </div>
                            </div>

                            <!-- Columna derecha - Formulario -->
                            <div class="col-md-8">
                                <!-- Nombre de la comunidad -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        Nombre de la comunidad <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control <?php $__errorArgs = ['name'];
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
                                           maxlength="100" 
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
                                <div class="mb-3">
                                    <label for="description" class="form-label">Descripción</label>
                                    <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              id="description" 
                                              name="description" 
                                              rows="4" 
                                              maxlength="500"
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
                                    <small class="text-muted">Máximo 500 caracteres</small>
                                </div>

                                <!-- Privacidad -->
                                <div class="mb-4">
                                    <label class="form-label">Privacidad</label>
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="is_private" 
                                               id="public" 
                                               value="0" 
                                               <?php echo e(old('is_private', '0') == '0' ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="public">
                                            <i class="fas fa-globe me-2"></i>
                                            <strong>Pública</strong>
                                            <br>
                                            <small class="text-muted">Cualquier usuario puede encontrar y unirse a esta comunidad</small>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="is_private" 
                                               id="private" 
                                               value="1" 
                                               <?php echo e(old('is_private') == '1' ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="private">
                                            <i class="fas fa-lock me-2"></i>
                                            <strong>Privada</strong>
                                            <br>
                                            <small class="text-muted">Solo los usuarios invitados pueden ver y participar en esta comunidad</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="<?php echo e(route('communities.index')); ?>" class="btn btn-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary-playlist">
                                Crear Comunidad
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.innerHTML = `<img src="${e.target.result}" alt="Vista previa" class="img-fluid rounded">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?php $__env->stopPush(); ?>






<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\staytuned\resources\views/communities/create.blade.php ENDPATH**/ ?>