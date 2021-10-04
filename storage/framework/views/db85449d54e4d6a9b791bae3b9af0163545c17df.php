<div class="title flex flex-between flex-align-center">
    <span>История игр (Battle)</span>
    <a href="/battle">Назад</a>
</div>

<div class="content double">
    <div class="alert">
        <span>Активируйте Реферальный/Промо код и получите 5 рублей на баланс.</span>
        <span class="alert-close tooltip" data-close="alert" title="Закрыть">×</span>
    </div>
   <?php $__currentLoopData = $games; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="game-coin">
            <div class="top">
                <div class="left">
                    <div class="players block"> 
                        <div class="game-id">
                            <span>#<?php echo e($game->id); ?></span>
                        </div>
                        <div class="user">
							<div class="ava user-link tooltip" title="Победившая команда" <?php if($game->winner_team == 'red'): ?> style="background:linear-gradient(to top, #E77474 0%, #e77a74 100%);box-shadow: -5px 8px 1em rgba(231, 116, 116, 0.1);line-height: 45px;" <?php else: ?> style="background:linear-gradient(to top, #8a8ef9 0%, #98a7fd 100%);box-shadow: -5px 8px 1em rgba(150, 164, 252, 0.1);line-height: 45px;" <?php endif; ?>></div>
							<div class="info">
                                <span class="user-link"> <?php if($game->winner_team == 'red'): ?> Красная команда <?php else: ?> Синяя команда <?php endif; ?></span>
                                <p>Выиграли: <i class="fas fa-coins"></i> <?php echo e($game->winner_factor); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="win-ticket tooltip" title="Победный билет">
                        <span><?php echo e($game->winner_ticket); ?> <i class="fas fa-ticket-alt"></i></span>
                    </div>
                    <div class="info block">
                        <span><i class="fas fa-coins"></i> <?php echo e($game->price); ?></span>
                    </div>
                    <div class="status block">
                        <a href="/fair/<?php echo e($game->hash); ?>" class="check">Проверить</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div><?php /**PATH /var/www/html/resources/views/pages/history/battleHistory.blade.php ENDPATH**/ ?>