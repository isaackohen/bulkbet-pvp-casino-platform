<script>
    window.room = '{{$room}}';
</script>
<div class="title flex flex-between flex-align-center {{ $room }}">
    <span>
        <svg class="icon">
            <svg id="icon-jackpot" viewBox="0 0 489.4 489.4" width="100%" height="100%">
                <path d="M267.4,3.55l-22.8-2.9v151.4l15.3,3.8c46,11.6,78.1,52.8,78.1,100.3c0,6.7-0.7,13.5-2,20.2l-3,15.4l136.8,65l7.1-21.9 c8.3-25.5,12.5-51.9,12.5-78.7C489.2,128.45,393.9,19.85,267.4,3.55z M444.4,299.95l-66.6-31.6c0.3-4.1,0.5-8.1,0.5-12.2 c0-60.6-37.7-113.9-93.3-134.7v-73.7c94.9,22.9,163.9,108.7,163.9,208.4C448.9,270.95,447.4,285.55,444.4,299.95z"></path>
                <path d="M0,256.15c0,119.3,89.1,217.7,204.3,232.6v-112.8c-53.6-13.5-93.3-62-93.3-119.8s39.7-106.3,93.3-119.8V23.55 C89,38.45,0,136.85,0,256.15z"></path>
                <path d="M264.8,375.95v112.7c70-9,130.2-48.8,166.8-105.4l-101.8-48.4C313.3,354.75,290.6,369.45,264.8,375.95z"></path>
            </svg>
        </svg>
        Jackpot &nbsp;<span id="titleRoom"></span>
    </span>
    <a href="/history/jackpot">История игр</a>
</div>

<div class="content">
    <div class="alert" style="margin-bottom: 15px;">
        <span>Активируйте Реферальный/Промо код и получите 5 рублей на баланс.</span>
        <span class="alert-close tooltip" data-close="alert" title="Закрыть">×</span>
    </div>
    <div class="alert" style="margin-bottom: 55px;">
        <span>Максимальный вывод с бонусного баланса - 100р.</span>
        <span class="alert-close tooltip" data-close="alert" title="Закрыть">×</span>
    </div>
    <div class="slider" id="chouser_{{$room}}" style="display: none;">
        <div class="arrow top"></div>
        <div class="carousel">
           <div class="fixed-width">
                <div class="scroll" id="carousel_{{$room}}"></div>
           </div>
        </div>
        <div class="arrow bottom"></div>
    </div>
    <div class="game-content flex flex-between">
        <div class="game-progress">
            <div class="progress">
                <div class="info flex flex-align-center flex-center">
                    <div>
                        <div class="game-price"><span id="gamebank_{{$room}}">{{$game->price}}</span> <i class="fas fa-coins"></i></div>
                        <div class="time">
                            <span id="time_{{ $room }}"><i class="far fa-clock"></i> {{$time[0]}}:{{$time[1]}}</span>
                        </div>
                    </div>
                </div>
                <div class="timer">
                    <svg class="timer-svg" viewBox="0 0 101 101" width="100%" height="100%">
                        <path id="timer-svg_{{ $room }}" d="M 50,50 m 0,-45 a 45,45 0 1 1 0,90 a 45,45 0 1 1 0,-90" fill-opacity="0" stroke-width="3" style="stroke-dasharray: 282.783, 282.783;stroke-dashoffset: 0.783;transition: 2s ease-in-out 0s;" stroke-linecap="round"></path>
                    </svg>
                    <svg class="timer-svg shadow" viewBox="0 0 101 101" width="100%" height="100%">
                        <path id="timerShadow_{{ $room }}" d="M 50,50 m 0,-45 a 45,45 0 1 1 0,90 a 45,45 0 1 1 0,-90" fill-opacity="0" stroke-width="3" style="stroke-dasharray: 282.783, 282.783;stroke-dashoffset: 0.783;transition: 2s ease-in-out 0s;" stroke-linecap="round"></path>
                    </svg>
                </div>
            </div>
            <div class="block">
                <div class="bet methods-value bg">
                   @guest
                    <span>Авторизуйтесь!</span>
                   @else
                   <div class="value">
                        <input type="text" id="amount" placeholder="Введите сумму">
                   </div>
                    <a  class="bet-b makeBet">Поставить</a>
                    <ul>
                        <li><a  data-value="1" data-method="plus">+1</a></li>
                        <li><a  data-value="10" data-method="plus">+10</a></li>
                        <li><a  data-value="100" data-method="plus">+100</a></li>
                        <li><a  data-method="multiply" data-value="2">x2</a></li>
                        <li><a  data-method="divide" data-value="2">1/2</a></li>
                        <li><a  data-method="all">макс</a></li>
                        <li><a  data-method="clear">очистить</a></li>
                    </ul>
                    @endif
                </div>
            </div>
        </div>
        <div class="bets">
            <div class="users-in-game flex flex-center flex-align-center">
                <span class="head">В игре</span>
                <div class="users" id="chances_{{$room}}">
                  @foreach($chances as $user)
                   <div class="user tooltip" title="{{ $user['username'] }}"> 
                       <div class="chance">{{ $user['chance'] }}%</div>
                       <div class="image" style="background: url({{ $user['avatar'] }}) no-repeat center center / 100%;"></div>
                   </div>
                   @endforeach
                </div>
            </div>
            <div class="scroll" id="bets_{{$room}}">
                @if($bets)
                @foreach($bets as $bet)
                <div class="bet flex flex-between">
                    <div class="left">
                        <div class="ava">
                            <div class="image" style="background: url({{ $bet->avatar }}) no-repeat center center / 100%;"></div>
                        </div>
                        <div class="username">{{ $bet->username }}</div>
                        <div class="tickets"><span>билеты</span> <b>{{ round($bet->from) }} - {{ round($bet->to) }}</b></div>
                        <div class="amount points">{{ $bet->sum }} <i class="fas fa-coins"></i></div>
                    </div>
                    <div class="right">
                        <div class="date percent">{{ $bet->chance }}%</div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
            <div class="fair-game">
                <h4>Игра началась! Вносите свои депозиты!</h4>
                <p><b>#{{ $game->game_id }}</b> Hash round: <span id="hash_{{$room}}">{{$game->hash}}</span></p>
            </div>
        </div>
    </div>
</div>