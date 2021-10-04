<div class="title flex flex-between flex-align-center">
    <span>История игр (PVP)</span>
    <a href="/pvp">Назад</a>
</div>

<div class="content pvp">
    <div class="alert">
        <span>Активируйте Реферальный/Промо код и получите 5 рублей на баланс.</span>
        <span class="alert-close tooltip" data-close="alert" title="Закрыть">×</span>
    </div>
   @foreach($games as $game)
                <div class="game-coin flip_block_{{$game->id}}">
                    <div class="top">
                        <div class="left">
                            <div class="players block">
                                <div class="user">
                                    <div class="ava user-link">
                                        <img src="{{ \App\User::find($game->user1)->avatar }}">
                                    </div>
                                    <div class="info">
                                        <span class="user-link">{{ \App\User::find($game->user1)->username }}</span>
                                        <p>{{$game->from2}} - {{$game->to2}} <i class="fas fa-ticket-alt"></i></p>
                                    </div>
                                </div>
                                <div class="vs">vs</div>
                                <div class="user">
                                    <div class="ava user-link">
                                        <img src="{{ \App\User::find($game->user2)->avatar }}">
                                    </div>
                                    <div class="info">
                                        <span class="user-link">{{ \App\User::find($game->user2)->username }}</span>
                                        <p>{{$game->from2}} - {{$game->to2}} <i class="fas fa-ticket-alt"></i></p>
                                    </div>
                                </div>
                            </div>
                            <div class="avatars">
                                <div class="tridiv">
                                    <div class="time" id="timer_{{$game->id}}" style="display:none;">
                                        <span id="count_num_{{$game->id}}">0</span>
                                    </div>
                                    <div id="coin-flip-cont_{{$game->id}}" style="">
                                        <div id="coin_{{$game->id}}">
                                            <div class="front winner_a"><img src="{{ \App\User::find($game->winner_id)->avatar }}"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="win-ticket tooltip" title="Счастливый билет">
                                <span>{{$game->winner_ticket}} <i class="fas fa-ticket-alt"></i></span>
                            </div>
                            <div class="info block">
                                <span><i class="fas fa-coins"></i> {{$game->price}}</span>
                            </div>
							<div class="status block"  style="border: 0;">
								<a href="/fair/{{$game->hash}}" class="check">Проверить</a>
							</div>
                        </div>
                    </div>
                </div>
	@endforeach
</div>