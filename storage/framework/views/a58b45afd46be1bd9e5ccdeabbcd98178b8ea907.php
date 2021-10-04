<div class="title flex flex-between flex-align-center">
    <span>
        <svg class="icon">
            <svg id="icon-jackpot" viewBox="0 0 489.4 489.4" width="100%" height="100%">
                <path d="M409.133,109.203c-19.608-33.592-46.205-60.189-79.798-79.796C295.736,9.801,259.058,0,219.273,0 c-39.781,0-76.47,9.801-110.063,29.407c-33.595,19.604-60.192,46.201-79.8,79.796C9.801,142.8,0,179.489,0,219.267 c0,39.78,9.804,76.463,29.407,110.062c19.607,33.592,46.204,60.189,79.799,79.798c33.597,19.605,70.283,29.407,110.063,29.407 s76.47-9.802,110.065-29.407c33.593-19.602,60.189-46.206,79.795-79.798c19.603-33.596,29.403-70.284,29.403-110.062 C438.533,179.485,428.732,142.795,409.133,109.203z M353.742,297.208c-13.894,23.791-32.736,42.633-56.527,56.534 c-23.791,13.894-49.771,20.834-77.945,20.834c-28.167,0-54.149-6.94-77.943-20.834c-23.791-13.901-42.633-32.743-56.527-56.534 c-13.897-23.791-20.843-49.772-20.843-77.941c0-28.171,6.949-54.152,20.843-77.943c13.891-23.791,32.738-42.637,56.527-56.53 c23.791-13.895,49.772-20.84,77.943-20.84c28.173,0,54.154,6.945,77.945,20.84c23.791,13.894,42.634,32.739,56.527,56.53 c13.895,23.791,20.838,49.772,20.838,77.943C374.58,247.436,367.637,273.417,353.742,297.208z"></path>
            </svg>
        </svg>
        Battle
    </span>
    <a href="/history/battle">История игр</a>
</div>

