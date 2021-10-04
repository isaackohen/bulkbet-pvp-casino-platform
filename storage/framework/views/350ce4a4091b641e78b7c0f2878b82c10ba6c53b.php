<div class="popup popup-pay">
	<div class="modal-title flex flex-between flex-align-center">
		<span>Пополнить баланс</span>
		<a href="#" class="close"><i class="fas fa-times"></i></a>
	</div>
	<div class="modal-content">
		<div class="banner">Минимальная сумма: 1р</div>
		<div class="banner">Максимальная сумма: 15000р</div>
		<form action="/pay" method="GET">
			<div class="bx-input">
				<h4>Введите сумму платежа</h4>
				<input type="text" class="input-sum" name="num" min="0"  placeholder="Введите сумму...">
			</div>
			<div class="bx-input">
				<button type="submit">Оплатить</button>
				<a href="/pay/history">История пополнений</a>
			</div>
		</form>
	</div>
</div><?php /**PATH /var/www/html/resources/views/modals/deposit.blade.php ENDPATH**/ ?>