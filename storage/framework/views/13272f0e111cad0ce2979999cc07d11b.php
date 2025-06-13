<?php if (isset($component)) { $__componentOriginal1a4a318d932e02d86670f282a316cd31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1a4a318d932e02d86670f282a316cd31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.action-section','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('action-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?>  <?php $__env->endSlot(); ?>
     <?php $__env->slot('description', null, []); ?>  <?php $__env->endSlot(); ?>

     <?php $__env->slot('content', null, []); ?> 
        <div class="mb-4">
            <div style="font-size:2rem; font-weight:600; line-height:1.1; color:#fff; margin-bottom:0; text-align:left;">Autenticación de Dos Factores</div>
            <div style="font-size:1rem; color:rgba(255,255,255,0.7); margin-bottom:2.2rem; text-align:left;">Añade una capa extra de seguridad a tu cuenta activando la autenticación en dos pasos.</div>
        </div>

        <h3 class="mb-3 text-white">
            <!--[if BLOCK]><![endif]--><?php if($this->enabled): ?>
                <!--[if BLOCK]><![endif]--><?php if($showingConfirmation): ?>
                    Termina de habilitar la autenticación de dos factores.
                <?php else: ?>
                    La autenticación de dos factores está habilitada.
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <?php else: ?>
                No has habilitado la autenticación de dos factores.
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </h3>

        <div class="mb-4">
            <p class="text-white-50">
                Cuando la autenticación de dos factores esté habilitada, se te pedirá un token aleatorio al iniciar sesión. Podrás obtenerlo en la app de autenticación de tu teléfono (por ejemplo, Google Authenticator).
            </p>
        </div>

        <!--[if BLOCK]><![endif]--><?php if($this->enabled): ?>
            <!--[if BLOCK]><![endif]--><?php if($showingQrCode): ?>
                <div class="mb-4">
                    <p class="text-white">
                        <!--[if BLOCK]><![endif]--><?php if($showingConfirmation): ?>
                            Escanea este código QR con tu app de autenticación o ingresa la clave de configuración y luego proporciona el código OTP generado.
                        <?php else: ?>
                            La autenticación de dos factores ya está habilitada. Escanea el siguiente código QR con tu app de autenticación o ingresa la clave de configuración.
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </p>
                </div>

                <div class="mb-4 p-3 bg-white d-inline-block">
                    <?php echo $this->user->twoFactorQrCodeSvg(); ?>

                </div>

                <div class="mb-4">
                    <p class="text-white">
                        <strong>Clave de Configuración:</strong> <?php echo e(decrypt($this->user->two_factor_secret)); ?>

                    </p>
                </div>

                <!--[if BLOCK]><![endif]--><?php if($showingConfirmation): ?>
                    <div class="mb-4">
                        <label for="code" class="form-label text-white">Código</label>
                        <input id="code" type="text" name="code"
                               class="form-control <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> w-50"
                               wire:model="code"
                               wire:keydown.enter="confirmTwoFactorAuthentication"
                               inputmode="numeric" autofocus autocomplete="one-time-code" />
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><?php if($showingRecoveryCodes): ?>
                <div class="mb-4">
                    <p class="text-white">
                        Guarda estos códigos de recuperación en un lugar seguro. Podrán usarse si pierdes el acceso a tu autenticador.
                    </p>
                </div>

                <div class="mb-4 px-4 py-3 bg-white text-monospace text-dark rounded">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = json_decode(decrypt($this->user->two_factor_recovery_codes), true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div><?php echo e($code); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <div class="d-flex gap-2 flex-wrap">
            <!--[if BLOCK]><![endif]--><?php if(! $this->enabled): ?>
                <?php if (isset($component)) { $__componentOriginalbec74c427ea01267d1faf57b533fd04e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbec74c427ea01267d1faf57b533fd04e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirms-password','data' => ['wire:then' => 'enableTwoFactorAuthentication']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirms-password'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:then' => 'enableTwoFactorAuthentication']); ?>
                    <button type="button" class="btn btn-primary">
                        Habilitar
                    </button>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbec74c427ea01267d1faf57b533fd04e)): ?>
<?php $attributes = $__attributesOriginalbec74c427ea01267d1faf57b533fd04e; ?>
<?php unset($__attributesOriginalbec74c427ea01267d1faf57b533fd04e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbec74c427ea01267d1faf57b533fd04e)): ?>
<?php $component = $__componentOriginalbec74c427ea01267d1faf57b533fd04e; ?>
<?php unset($__componentOriginalbec74c427ea01267d1faf57b533fd04e); ?>
<?php endif; ?>
            <?php else: ?>
                <!--[if BLOCK]><![endif]--><?php if($showingRecoveryCodes): ?>
                    <?php if (isset($component)) { $__componentOriginalbec74c427ea01267d1faf57b533fd04e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbec74c427ea01267d1faf57b533fd04e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirms-password','data' => ['wire:then' => 'regenerateRecoveryCodes']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirms-password'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:then' => 'regenerateRecoveryCodes']); ?>
                        <button type="button" class="btn btn-outline-light">
                            Regenerar Códigos
                        </button>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbec74c427ea01267d1faf57b533fd04e)): ?>
