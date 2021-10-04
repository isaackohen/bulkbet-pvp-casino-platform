<div class="head-game">
	<span class="game-name">Jackpot</span>
	<ul>
		<li><a href="/jackpot/history">{{ __('platform.jackpot.history') }}</a></li>
		<li><a href="/">{{ __('platform.back') }}</a></li>
	</ul>
</div>
<div class="game-info">
	<div class="game-id">Game: <span>{{$history->game_id}}</span></div><span class="fair" rel="popup" data-popup="popup-fair">Fair Game</span>
</div>
<div class="jackpot">
	<div class="bank">Jackpot игры: <span>{{$history->price}}</span><i class="fas fa-coins"></i></div>
	<div class="second-title"><span>Игроки</span></div>
	<ul class="chances" id="chances">
	@foreach($historyChance as $user)
		<li class="tooltip" title="{{ $user['username'] }}">
			<img src="{{ $user['avatar'] }}" alt="">
			<span>{{ $user['chance'] }}%</span>
			<color style="background: #{{ $user['color'] }};"></color>
		</li>
	@endforeach
	</ul>
	<div class="timer">
		<div class="timer-title">До начала</div>
		<div class="timer-bar">
			<div class="time">
				<div class="elements">
					<span class="minsec">00:00</span>
				</div>
			</div>
			<span class="timer-bar-fill" style="width: 0%" id="timeline"></span>  
		</div>
	</div>
	<div class="winner">
		<div class="second-title"><span>Победитель!</span></div>
		<ul>
			<li>
				<div class="chance-w">
					<span class="titles">Шанс выигрыша</span>
					<span class="chance">{{$history->winner_chance}}%</span>
				</div>
			</li>
			<li>
				<div class="winner-w">
					<div class="ava"><img src="{{$winner->avatar}}" alt=""></div>
					<div class="nickname">{{$winner->username}}</div>
					<div class="points">Выигрыш: <b>{{$history->winner_sum}}</b> <i class="fas fa-coins"></i></div>
				</div>
			</li>
			<li>
				<div class="ticket-w">
					<span class="titles">Счастливый билет</span>
					<span class="ticket"><i class="fas fa-ticket-alt"></i> <b>{{$history->winner_ticket}}</b></span>
				</div>
			</li>
		</ul>
		<div class="check-random">
			<a href="/fair/{{$history->hash}}" class="btn btn-white btn-sm btn-right">Проверить</a>
		</div>
	</div>
	<div class="chouser" id="chouser">
		<div class="second-title"><span>Выбираем победителя</span></div>
		<div class="carousel" style="transform: translate3d(-6727px, 0px, 0px)">
			@foreach($members as $m)
				<p><img src="{{$m['avatar']}}" alt=""><color style="background: #{{$m['color']}};"></color></p>
			@endforeach
		</div>
		<div class="picker"></div>
	</div>
	<div class="second-title"><span>Ставки в этой игре</span></div>
	<ul class="bets" id="bets">
		@if($historyBets)
		@foreach($historyBets as $bet)
		<li>
			<color style="background: #{{ $bet->color }};"></color>
			<div class="user">
				<div class="ava"><img src="{{ $bet->avatar }}" alt=""></div>
				<div class="info">
					<div class="nickname">{{ $bet->username }}</div>
					<div class="points">Поставил: {{ $bet->sum }} <i class="fas fa-coins"></i></div>
				</div>
				<div class="detail">
					<div class="percent">{{ $bet->chance }}%</div>
					<div class="tickets"><i class="fas fa-ticket-alt"></i> #{{ round($bet->from) }} - #{{ round($bet->to) }}</div>
				</div>
			</div>
		</li>
		@endforeach
		@endif
	</ul>
</div>