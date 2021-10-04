<div class="popup popup-pay">
	<div class="modal-title flex flex-between flex-align-center">
		<span><?php echo e(__('platform.deposit.header')); ?></span>
		<a href="#" class="close"><i class="fas fa-times"></i></a>
	</div>
	<div class="modal-content">
		<div class="banner"><?php echo e(__('platform.deposit.minimum.label')); ?> <?php echo e(__('platform.deposit.minimum.amount')); ?></div>
		<form action="/pay" method="GET">
			<div class="bx-input">
				<h4><?php echo e(__('platform.deposit.input.label')); ?></h4>
				<input type="text" class="input-sum" name="num" min="0"  placeholder="<?php echo e(__('platform.deposit.input.placeholder')); ?>">
			</div>
			<div class="bx-input">
				<button type="submit"><?php echo e(__('platform.deposit.button.submit')); ?></button>
				<a href="/pay/history"><?php echo e(__('platform.deposit.button.history')); ?></a>
			</div>
		</form>
	</div>
</div>

<?php /**PATH /home/ploi/pvp.bulk.bet/resources/views/modals/deposit.blade.php ENDPATH**/ ?>