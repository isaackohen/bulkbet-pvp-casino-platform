<div class="popup popup-transfer">
    <div class="modal-title flex flex-between flex-align-center">
        <span><?php echo e(__('platform.transfermodal.title')); ?></span>
        <a href="#" class="close"><i class="fas fa-times"></i></a>
    </div>
    <div class="modal-content">
        <div class="banner"><?php echo e(__('platform.transfermodal.minimum.label')); ?> <?php echo e(__('platform.transfermodal.minimum.amount')); ?></div>
        <div class="banner"><?php echo e(__('platform.transfermodal.maximum.label')); ?> <?php echo e(__('platform.transfermodal.maximum.amount')); ?></div>
        <div id="recipient" class="banner"></div>
            <div class="bx-input">
                <h4><?php echo e(__('platform.transfermodal.input.label')); ?></h4>
                <input id="transfer_sum" type="text" class="input-sum" name="num" min="0"  placeholder="<?php echo e(__('platform.transfermodal.input.placeholder')); ?>">
                <input style="display:none;" id="recipient_id" value="">
            </div>
            <div class="bx-input">
                <button id="send_transfer" type="submit"><?php echo e(__('platform.transfermodal.button.submit')); ?></button>
            </div>
    </div>
</div><?php /**PATH /home/ploi/pvp.bulk.bet/resources/views/modals/transfer.blade.php ENDPATH**/ ?>