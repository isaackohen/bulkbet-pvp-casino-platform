<div class="popup popup-transfer">
	<div class="modal-title flex flex-between flex-align-center">
		<span>Перевести деньги</span>
		<a href="#" class="close"><i class="fas fa-times"></i></a>
	</div>
	<div class="modal-content">
		<div class="banner">Минимальная сумма: 1р</div>
		<div class="banner">Максимальная сумма: 15000р</div>
		<div id="recipient" class="banner"></div>
			<div class="bx-input">
				<h4>Введите сумму перевода</h4>
				<input id="transfer_sum" type="text" class="input-sum" name="num" min="0"  placeholder="Введите сумму...">
				<input style="display:none;" id="recipient_id" value="">
			</div>
			<div class="bx-input">
				<button id="send_transfer" type="submit">Отправить</button>
			</div>
	</div>
</div><?php /**PATH /var/www/html/resources/views/modals/transfer.blade.php ENDPATH**/ ?>