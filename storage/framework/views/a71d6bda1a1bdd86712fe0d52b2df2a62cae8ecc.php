

<?php $__env->startSection('code', '500 😭'); ?>
<?php $__env->startSection('title', __('500 | Ошибочка')); ?>

<?php $__env->startSection('image'); ?>
<div style="background-image: url('/assets/images/new-logo-loto.png');" class="absolute pin bg-no-repeat md:bg-left lg:bg-center">
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('message', __('Упс, что-то пошло не так.')); ?>
<?php echo $__env->make('errors::illustrated-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/errors/500.blade.php ENDPATH**/ ?>