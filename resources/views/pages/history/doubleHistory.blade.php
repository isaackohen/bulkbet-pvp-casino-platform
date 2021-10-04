<div class="title flex flex-between flex-align-center">
    <span>{{ __('platform.double.history') }} (Double)</span>
    <a href="/double">{{ __('platform.back') }}</a>
</div>

<div class="content double">
   @foreach($games as $game)
    <div class="game-coin" style="width: 32.3%;margin: 5px 5px;">
        <div class="top">
            <div class="left">
                <div class="players block"> 
                    <div class="game-id">
                        <span>#{{$game->id}}</span>
                    </div>
                    <div class="user">
                        <div class="ava user-link tooltip" title="{{ __('platform.double.winningteam') }}" @if($game->winner_color == 'red') style="background:linear-gradient(to top, #e79f74 0%, #e77a74 100%);box-shadow: -5px 8px 1em rgba(231, 116, 116, 0.1);line-height: 45px;" @elseif($game->winner_color == 'green') style="background:linear-gradient(to top, #87f9c4 0%, #74c1c9 100%);box-shadow: -5px 8px 1em rgba(231, 116, 116, 0.1);line-height: 45px;" @else style="background:linear-gradient(to top, #8a8ef9 0%, #98a7fd 100%);box-shadow: -5px 8px 1em rgba(150, 164, 252, 0.1);line-height: 45px;" @endif> <span style="color: #fff;">{{$game->winner_num}}</span></div>
                    </div>
                </div>
                <div class="status block"  style="border: 0;">
                    <a href="/fair/{{$game->hash}}" class="check">{{ __('platform.verifyfairness') }}</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>