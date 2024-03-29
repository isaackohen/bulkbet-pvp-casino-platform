<div class="title flex flex-between flex-align-center">
    <span><?php echo e(__('platform.jackpot.history')); ?></span>
    <div class="r">
		<ul class="room-selector">
			<?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<li class="room">
				<a>
					<div class="room-name"><?php echo e($r->title); ?></div>
				</a>
			</li>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</ul>
    </div>
</div>

<div class="content">
	<?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
   <div class="historyTable">
       <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($game['room'] == $r->name): ?>
        <div class="game-coin">
            <div class="top">
                <div class="left">
                    <div class="players block"> 
                        <div class="game-id">
                            <span>#<?php echo e($game['game_id']); ?></span>
                        </div>
                        <div class="user">
                            <div class="ava user-link">
                                <img src="<?php echo e($game['winner_avatar']); ?>">
                            </div>
                            <div class="info">
                                <span class="user-link"><?php echo e($game['winner_name']); ?></span>
                                <p><?php echo e(__('platform.jackpot.winchance')); ?>: <?php echo e($game['winner_chance']); ?>%</p>
                            </div>
                        </div>
                    </div>
                    <div class="win-ticket tooltip" title="<?php echo e(__('platform.jackpot.winningticket')); ?>">
                        <span><?php echo e($game['winner_ticket']); ?> <i class="fas fa-ticket-alt"></i></span>
                    </div>
                    <div class="info block">
                        <span><i class="fas fa-coins"></i> <?php echo e($game['winner_sum']); ?></span>
                    </div>
                    <div class="status block">
                        <a href="/fair/<?php echo e($game['hash']); ?>" class="check"><?php echo e(__('platform.verifyfairness')); ?></a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
   </div>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div><?php /**PATH /home/ploi/pvp.bulk.bet/resources/views/pages/history/jackpotHistory.blade.php ENDPATH**/ ?>