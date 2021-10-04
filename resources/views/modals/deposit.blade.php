<div class="popup popup-pay">
	<div class="modal-title flex flex-between flex-align-center">
		<span>{{ __('platform.deposit.header') }}</span>
		<a href="#" class="close"><i class="fas fa-times"></i></a>
	</div>
	<div class="modal-content">
		<div class="banner">{{ __('platform.deposit.minimum.label') }} {{ __('platform.deposit.minimum.amount') }}</div>
		<form action="/pay" method="GET">
			<div class="bx-input">
				<h4>{{ __('platform.deposit.input.label') }}</h4>
				<input type="text" class="input-sum" name="num" min="0"  placeholder="{{ __('platform.deposit.input.placeholder') }}">
			</div>
			<div class="bx-input">
				<button type="submit">{{ __('platform.deposit.button.submit') }}</button>
				<a href="/pay/history">{{ __('platform.deposit.button.history') }}</a>
			</div>
		</form>
	</div>
</div>

