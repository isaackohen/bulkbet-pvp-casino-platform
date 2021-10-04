<div class="title flex flex-between flex-align-center">
    <span>{{ __('platform.bonus.title') }}</span>
</div>

<div class="content">
   @if($line)
       <div class="info-bonus">
        <div class="heading">{{ __('platform.bonus.cooldownwarning') }}</div>
        <div class="desc">{{ __('platform.bonus.telegrambotwarning') }} &nbsp;<a href="https://t.me/techmain_auth_bot" target="_blank"><i class="fab fa-telegram-plane"></i> Telegram Bot</a>.</div>
       </div>
    <div class="slider flex flex-center flex-align-center">
        <div class="arrow top"></div>
        <div class="carousel bonus">
			<div class="cooldown" style="{{ $check ? '' : 'display: none;' }}">
				<div class="head">{{ __('platform.bonus.issued') }}</div>
			</div>
           <div class="fixed-width">
                <div class="scroll" id="bonus_carousel" style="width: 700000px;">
                  @foreach($line as $l)
                   <div class="user" style="background: #495168;border-radius: 5px;">
                       <div class="summ">
                           <b>{{$l['sum']}}</b>
                           <p>{{trans_choice('Coin|Coins|Coins', $l['sum'])}}</p>
                       </div>
                   </div>
                   @endforeach
                </div>
           </div>
        </div>
        <div class="arrow bottom"></div>
    </div>
    @endif
    <div class="captch">
        {!! NoCaptcha::renderJs() !!}
        {!! NoCaptcha::display() !!}
    </div>
    <div class="button">
        <a class="getBonus">{{ __('platform.bonus.spins') }}</a>
    </div>
</div>