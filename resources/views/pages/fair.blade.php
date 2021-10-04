<div class="fair-page">
    <div class="nav-fair">
      <ul class links="">
        <li class="fair data"><a class="btn">{{ __('platform.provablyfair.title') }}</a></li>
      </ul>
    </div>
    <div ng-app="" ng-init="checked = false" class="ng-scope">
		  <div class="fair-details ng-pristine ng-valid fair" action="" method="post" name="form">
          <label for="username">{{ __('platform.provablyfair.input.label') }}</label>
		  <input class="form-styling" type="text" class="input" id="hash" placeholder="{{ __('platform.provablyfair.input.placeholder') }}" value="{{$hash}}">
		  <a class="checkHash">{{ __('platform.submit.verify') }}</a>
		  <div class="col" style="display: none;">
          <label for="password">{{ __('platform.provablyfair.gamenumber') }}</label>
		  <input class="form-styling" type="text" class="input" id="round" value="" disabled>
		  </div>
		  <div class="col" style="display: none;">
			<label for="password">{{ __('platform.provablyfair.winningnumber') }}</label>
			<input class="form-styling"type="text" class="input" id="number" value="" disabled>
			</div>
		</div>
      </div>
  </div>