<div class="content">
    <div class="alert">
        <span>Активируйте Реферальный/Промо код и получите 5 рублей на баланс.</span>
        <span class="alert-close tooltip" data-close="alert" title="Закрыть">×</span>
    </div>
    <div class="game-content battle flex flex-between flex-wrap">
        <div class="top-battle">
            <div class="battle-slider hist flex flex-center flex-align-center bg-2">
                <div class="history">
                    <div class="scroll" id="history">
                        <?php $__currentLoopData = $lastwins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                            <a href="/fair/<?php echo e($game->hash); ?>"><div class="hist <?php echo e($game->winner_team); ?> tooltip-right" title="Проверить игру"></div></a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <div class="battle-slider battle bg-2 flex flex-center flex-align-center">
                <div class="left">
                    <div class="slider-svg">
                        <div class="info">
                            <div class="time">
                                <span id="timer"><i class="far fa-clock"></i> 15</span>
                            </div>
                        </div>
                        <svg class="UsersInterestChart" width="400" height="400">
                            <g class="chart" transform="translate(200, 200)">
                                <g class="timer" transform="translate(0,0)">
                                    <g class="bets" id="circle" style="transform: rotate(0deg);">
                                        <path id="blue" fill="#8a8ef9" stroke-width="5px" d="M1.1021821192326179e-14,-150A150,150,0,1,1,1.1021821192326179e-14,150L9.491012693391987e-15,140A140,140,0,1,0,9.491012693391987e-15,-140Z" transform="rotate(0)" style="opacity: 1;"></path>
                                        <path id="red" fill="#e77474" stroke-width="5px" d="M1.1021821192326179e-14,150A150,150,0,1,1,-3.3065463576978534e-14,-150L-2.847303808017596e-14,-140A140,140,0,1,0,9.491012693391987e-15,140Z" transform="rotate(0)" style="opacity: 1;"></path>
                                    </g>
                                </g>
                            </g>
                            <polygon points="200,50 220,80 180,80" style="fill: #b1c0ef;stroke: #3a4055;stroke-width: 5px;"></polygon>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="battle-slider info bg-2">
                <div class="right">
                    <div class="chances flex flex-between flex-align-center">
                        <div class="chance red flex flex-align-center flex-center">
                            <div>
                                <h4 id="red_persent"><?php echo e($chances[0]); ?>%</h4>
                                <p>1 - <span id="red_tickets"><?php echo e($tickets[0]); ?></span></p>
                            </div>
                        </div>
                        <div class="chance blue flex flex-align-center flex-center">
                            <div>
                                <h4 id="blue_persent"><?php echo e($chances[1]); ?>%</h4>
                                <p><span id="blue_tickets"><?php echo e($tickets[1]); ?></span> - 1000</p>
                            </div>
                        </div>
                    </div>
                    <div class="bet methods-value">
                        <?php if(auth()->guard()->guest()): ?>
                        <input type="text" id="amount" value="Авторизуйтесь." disabled/>
                        <div class="buttons flex flex-between">
                            <a  class="red makeBet" onclick="$.wnoty({position: 'top-right',type: 'error',message: 'Авторизуйтесь!'});">Поставить</a>
                            <a  class="blue makeBet" onclick="$.wnoty({position: 'top-right',type: 'error',message: 'Авторизуйтесь!'});">Поставить</a>
                        </div>
                        <?php else: ?> 
                        <input type="text" id="amount" placeholder="Введите сумму...">
                        <ul>
                            <li><a  data-value="1" data-method="plus">+1</a></li>
                            <li><a  data-value="10" data-method="plus">+10</a></li>
                            <li><a  data-value="100" data-method="plus">+100</a></li>
                            <li><a  data-value="1000" data-method="plus">+1000</a></li>
                            <li><a  data-value="2" data-method="multiply">x2</a></li>
                            <li><a  data-value="2" data-method="divide">1/2</a></li>
                            <li><a  data-method="all">макс</a></li>
                            <li><a  data-method="clear">очистить</a></li>
                        </ul>
                        <div class="buttons flex flex-between">
                            <a  class="red makeBet" onclick="$.bet('red')">Поставить</a>
                            <a  class="blue makeBet" onclick="$.bet('blue')">Поставить</a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="hash">
                        Round hash: <span id="hash"><?php echo e($game->hash); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="bets flex flex-between">
            <div class="red-team team">
                <div class="heading flex flex-between flex-align-center">
                    <span class="game-price" id="red_sum"><?php echo e($bank[0]); ?> <i class="fas fa-coins"></i></span>
                    <span class="x" id="red_factor"><?php echo e($factor[0] ? 'x'.$factor[0] : ''); ?></span>
                </div>
                <div class="bets" id="red_list">
                   <?php $__currentLoopData = $bets->where('color', 'red'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bet flex flex-between">
                        <div class="left">
                            <div class="ava">
                                <div class="image" style="background: url(<?php echo e($b->user->avatar); ?>) no-repeat center center / 100%;"></div>
                            </div>
                            <div class="username"><?php echo e($b->user->username); ?></div>
                            <div class="tickets"><span>внес</span></div>
                            <div class="amount"><?php echo e($b->price); ?> <i class="fas fa-coins"></i></div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div class="blue-team team">
                <div class="heading flex flex-between flex-align-center">
                    <span class="game-price" id="blue_sum"><?php echo e($bank[1]); ?> <i class="fas fa-coins"></i></span>
                    <span class="x" id="blue_factor"><?php echo e($factor[1] ? 'x'.$factor[1] : ''); ?></span>
                </div>
                <div class="bets" id="blue_list">
                   <?php $__currentLoopData = $bets->where('color', 'blue'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bet flex flex-between">
                        <div class="left">
                            <div class="ava">
                                <div class="image" style="background: url(<?php echo e($b->user->avatar); ?>) no-repeat center center / 100%;"></div>
                            </div>
                            <div class="username"><?php echo e($b->user->username); ?></div>
                            <div class="tickets"><span>внес</span></div>
                            <div class="amount"><?php echo e($b->price); ?> <i class="fas fa-coins"></i></div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	window.build = '<?php echo e($chances[1] / 100); ?>';
</script><?php /**PATH /var/www/html/resources/views/pages/games/battle.blade.php ENDPATH**/ ?>