<?php $attributes = $__attributesOriginalbec74c427ea01267d1faf57b533fd04e; ?>
<?php unset($__attributesOriginalbec74c427ea01267d1faf57b533fd04e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbec74c427ea01267d1faf57b533fd04e)): ?>
<?php $component = $__componentOriginalbec74c427ea01267d1faf57b533fd04e; ?>
<?php unset($__componentOriginalbec74c427ea01267d1faf57b533fd04e); ?>
<?php endif; ?>
                <?php elseif($showingConfirmation): ?>
                    <?php if (isset($component)) { $__componentOriginalbec74c427ea01267d1faf57b533fd04e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbec74c427ea01267d1faf57b533fd04e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirms-password','data' => ['wire:then' => 'confirmTwoFactorAuthentication']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirms-password'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:then' => 'confirmTwoFactorAuthentication']); ?>
                        <button type="button" class="btn btn-primary me-2">
                            Confirmar
                        </button>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbec74c427ea01267d1faf57b533fd04e)): ?>
<?php $attributes = $__attributesOriginalbec74c427ea01267d1faf57b533fd04e; ?>
<?php unset($__attributesOriginalbec74c427ea01267d1faf57b533fd04e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbec74c427ea01267d1faf57b533fd04e)): ?>
<?php $component = $__componentOriginalbec74c427ea01267d1faf57b533fd04e; ?>
<?php unset($__componentOriginalbec74c427ea01267d1faf57b533fd04e); ?>
<?php endif; ?>
                <?php else: ?>
                    <?php if (isset($component)) { $__componentOriginalbec74c427ea01267d1faf57b533fd04e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbec74c427ea01267d1faf57b533fd04e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirms-password','data' => ['wire:then' => 'showRecoveryCodes']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirms-password'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:then' => 'showRecoveryCodes']); ?>
                        <button type="button" class="btn btn-outline-light me-2">
                            Mostrar Códigos
                        </button>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbec74c427ea01267d1faf57b533fd04e)): ?>
<?php $attributes = $__attributesOriginalbec74c427ea01267d1faf57b533fd04e; ?>
<?php unset($__attributesOriginalbec74c427ea01267d1faf57b533fd04e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbec74c427ea01267d1faf57b533fd04e)): ?>
<?php $component = $__componentOriginalbec74c427ea01267d1faf57b533fd04e; ?>
<?php unset($__componentOriginalbec74c427ea01267d1faf57b533fd04e); ?>
<?php endif; ?>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><?php if($showingConfirmation): ?>
                    <?php if (isset($component)) { $__componentOriginalbec74c427ea01267d1faf57b533fd04e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbec74c427ea01267d1faf57b533fd04e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirms-password','data' => ['wire:then' => 'disableTwoFactorAuthentication']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirms-password'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:then' => 'disableTwoFactorAuthentication']); ?>
                        <button type="button" class="btn btn-outline-light">
                            Cancelar
                        </button>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbec74c427ea01267d1faf57b533fd04e)): ?>
<?php $attributes = $__attributesOriginalbec74c427ea01267d1faf57b533fd04e; ?>
<?php unset($__attributesOriginalbec74c427ea01267d1faf57b533fd04e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbec74c427ea01267d1faf57b533fd04e)): ?>
<?php $component = $__componentOriginalbec74c427ea01267d1faf57b533fd04e; ?>
<?php unset($__componentOriginalbec74c427ea01267d1faf57b533fd04e); ?>
<?php endif; ?>
                <?php else: ?>
                    <?php if (isset($component)) { $__componentOriginalbec74c427ea01267d1faf57b533fd04e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbec74c427ea01267d1faf57b533fd04e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirms-password','data' => ['wire:then' => 'disableTwoFactorAuthentication']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirms-password'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:then' => 'disableTwoFactorAuthentication']); ?>
                        <button type="button" class="btn btn-danger">
                            Deshabilitar
                        </button>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbec74c427ea01267d1faf57b533fd04e)): ?>
<?php $attributes = $__attributesOriginalbec74c427ea01267d1faf57b533fd04e; ?>
<?php unset($__attributesOriginalbec74c427ea01267d1faf57b533fd04e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbec74c427ea01267d1faf57b533fd04e)): ?>
<?php $component = $__componentOriginalbec74c427ea01267d1faf57b533fd04e; ?>
<?php unset($__componentOriginalbec74c427ea01267d1faf57b533fd04e); ?>
<?php endif; ?>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
     <?php $__env->endSlot(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1a4a318d932e02d86670f282a316cd31)): ?>
<?php $attributes = $__attributesOriginal1a4a318d932e02d86670f282a316cd31; ?>
<?php unset($__attributesOriginal1a4a318d932e02d86670f282a316cd31); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1a4a318d932e02d86670f282a316cd31)): ?>
<?php $component = $__componentOriginal1a4a318d932e02d86670f282a316cd31; ?>
<?php unset($__componentOriginal1a4a318d932e02d86670f282a316cd31); ?>
<?php endif; ?>





<?php /**PATH C:\laragon\www\staytuned\resources\views/profile/two-factor-authentication-form.blade.php ENDPATH**/ ?>