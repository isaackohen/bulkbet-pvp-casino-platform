<div class="popup popup-withdraw">
	<div class="modal-title flex flex-between flex-align-center">
		<span><?php echo e(__('platform.withdrawmodal.title')); ?></span>
		<a href="#" class="close"><i class="fas fa-times"></i></a>
	</div>
	<div class="modal-content">
		<div class="banner">
			<?php echo e(__('platform.withdrawmodal.warning.depositrequired')); ?>

		</div>
		<div class="banner">
			    <?php echo e(__('platform.withdrawmodal.warning.processingtime')); ?>

		</div>
		<div class="bx-input">
			<h4><?php echo e(__('platform.withdrawmodal.method')); ?></h4>
			<div class="list-pay clear">
				<a class="item active" data-type="qiwi"><img src="/assets/images/img-qiwi.png" alt=""></a>
				<a class="item" data-type="yandex"><img src="/assets/images/img-yandex.png" alt=""></a>
				<a class="item" data-type="webmoney"><img src="/assets/images/img-webm.png" alt=""></a>
				<a class="item" data-type="visa"><img src="/assets/images/img-visa.png" alt=""></a>
			</div>
			<div class="bx-input">
				<h4><?php echo e(__('platform.amount')); ?></h4>
				<input type="text" id="value" class="input-sum" placeholder="<?php echo e(__('platform.withdrawmodal.amount.placeholder')); ?>">
			</div>
			<div class="bx-input">
				<h4><?php echo e(__('platform.walletaddress')); ?></h4>
				<input type="text" id="wallet" class="input-num" placeholder="<?php echo e(__('platform.withdrawmodal.wallet.placeholder')); ?>">
			</div>
			<div class="bx-input">
				<span><?php echo e(__('platform.withdrawmodal.processingcost')); ?> <b id="com">5%</b></span>
				<span><?php echo e(__('platform.withdrawmodal.netamount')); ?> <b id="valwithcom">0.00 руб.</b></span>
			</div>
			<div class="bx-input">
				<input id="chh" type="checkbox">
				<label class="check" for="chh"><?php echo e(__('platform.withdrawmodal.checkbox')); ?></label>
			</div>
			<div class="bx-input">
				<button type="submit" id="withdraw"><?php echo e(__('platform.withdrawmodal.button.submit')); ?></button>
				<a href="/pay/history"><?php echo e(__('platform.withdrawmodal.button.history')); ?></a>
			</div>
		</div>
	</div>
</div>

<?php /**PATH /home/ploi/pvp.bulk.bet/resources/views/modals/withdraw.blade.php ENDPATH**/ ?>