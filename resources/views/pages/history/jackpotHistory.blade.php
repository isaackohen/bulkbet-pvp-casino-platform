<div class="title flex flex-between flex-align-center">
    <span>История игр</span>
    <div class="r">
		<ul class="room-selector">
			@foreach($rooms as $r)
			<li class="room">
				<a>
					<div class="room-name">{{$r->title}}</div>
				</a>
			</li>
			@endforeach
		</ul>
    </div>
</div>

<div class="content">
    <div class="alert">
        <span>Активируйте Реферальный/Промо код и получите 5 рублей на баланс.</span>
        <span class="alert-close tooltip" data-close="alert" title="Закрыть">×</span>
    </div>
	@foreach($rooms as $r)
   <div class="historyTable">
       @foreach($history as $game) @if($game['room'] == $r->name)
        <div class="game-coin">
            <div class="top">
                <div class="left">
                    <div class="players block"> 
                        <div class="game-id">
                            <span>#{{$game['game_id']}}</span>
                        </div>
                        <div class="user">
                            <div class="ava user-link">
                                <img src="{{$game['winner_avatar']}}">
                            </div>
                            <div class="info">
                                <span class="user-link">{{$game['winner_name']}}</span>
                                <p>Шанс: {{$game['winner_chance']}}%</p>
                            </div>
                        </div>
                    </div>
                    <div class="win-ticket tooltip" title="Победный билет">
                        <span>{{$game['winner_ticket']}} <i class="fas fa-ticket-alt"></i></span>
                    </div>
                    <div class="info block">
                        <span><i class="fas fa-coins"></i> {{$game['winner_sum']}}</span>
                    </div>
                    <div class="status block">
                        <a href="/fair/{{$game['hash']}}" class="check">Проверить</a>
                    </div>
                </div>
            </div>
        </div>
        @endif @endforeach
   </div>
	@endforeach
</div>