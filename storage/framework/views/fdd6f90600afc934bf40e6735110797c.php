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
            <div style="font-size:2rem; font-weight:600; line-height:1.1; color:#fff; margin-bottom:0; text-align:left;">Eliminar Cuenta</div>
            <div style="font-size:1rem; color:rgba(255,255,255,0.7); margin-bottom:2.2rem; text-align:left;">Esta acción eliminará tu cuenta y todos tus datos de forma permanente. No podrás recuperarlos después.</div>
        </div>
        <p class="text-white-50 mb-3">
            Antes de continuar, descarga toda la información que desees conservar.
        </p>
        <?php if (isset($component)) { $__componentOriginalbec74c427ea01267d1faf57b533fd04e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbec74c427ea01267d1faf57b533fd04e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirms-password','data' => ['wire:then' => 'confirmUserDeletion']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirms-password'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:then' => 'confirmUserDeletion']); ?>
            <button type="button" class="btn btn-danger">
                Eliminar Cuenta
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

        <!-- Modal de confirmación de eliminación -->
        <?php if (isset($component)) { $__componentOriginal49bd1c1dd878e22e0fb84faabf295a3f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal49bd1c1dd878e22e0fb84faabf295a3f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dialog-modal','data' => ['wire:model.live' => 'confirmingUserDeletion']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dialog-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'confirmingUserDeletion']); ?>
             <?php $__env->slot('title', null, []); ?> 
                <div class="text-white">¿Estás Seguro?</div>
             <?php $__env->endSlot(); ?>

             <?php $__env->slot('content', null, []); ?> 
                <p class="text-white">
                    Una vez eliminada, tu cuenta y todos tus datos se borrarán permanentemente. Por favor, ingresa tu contraseña para confirmar.
                </p>
                <div class="mt-3">
                    <input type="password"
                           class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> w-100"
                           placeholder="Contraseña"
                           wire:model="password"
                           wire:keydown.enter="deleteUser" />
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['password'];
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
             <?php $__env->endSlot(); ?>

             <?php $__env->slot('footer', null, []); ?> 
                <div class="d-flex align-items-center">
                    <button type="button"
                            class="btn btn-outline-light"
                            wire:click="$toggle('confirmingUserDeletion')">
                        Cancelar
                    </button>
                    <button type="button"
                            class="btn btn-danger ms-2"
                            wire:click="deleteUser">
                        Eliminar Cuenta
                    </button>
                </div>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal49bd1c1dd878e22e0fb84faabf295a3f)): ?>
<?php $attributes = $__attributesOriginal49bd1c1dd878e22e0fb84faabf295a3f; ?>
<?php unset($__attributesOriginal49bd1c1dd878e22e0fb84faabf295a3f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal49bd1c1dd878e22e0fb84faabf295a3f)): ?>
<?php $component = $__componentOriginal49bd1c1dd878e22e0fb84faabf295a3f; ?>
<?php unset($__componentOriginal49bd1c1dd878e22e0fb84faabf295a3f); ?>
<?php endif; ?>
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
<?php /**PATH C:\laragon\www\staytuned\resources\views/profile/delete-user-form.blade.php ENDPATH**/ ?>