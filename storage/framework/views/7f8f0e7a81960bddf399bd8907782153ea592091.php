<?php $__env->startSection('code', '404'); ?>

<?php $__env->startSection('title', __('404 | Страница не найдена')); ?>

<?php $__env->startSection('image'); ?>

<div style="background-image: url('/assets/images/new-logo-loto.png');" class="absolute pin bg-no-repeat md:bg-left lg:bg-center">
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('message', __('К сожалению, страница, которую вы ищете, не существует.')); ?>
<?php echo $__env->make('errors::illustrated-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ploi/pvp.bulk.bet/resources/views/errors/404.blade.php ENDPATH**/ ?>