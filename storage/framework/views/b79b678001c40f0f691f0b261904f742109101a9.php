<div class="title flex flex-between flex-align-center">
    <span>История игр (PVP)</span>
    <a href="/pvp">Назад</a>
</div>

<div class="content pvp">
    <div class="alert">
        <span>Активируйте Реферальный/Промо код и получите 5 рублей на баланс.</span>
        <span class="alert-close tooltip" data-close="alert" title="Закрыть">×</span>
    </div>
   <?php $__currentLoopData = $games; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="game-coin flip_block_<?php echo e($game->id); ?>">
                    <div class="top">
                        <div class="left">
                            <div class="players block">
                                <div class="user">
                                    <div class="ava user-link">
                                        <img src="<?php echo e(\App\User::find($game->user1)->avatar); ?>">
                                    </div>
                                    <div class="info">
                                        <span class="user-link"><?php echo e(\App\User::find($game->user1)->username); ?></span>
                                        <p><?php echo e($game->from2); ?> - <?php echo e($game->to2); ?> <i class="fas fa-ticket-alt"></i></p>
                                    </div>
                                </div>
                                <div class="vs">vs</div>
                                <div class="user">
                                    <div class="ava user-link">
                                        <img src="<?php echo e(\App\User::find($game->user2)->avatar); ?>">
                                    </div>
                                    <div class="info">
                                        <span class="user-link"><?php echo e(\App\User::find($game->user2)->username); ?></span>
                                        <p><?php echo e($game->from2); ?> - <?php echo e($game->to2); ?> <i class="fas fa-ticket-alt"></i></p>
                                    </div>
                                </div>
                            </div>
                            <div class="avatars">
                                <div class="tridiv">
                                    <div class="time" id="timer_<?php echo e($game->id); ?>" style="display:none;">
                                        <span id="count_num_<?php echo e($game->id); ?>">0</span>
                                    </div>
                                    <div id="coin-flip-cont_<?php echo e($game->id); ?>" style="">
                                        <div id="coin_<?php echo e($game->id); ?>">
                                            <div class="front winner_a"><img src="<?php echo e(\App\User::find($game->winner_id)->avatar); ?>"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="win-ticket tooltip" title="Счастливый билет">
                                <span><?php echo e($game->winner_ticket); ?> <i class="fas fa-ticket-alt"></i></span>
                            </div>
                            <div class="info block">
                                <span><i class="fas fa-coins"></i> <?php echo e($game->price); ?></span>
                            </div>
							<div class="status block"  style="border: 0;">
								<a href="/fair/<?php echo e($game->hash); ?>" class="check">Проверить</a>
							</div>
                        </div>
                    </div>
                </div>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div><?php /**PATH /var/www/html/resources/views/pages/history/pvpHistory.blade.php ENDPATH**/ ?>