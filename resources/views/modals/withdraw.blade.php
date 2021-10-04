<div class="popup popup-withdraw">
	<div class="modal-title flex flex-between flex-align-center">
		<span>Вывести баланс</span>
		<a href="#" class="close"><i class="fas fa-times"></i></a>
	</div>
	<div class="modal-content">
		<div class="banner">
			Вывод доступен после пополнения счета на 50р!
		</div>
		<div class="banner">
			Обработка вывода обычно осуществляется в течении часа. В некоторых случаях платеж может быть обработан до 24 часов. Минимальная сумма к выводу <b>105</b> монет.
		</div>
		<div class="bx-input">
			<h4>Выберите платежную систему:</h4>
			<div class="list-pay clear">
				<a class="item active" data-type="qiwi"><img src="/assets/images/img-qiwi.png" alt=""></a>
				<a class="item" data-type="yandex"><img src="/assets/images/img-yandex.png" alt=""></a>
				<a class="item" data-type="webmoney"><img src="/assets/images/img-webm.png" alt=""></a>
				<a class="item" data-type="visa"><img src="/assets/images/img-visa.png" alt=""></a>
			</div>
			<div class="bx-input">
				<h4>Сумма</h4>
				<input type="text" id="value" class="input-sum" placeholder="Мин.сумма: 105 монет.">
			</div>
			<div class="bx-input">
				<h4>Номер кошелька</h4>
				<input type="text" id="wallet" class="input-num" placeholder="7900xxxxxxx">
			</div>
			<div class="bx-input">
				<span>Комиссия: <b id="com">5%</b></span>
				<span>Итого к получению: <b id="valwithcom">0.00 руб.</b></span>
			</div>
			<div class="bx-input">
				<input id="chh" type="checkbox">
				<label class="check" for="chh">Я подтверждаю правильность введенных данных</label>
			</div>
			<div class="bx-input">
				<button type="submit" id="withdraw">Вывести</button>
				<a href="/pay/history">История выводов</a>
			</div>
		</div>
	</div>
</div>