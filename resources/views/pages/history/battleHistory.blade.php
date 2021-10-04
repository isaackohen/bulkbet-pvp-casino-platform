<div class="title flex flex-between flex-align-center">
    <span>{{ __('platform.battle.history') }} (Battle)</span>
    <a href="/battle">{{ __('platform.back') }}</a>
</div>

<div class="content double">
   @foreach($games as $game)
        <div class="game-coin">
            <div class="top">
                <div class="left">
                    <div class="players block"> 
                        <div class="game-id">
                            <span>#{{$game->id}}</span>
                        </div>
                        <div class="user">
							<div class="ava user-link tooltip" title="{{ __('platform.battle.winningteam') }}" @if($game->winner_team == 'red') style="background:linear-gradient(to top, #E77474 0%, #e77a74 100%);box-shadow: -5px 8px 1em rgba(231, 116, 116, 0.1);line-height: 45px;" @else style="background:linear-gradient(to top, #8a8ef9 0%, #98a7fd 100%);box-shadow: -5px 8px 1em rgba(150, 164, 252, 0.1);line-height: 45px;" @endif></div>
							<div class="info">
                                <span class="user-link"> @if($game->winner_team == 'red') {{ __('platform.battle.redteam') }} @else {{ __('platform.battle.blueteam') }} @endif</span>
                                <p>{{ __('platform.battle.wonamount') }}: <i class="fas fa-coins"></i> {{$game->winner_factor}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="win-ticket tooltip" title="{{ __('platform.battle.winningticket') }}">
                        <span>{{$game->winner_ticket}} <i class="fas fa-ticket-alt"></i></span>
                    </div>
                    <div class="info block">
                        <span><i class="fas fa-coins"></i> {{$game->price}}</span>
                    </div>
                    <div class="status block">
                        <a href="/fair/{{$game->hash}}" class="check">{{ __('platform.verifyfairness') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>