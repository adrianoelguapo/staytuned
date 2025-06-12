<!DOCTYPE html>
<html lang = "es">

    <head>

        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width = device-width, initial-scale = 1.0">
        <title>Iniciar sesión | StayTuned</title>
        <link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel = "stylesheet">
        <link href = "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel = "stylesheet">
        <link href = "<?php echo e(asset('css/auth.css')); ?>" rel = "stylesheet">

    </head>

    <body class = "login-body">

        <nav class = "navbar navbar-expand-lg px-3 px-lg-5 py-3">

            <a class = "navbar-brand text-white fw-bold" href = "<?php echo e(url('/')); ?>"> StayTuned</a>

            <?php if(Route::has('login')): ?>

                <div class = "ms-auto ms-lg-auto d-flex align-items-center gap-2">

                    <?php if(auth()->guard()->check()): ?>

                        <a href = "<?php echo e(url('/dashboard')); ?>" class = "btn btn-outline-light small-button">Dashboard</a>

                    <?php else: ?>

                        <a href = "<?php echo e(route('login')); ?>" class = "btn btn-outline-light small-button">Inicia Sesión</a>

                        <?php if(Route::has('register')): ?>

                            <a href = "<?php echo e(route('register')); ?>" class = "btn btn-light small-button">Regístrate</a>

                        <?php endif; ?>

                    <?php endif; ?>

                </div>

            <?php endif; ?>

        </nav>

        <div class = "login-container d-flex flex-column flex-lg-row align-items-stretch pb-4">

            <div class = "login-left d-flex flex-column justify-content-center px-5 py-4">

                <div class = "login-box w-100">

                    <h1 class = "fw-bold text-white mb-2">Bienvenido de nuevo</h1>

                    <p class = "mb-4 text-white-50">Ingresa con tu cuenta para continuar.</p>

                    <?php if($errors->any()): ?>

                        <div class = "validation-errors mb-3">

                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <p class = "text-white mb-1"><?php echo e($error); ?></p>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>

                    <?php endif; ?>

                    <form method = "POST" action = "<?php echo e(route('login')); ?>">

                        <?php echo csrf_field(); ?>

                        <div class = "mb-3">

                            <label for = "email" class = "form-label text-white">Correo electrónico</label>
                            <input id = "email" class = "form-control" type = "email" name = "email" value = "<?php echo e(old('email')); ?>" required autofocus>
                        
                        </div>

                        <div class = "mb-3">

                            <label for = "password" class = "form-label text-white">Contraseña</label>
                            <input id = "password" class = "form-control" type = "password" name = "password" required autocomplete = "current-password">
                        
                        </div>

                        <div class = "d-flex justify-content-between align-items-center mb-4">

                            <div class = "form-check">

                                <input type = "checkbox" class = "form-check-input" id = "remember_me" name = "remember">
                                <label class = "form-check-label text-white" for = "remember_me">Recordarme</label>
                            
                            </div>

                            <a href = "<?php echo e(route('password.request')); ?>" class = "text-decoration-underline text-white-50 small">¿Olvidaste tu contraseña?</a>
                        
                        </div>

                        <button type = "submit" class = "btn submit-btn btn-outline-light rounded-5 py-3 w-100">Entrar</button>

                        <div class = "text-center mt-4 text-white-50 small">
                            
                            ¿No tienes una cuenta? <a href = "<?php echo e(route('register')); ?>" class = "text-decoration-underline text-white">Regístrate</a>
                        
                        </div>

                    </form>

                </div>

            </div>

            <div class = "login-right d-none d-lg-flex justify-content-center align-items-center p-4">

                <img src = "<?php echo e(asset('img/login7.png')); ?>" alt = "Ilustración de acceso" class = "img-fluid me-5">

            </div>

        </div>

    </body>

</html>
<?php /**PATH C:\laragon\www\staytuned\resources\views/auth/login.blade.php ENDPATH**/ ?>