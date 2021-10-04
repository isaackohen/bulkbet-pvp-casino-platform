<div class="popup popup-mute">
    <div class="modal-title flex flex-between flex-align-center">
        <span>{{ __('platform.admin.mutemodal.title') }}</span>
        <a href="#" class="close"><i class="fas fa-times"></i></a>
    </div>
    <div class="modal-content">
        <div id="mute_user" class="banner"></div>
            <div class="bx-input">
                <h4>{{ __('platform.admin.mutemodal.input.label') }}</h4>
                <input id="mute_time" type="text" class="input-sum" name="num" min="0"  placeholder="{{ __('platform.admin.mutemodal.input.placeholder') }}">
                <input style="display:none;" id="mute_id" value="">
            </div>
            <div class="bx-input">
                <button id="send_mute" type="submit">{{ __('platform.admin.mutemodal.submit') }}</button>
            </div>
    </div>
</div>