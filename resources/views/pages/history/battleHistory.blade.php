<div class="title flex flex-between flex-align-center">
    <span>История игр (Battle)</span>
    <a href="/battle">Назад</a>
</div>

<div class="content double">
    <div class="alert">
        <span>Активируйте Реферальный/Промо код и получите 5 рублей на баланс.</span>
        <span class="alert-close tooltip" data-close="alert" title="Закрыть">×</span>
    </div>
   @foreach($games as $game)
        <div class="game-coin">
            <div class="top">
                <div class="left">
                    <div class="players block"> 
                        <div class="game-id">
                            <span>#{{$game->id}}</span>
                        </div>
                        <div class="user">
							<div class="ava user-link tooltip" title="Победившая команда" @if($game->winner_team == 'red') style="background:linear-gradient(to top, #E77474 0%, #e77a74 100%);box-shadow: -5px 8px 1em rgba(231, 116, 116, 0.1);line-height: 45px;" @else style="background:linear-gradient(to top, #8a8ef9 0%, #98a7fd 100%);box-shadow: -5px 8px 1em rgba(150, 164, 252, 0.1);line-height: 45px;" @endif></div>
							<div class="info">
                                <span class="user-link"> @if($game->winner_team == 'red') Красная команда @else Синяя команда @endif</span>
                                <p>Выиграли: <i class="fas fa-coins"></i> {{$game->winner_factor}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="win-ticket tooltip" title="Победный билет">
                        <span>{{$game->winner_ticket}} <i class="fas fa-ticket-alt"></i></span>
                    </div>
                    <div class="info block">
                        <span><i class="fas fa-coins"></i> {{$game->price}}</span>
                    </div>
                    <div class="status block">
                        <a href="/fair/{{$game->hash}}" class="check">Проверить</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>