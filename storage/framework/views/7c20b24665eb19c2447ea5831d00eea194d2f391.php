<div class="title flex flex-between flex-align-center">
    <span>История игр (Double)</span>
    <a href="/double">Назад</a>
</div>

<div class="content double">
    <div class="alert">
        <span>Активируйте Реферальный/Промо код и получите 5 рублей на баланс.</span>
        <span class="alert-close tooltip" data-close="alert" title="Закрыть">×</span>
    </div>
   <?php $__currentLoopData = $games; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="game-coin" style="width: 32.3%;margin: 5px 5px;">
        <div class="top">
            <div class="left">
                <div class="players block"> 
                    <div class="game-id">
                        <span>#<?php echo e($game->id); ?></span>
                    </div>
                    <div class="user">
                        <div class="ava user-link tooltip" title="Победное число" <?php if($game->winner_color == 'red'): ?> style="background:linear-gradient(to top, #e79f74 0%, #e77a74 100%);box-shadow: -5px 8px 1em rgba(231, 116, 116, 0.1);line-height: 45px;" <?php elseif($game->winner_color == 'green'): ?> style="background:linear-gradient(to top, #87f9c4 0%, #74c1c9 100%);box-shadow: -5px 8px 1em rgba(231, 116, 116, 0.1);line-height: 45px;" <?php else: ?> style="background:linear-gradient(to top, #8a8ef9 0%, #98a7fd 100%);box-shadow: -5px 8px 1em rgba(150, 164, 252, 0.1);line-height: 45px;" <?php endif; ?>> <span style="color: #fff;"><?php echo e($game->winner_num); ?></span></div>
                    </div>
                </div>
                <div class="status block"  style="border: 0;">
                    <a href="/fair/<?php echo e($game->hash); ?>" class="check">Проверить</a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div><?php /**PATH /var/www/html/resources/views/pages/history/doubleHistory.blade.php ENDPATH**/ ?>