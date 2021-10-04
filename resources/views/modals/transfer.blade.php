<div class="popup popup-transfer">
    <div class="modal-title flex flex-between flex-align-center">
        <span>{{ __('platform.transfermodal.title') }}</span>
        <a href="#" class="close"><i class="fas fa-times"></i></a>
    </div>
    <div class="modal-content">
        <div class="banner">{{ __('platform.transfermodal.minimum.label') }} {{ __('platform.transfermodal.minimum.amount') }}</div>
        <div class="banner">{{ __('platform.transfermodal.maximum.label') }} {{ __('platform.transfermodal.maximum.amount') }}</div>
        <div id="recipient" class="banner"></div>
            <div class="bx-input">
                <h4>{{ __('platform.transfermodal.input.label') }}</h4>
                <input id="transfer_sum" type="text" class="input-sum" name="num" min="0"  placeholder="{{ __('platform.transfermodal.input.placeholder') }}">
                <input style="display:none;" id="recipient_id" value="">
            </div>
            <div class="bx-input">
                <button id="send_transfer" type="submit">{{ __('platform.transfermodal.button.submit') }}</button>
            </div>
    </div>
</div>