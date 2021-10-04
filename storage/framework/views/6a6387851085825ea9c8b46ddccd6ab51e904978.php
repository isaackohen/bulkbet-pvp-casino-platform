<?php if(Auth::user() && $u->ban): ?>
<?php else: ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo e($settings->title); ?></title>
		<meta charset="UTF-8">
		<meta content="<?php echo e($settings->desc); ?>" name="description">
		<meta content="<?php echo e($settings->keys); ?>" name="keywords">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
		<meta content="<?php echo e(csrf_token()); ?>" name="csrf-token">
		<link rel="icon" type="image/png" href="/assets/images/new-logo-loto.png">
		<meta property="og:image" content="/assets/images/new-logo-loto.png"/>
		<link href="https://fonts.googleapis.com/css?family=Jura:300,400,500,600,700" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
		<link rel="manifest" href="/manifest.json">
		<link rel="stylesheet" href="<?php echo e(mix('/assets/css/app.css')); ?>">
		<link rel="stylesheet" href="<?php echo e(mix('/assets/css/notifyme.css')); ?>">
		<link rel="stylesheet" href="<?php echo e(mix('/assets/css/media.css')); ?>">
		<link rel="stylesheet" href="<?php echo e(mix('/assets/css/tooltipster.css')); ?>">
		<link rel="preload" href="<?php echo e(mix('/assets/js/app.js')); ?>" as="script">
		<script src="<?php echo e(mix('/assets/js/bootstrap.js')); ?>" type="text/javascript" defer></script>
		<script>
			window._mixManifest = <?php echo file_get_contents(public_path('mix-manifest.json')); ?>

			window.Laravel = <?php echo json_encode([
					'csrfToken' => csrf_token(),
					'userId' => auth()->guest() ? null : auth()->user()->id,
					'access' => auth()->guest() ? 'user' : auth()->user()->access 
				]); ?>;
		</script>
	</head>
	<body class="chat-mobile">
	
		<div class="header flex flex-between flex-align-center">
		    <div class="left">
		        <a href="/" class="logotype">
					<span>PVP.BULK <b>BETA</b></span>
		        </a>
		        <div class="rooms">
		            <ul>
		                <li class="small tooltip" title="От 1 до 50">
		                    <a href="/?room=small">
		                        <span><span class="name"><?php echo e(__('platform.menu.room.small.title')); ?></span> <b id="roombank_small"><?php echo e(\App\Jackpot::getBank('small')); ?> <i class="fas fa-coins"></i></b></span>
		                    </a>
		                </li>
		                <li class="classic tooltip" title="От 10 до 500">
		                    <a href="/?room=classic">
		                        <span><span class="name"><?php echo e(__('platform.menu.room.classic.title')); ?></span> <b id="roombank_classic"><?php echo e(\App\Jackpot::getBank('classic')); ?> <i class="fas fa-coins"></i></b></span>
		                    </a>
		                </li>
		                <li class="major tooltip" title="От 100 до 1000">
		                    <a href="/?room=major">
		                        <span><span class="name"><?php echo e(__('platform.menu.room.mega.title')); ?></span> <b id="roombank_major"><?php echo e(\App\Jackpot::getBank('major')); ?> <i class="fas fa-coins"></i></b></span>
		                    </a>
		                </li>
		            </ul>
		        </div>
		    </div>
		    <div class="online">
		        <span><i class="fas fa-users"></i> <span class="t"><?php echo e(__('platform.stats.useronline')); ?></span> <b class="on">-</b></span>
		    </div>
		    <div class="group">
		        <a href="<?php echo e($settings->tg_url); ?>" target="_blank"><i class="fab fa-telegram-plane"></i> <?php echo e(__('platform.menu.telegram')); ?></a>
		    </div>
		</div>
		
		<main>
            <div class="leftside">
                <div class="open" id="menuOpen">
                    <span>></span>
                </div>
                <div class="scroll">
                    <div class="profile-block flex flex-wrap">
                       <?php if(auth()->guard()->guest()): ?>
                        <div class="no-login">
							<script async src="https://telegram.org/js/telegram-widget.js?5" data-telegram-login="<?php echo e(config('telegram_login_auth.telegram_bot_name')); ?>" data-size="large" data-auth-url="<?php echo e(config('app.url')); ?>/auth/telegram/callback" data-radius="5" data-request-access="write"></script>
                        </div>
                        <?php else: ?>
                        <div class="login">
                            <div class="avatar flex flex-center flex-align-center">
                                <div class="img">
                                    <a onclick="window.location.href='/logout'" class="log-out">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </a>
                                    <div class="image" style="background: url(<?php echo e($u->avatar); ?>) no-repeat center center / 100%;"></div>
                                </div>
                            </div> 
                            <div class="username">
                                <?php echo e($u->username); ?>

                            </div>
                            <div class="balance flex flex-between flex-align-center tooltip" title="1 Монета - 1 Рубль">
                                <a href="#" rel="popup" data-popup="popup-withdraw"><i class="fas fa-minus"></i></a>
                                <span><span class="money" data-balance="<?php echo e($u->balance); ?>"><?php echo e($u->balance); ?></span> <i class="fas fa-coins"></i></span>
                                <a href="#" rel="popup" data-popup="popup-pay"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="menu-s">
                        <nav>
                            <li class="sounds"><a style="display:none;" id="soundsOn"><i class="icon"><i class="fas fa-volume-up"></i></i><span><?php echo e(__('platform.menu.turnsound.off')); ?></span> <b></b></a></li>
                            <li class="sounds"><a id="sounds"><i class="icon"><i class="fas fa-volume-off"></i></i><span><?php echo e(__('platform.menu.turnsound.on')); ?></span> <b></b></a></li>
                            <li data-page-trigger="'/', '/history/jackpot'" data-toggle-class="active"><a href="/"><svg class="icon"><svg id="icon-jackpot" viewBox="0 0 489.4 489.4" width="100%" height="100%"><path d="M267.4,3.55l-22.8-2.9v151.4l15.3,3.8c46,11.6,78.1,52.8,78.1,100.3c0,6.7-0.7,13.5-2,20.2l-3,15.4l136.8,65l7.1-21.9 c8.3-25.5,12.5-51.9,12.5-78.7C489.2,128.45,393.9,19.85,267.4,3.55z M444.4,299.95l-66.6-31.6c0.3-4.1,0.5-8.1,0.5-12.2 c0-60.6-37.7-113.9-93.3-134.7v-73.7c94.9,22.9,163.9,108.7,163.9,208.4C448.9,270.95,447.4,285.55,444.4,299.95z"></path><path d="M0,256.15c0,119.3,89.1,217.7,204.3,232.6v-112.8c-53.6-13.5-93.3-62-93.3-119.8s39.7-106.3,93.3-119.8V23.55 C89,38.45,0,136.85,0,256.15z"></path><path d="M264.8,375.95v112.7c70-9,130.2-48.8,166.8-105.4l-101.8-48.4C313.3,354.75,290.6,369.45,264.8,375.95z"></path></svg></svg> <span>Jackpot</span> <b id="getPriceJackpot"><?php echo e(\App\Http\Controllers\JackpotController::getPriceJackpot()); ?> <i class="fas fa-coins"></i></b></a></li>
							<li data-page-trigger="'/battle', '/history/battle'" data-toggle-class="active"><a href="/battle"><svg class="icon"><svg id="icon-jackpot" viewBox="0 0 489.4 489.4" width="100%" height="100%"><path d="M409.133,109.203c-19.608-33.592-46.205-60.189-79.798-79.796C295.736,9.801,259.058,0,219.273,0 c-39.781,0-76.47,9.801-110.063,29.407c-33.595,19.604-60.192,46.201-79.8,79.796C9.801,142.8,0,179.489,0,219.267 c0,39.78,9.804,76.463,29.407,110.062c19.607,33.592,46.204,60.189,79.799,79.798c33.597,19.605,70.283,29.407,110.063,29.407 s76.47-9.802,110.065-29.407c33.593-19.602,60.189-46.206,79.795-79.798c19.603-33.596,29.403-70.284,29.403-110.062 C438.533,179.485,428.732,142.795,409.133,109.203z M353.742,297.208c-13.894,23.791-32.736,42.633-56.527,56.534 c-23.791,13.894-49.771,20.834-77.945,20.834c-28.167,0-54.149-6.94-77.943-20.834c-23.791-13.901-42.633-32.743-56.527-56.534 c-13.897-23.791-20.843-49.772-20.843-77.941c0-28.171,6.949-54.152,20.843-77.943c13.891-23.791,32.738-42.637,56.527-56.53 c23.791-13.895,49.772-20.84,77.943-20.84c28.173,0,54.154,6.945,77.945,20.84c23.791,13.894,42.634,32.739,56.527,56.53 c13.895,23.791,20.838,49.772,20.838,77.943C374.58,247.436,367.637,273.417,353.742,297.208z"></path></svg></svg> <span>Battle</span> <b id="getPriceBattle"><?php echo e(\App\Http\Controllers\BattleController::getPriceBattle()); ?> <i class="fas fa-coins"></i></b></a></li>
							<li data-page-trigger="'/pvp', '/history/pvp'" data-toggle-class="active"><a href="/pvp"><svg class="icon"><svg id="icon-flip" viewBox="0 0 511.999 511.999" width="100%" height="100%"><rect x="162.587" y="297.612" transform="matrix(0.7071 0.7071 -0.7071 0.7071 265.563 -30.905)" width="15" height="15"></rect><rect x="197.885" y="333.033" transform="matrix(-0.7071 0.7071 -0.7071 -0.7071 591.3451 435.9457)" width="15" height="14.823"></rect><rect x="180.261" y="315.292" transform="matrix(-0.7071 0.7071 -0.7071 -0.7071 548.7759 418.2738)" width="15" height="15"></rect><rect x="315.007" y="316.653" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 321.3432 781.4113)" width="15" height="15"></rect><rect x="332.632" y="298.97" transform="matrix(0.7071 0.7071 -0.7071 0.7071 316.3306 -150.7466)" width="15" height="15"></rect><rect x="297.389" y="334.358" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 278.8105 799.0264)" width="15" height="14.823"></rect><rect x="447.331" y="48.112" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 737.1212 416.5511)" width="15" height="15"></rect><rect x="429.649" y="65.792" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 694.4345 434.2279)" width="15" height="15"></rect><rect x="371.934" y="66.877" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 555.1087 491.9195)" width="15" height="128.231"></rect><path d="M482.033,405.265l-10.175,10.175L370.407,313.99l46.163-46.163l-37.355-37.355l-41.506,41.506l-8.78-8.781 l-10.607,10.606l8.781,8.781l-16.179,16.179l-17.531-17.53L508.894,65.731V1.553h-64.179l-215.5,215.5l-19.722-19.721 l-10.607,10.606l19.722,19.722l-44.317,44.317l-41.506-41.506L95.43,267.826l46.163,46.163L40.142,415.441l-10.175-10.175 L0,435.23l75.215,75.214l29.966-29.966l-10.176-10.176l101.451-101.451l46.163,46.163l37.356-37.355l-41.506-41.506 l44.317-44.317l17.53,17.53l-16.18,16.179l-8.78-8.781l-10.607,10.606l8.781,8.781l-8.781,8.781l10.607,10.606l103.858-103.858 l16.142,16.142l-103.858,103.86l10.607,10.606l13.438-13.438l101.451,101.451l-10.176,10.176l29.966,29.966l75.215-75.214 L482.033,405.265z M75.214,489.233L21.213,435.23l8.752-8.751l54.001,54.001L75.214,489.233z M84.4,459.697l-33.651-33.65 l7.263-7.263l33.65,33.651L84.4,459.697z M102.268,441.828l-33.65-33.651l8.472-8.472l33.65,33.651L102.268,441.828z M121.346,422.748l-33.649-33.651l8.472-8.472l33.65,33.65L121.346,422.748z M140.427,403.668l-33.65-33.65l8.472-8.472 l33.65,33.65L140.427,403.668z M159.506,384.588l-33.65-33.65l8.472-8.472l33.65,33.65L159.506,384.588z M178.586,365.509 l-33.65-33.65l7.262-7.262l33.65,33.65L178.586,365.509z M258.763,377.66l-16.144,16.142L116.643,267.826l16.142-16.142 L258.763,377.66z M227.862,325.548l-16.179-16.179l101.432-101.432l-10.607-10.606L201.076,298.762l-16.179-16.178 l266.031-266.03h42.966v42.965L227.862,325.548z M326.15,358.245l33.651-33.649l7.262,7.262l-33.65,33.65L326.15,358.245z M344.021,376.116l33.65-33.65l8.472,8.472l-33.65,33.65L344.021,376.116z M363.101,395.196l33.65-33.65l8.472,8.472 l-33.65,33.65L363.101,395.196z M382.18,414.275l33.651-33.65l8.472,8.472l-33.65,33.651L382.18,414.275z M409.732,441.828 l-8.472-8.472l33.65-33.651l8.472,8.472L409.732,441.828z M420.339,452.435l33.65-33.651l7.263,7.263l-33.65,33.65 L420.339,452.435z M428.032,480.48l54.001-54.001l8.752,8.751l-54.002,54.001L428.032,480.48z"></path><rect x="147.406" y="145.838" transform="matrix(0.7071 0.7071 -0.7071 0.7071 153.7976 -64.6234)" width="15" height="15"></rect><rect x="165.086" y="163.524" transform="matrix(0.7071 0.7071 -0.7071 0.7071 171.4809 -71.9453)" width="15" height="15"></rect><rect x="33.077" y="88.141" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 98.289 231.9955)" width="128.231" height="15"></rect><path d="M254.451,115.487l19.924-19.924h46.296V1.553h-132.44v94.011h46.296L254.451,115.487z M203.232,16.553h102.44v64.01 h-37.509l-13.711,13.711l-13.711-13.711h-37.509V16.553z"></path><polygon points="280.831,35.012 268.116,27.054 254.448,48.894 240.779,27.054 228.064,35.012 254.448,77.168"></polygon><polygon points="193.679,235.089 18.107,59.518 18.107,16.553 61.072,16.553 236.643,192.124 247.25,181.518 67.285,1.553  3.107,1.553 3.107,65.731 183.071,245.696"></polygon></svg></svg> <span>PvP</span> <b id="getPriceCoin"><?php echo e(\App\Http\Controllers\PvpController::getPriceCoin()); ?> <i class="fas fa-coins"></i></b></a></li>
                            <li data-page-trigger="'/double', '/history/double'" data-toggle-class="active"><a href="/double"><svg class="icon"><svg id="icon-roulette" viewBox="0 0 512 512" width="100%" height="100%"><path d="M326,126c-5.52,0-10,4.48-10,10s4.48,10,10,10c5.52,0,10-4.48,10-10S331.52,126,326,126z"></path><path d="M256,0C117.577,0,0,118.261,0,256c0,138.227,118.062,256,256,256c91.709,0,174.606-52.029,219.907-127.578 C499.519,345.035,512,300.628,512,256C512,117.629,393.793,0,256,0z M491.765,246.001h-43.063V246 c-1.475-27.167-8.937-53.906-21.863-78.35l36.792-21.245C480.575,177.491,490.199,211.563,491.765,246.001z M453.319,129.263 l-36.722,21.205c-14.524-21.754-33.312-40.542-55.065-55.065l21.205-36.722C410.757,77.114,434.887,101.243,453.319,129.263z M266,20.235c34.439,1.566,68.512,11.19,99.596,28.134l-21.245,36.793C319.907,72.235,293.168,64.774,266,63.298V20.235z M246,20.235v43.063c-27.168,1.475-53.907,8.937-78.351,21.863L146.404,48.37C177.489,31.425,211.561,21.801,246,20.235z M129.263,58.681l21.205,36.722c-21.753,14.523-40.541,33.312-55.065,55.065l-36.722-21.205 C77.114,101.243,101.244,77.113,129.263,58.681z M48.369,146.404l36.792,21.245C72.235,192.094,64.774,218.833,63.298,246H20.235 C21.801,211.562,31.426,177.49,48.369,146.404z M20.235,266h43.063c1.475,27.167,8.937,53.906,21.863,78.35l-36.792,21.246 C31.425,334.51,21.801,300.438,20.235,266z M58.681,382.737l36.723-21.205c14.524,21.754,33.312,40.542,55.065,55.065 l-21.205,36.722C101.243,434.886,77.113,410.757,58.681,382.737z M246,491.764c-34.439-1.566-68.512-11.19-99.596-28.134 l21.245-36.792c24.444,12.926,51.183,20.388,78.351,21.863V491.764z M83,256c0-94.073,79.671-173,173-173 c94.153,0,173,79.854,173,173c0,93.768-79.309,173-173,173C162.221,429,83,349.678,83,256z M266,491.766v-43.064 c27.167-1.475,53.907-8.937,78.351-21.863l21.245,36.792C334.511,480.576,300.439,490.2,266,491.766z M382.737,453.319 l-21.205-36.722c21.752-14.522,40.539-33.309,55.063-55.061l36.725,21.202C434.886,410.758,410.756,434.887,382.737,453.319z M463.63,365.597l-36.794-21.242c12.928-24.446,20.39-51.287,21.866-78.455h43.063 C490.199,300.339,480.574,334.511,463.63,365.597z"></path><path d="M399.762,213.137c-6.635-22.203-18.589-42.913-34.57-59.892c-3.785-4.021-10.114-4.214-14.136-0.427 c-4.021,3.785-4.213,10.113-0.428,14.136c13.859,14.723,24.223,32.673,29.971,51.909c1.579,5.281,7.14,8.302,12.445,6.718 C398.336,224,401.343,218.429,399.762,213.137z"></path><path d="M356,226c-13.036,0-24.152,8.361-28.28,20h-22.726c-3.987-19.563-19.431-35.007-38.994-38.994V184.28 c11.639-4.128,20-15.243,20-28.28c0-16.542-13.458-30-30-30s-30,13.458-30,30c0,13.036,8.361,24.152,20,28.28v22.726 c-19.563,3.987-35.007,19.431-38.994,38.994H184.28c-4.128-11.639-15.243-20-28.28-20c-16.542,0-30,13.458-30,30s13.458,30,30,30 c13.036,0,24.152-8.361,28.28-20h22.726c3.987,19.563,19.431,35.007,38.994,38.994v22.726c-11.639,4.128-20,15.243-20,28.28 c0,16.542,13.458,30,30,30s30-13.458,30-30c0-13.036-8.361-24.152-20-28.28v-22.726c19.563-3.987,35.007-19.431,38.994-38.994 h22.726c4.128,11.639,15.243,20,28.28,20c16.542,0,30-13.458,30-30S372.542,226,356,226z M156,266c-5.514,0-10-4.486-10-10 c0-5.514,4.486-10,10-10c5.514,0,10,4.486,10,10C166,261.514,161.514,266,156,266z M256,146c5.514,0,10,4.486,10,10 c0,5.514-4.486,10-10,10c-5.514,0-10-4.486-10-10C246,150.486,250.486,146,256,146z M256,366c-5.514,0-10-4.486-10-10 c0-5.514,4.486-10,10-10c5.514,0,10,4.486,10,10C266,361.514,261.514,366,256,366z M256,286c-16.542,0-30-13.458-30-30 s13.458-30,30-30s30,13.458,30,30S272.542,286,256,286z M356,266c-5.514,0-10-4.486-10-10c0-5.514,4.486-10,10-10 c5.514,0,10,4.486,10,10C366,261.514,361.514,266,356,266z"></path></svg></svg> <span>Double</span> <b id="getPriceDouble"><?php echo e(\App\Http\Controllers\DoubleController::getPriceDouble()); ?> <i class="fas fa-coins"></i></b></a></li>

                        </nav>
                    </div>
                    <div class="menu-s">
                        <nav>
                            <?php if(auth()->guard()->check()): ?>
                            <li data-page-trigger="'/referral'" data-toggle-class="active"><a href="/referral"><i class="icon"><i class="fas fa-users"></i></i><span><?php echo e(__('platform.menu.partnerprogram')); ?></span> <b></b></a></li>
                            <li data-page-trigger="'/bonus'" data-toggle-class="active"><a href="/bonus"><i class="icon"><i class="fas fa-gift"></i></i><span><?php echo e(__('platform.menu.bonus')); ?></span> <b></b></a></li>
                            <?php endif; ?>
                            <li data-page-trigger="'/rules'" data-toggle-class="active"><a href="/rules"><i class="icon"><i class="fas fa-info"></i></i><span><?php echo e(__('platform.menu.rules')); ?></span> <b></b></a></li>
                            <li data-page-trigger="'/help'" data-toggle-class="active"><a href="/help"><i class="icon"><i class="fas fa-list-ul"></i></i><span><?php echo e(__('platform.menu.help')); ?></span> <b></b></a></li>
                            <?php if(auth()->guard()->check()): ?>
                            <?php if(Auth::user() && auth()->user()->access == 'admin'): ?>
                            <li class=""><a href="/admin" target="_blank"><i class="icon"><i class="fas fa-cogs"></i></i><span><?php echo e(__('platform.menu.admin')); ?></span> <b></b></a></li>
                            <?php endif; ?>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>
		    </div>
		    
		    <div class="container">
			<div class="preloading-wrapper"></div>
		        <div class="c-middle pageContent" style="opacity: 0">
		            <?php echo html_entity_decode($page); ?>

		        </div>
		    </div>
		    
		</main>
		
		
        <div class="chat">
            <div class="open" id="openChat">
                <span>></span>
            </div>
        		    <div class="heading flex flex-start flex-align-center">
        		        <i class="far fa-comments"></i> <span>Онлайн чат</span>
        		    </div>
        		    <div class="messages">
        		        <div class="scroll">
                  <?php if($messages != 0): ?> <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        		            <div class="msg flex flex-between" id="chatm_<?php echo e($sms['time2']); ?>">
        		                <div class="ava">
        		                    <div class="image" style="background: url(<?php echo e($sms['avatar']); ?>) no-repeat center center / 100%;"></div>
        		                </div>
        		                <div class="r">
        		                    <div class="top flex flex-between">
        		                        <span onclick="var u = $(this); $('.chat-input').val(u.text() + ', '); return false;"><?php if($sms['access'] == 'admin'): ?><span style="color:#ffd400;">[АМС] <?php echo e($sms['username']); ?></span> <?php elseif($sms['access'] == 'moder'): ?> <span style="color:#70afe6;">[М] <?php echo e($sms['username']); ?></span> <?php echo e($sms['username']); ?> <?php elseif($sms['access'] == 'youtuber'): ?><span style="color:#dc7979;">[YT] <?php echo e($sms['username']); ?></span> <?php echo e($sms['username']); ?> <?php else: ?> <?php echo e($sms['username']); ?> <?php endif; ?></span>
        		                        <div class="data">
											<b><?php echo e($sms['user_id']); ?></b>
										</div>
        		                        <?php if(Auth::user() && (auth()->user()->access == 'admin' || auth()->user()->access == 'moder')): ?>
        		                        <div class="delete tooltip" title="Удалить" onclick="$.chatdelet(<?php echo e($sms['time2']); ?>)">
        		                            <i class="fas fa-trash-alt"></i>
        		                        </div>
											<?php if($sms['access'] != 'admin' || $sms['access'] != 'moder'): ?>
												<div class="delete tooltip" title="Замутить" onclick="$.mute('<?php echo e($sms['user_id']); ?>','<?php echo e($sms['username']); ?>')">
													<i class="fas fa-ban"></i>
												</div>
											<?php endif; ?>
        		                        <?php endif; ?>
										<?php if(auth()->guard()->check()): ?>
											<?php if($sms['access'] != 'admin' || $sms['access'] != 'moder'): ?>
												<div class="delete tooltip" title="Перевод" onclick="$.transfer('<?php echo e($sms['user_id']); ?>','<?php echo e($sms['username']); ?>')">
													<i class="fas fa-gift"></i>
												</div>
											<?php endif; ?>
										<?php endif; ?>
        		                    </div>
        		                    <div class="mess">
        		                        <?php echo $sms['messages']; ?>

        		                    </div>
        		                </div>
        		            </div>
        		            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?>
        		        </div>
        		    </div>
        		    <div class="bottom flex flex-center flex-align-center">
               <?php if(auth()->guard()->guest()): ?>
                <div class="log-out">
                    <a><i class="fab fa-telegram-plane"></i> <?php echo e(__('platform.menu.login')); ?></a>
                </div>
                <?php else: ?>
                <div class="send-form">
                    <input type="text" class="chat-input" placeholder="<?php echo e(__('platform.chat.placeholder')); ?>">
                    <a href="#send" class="btn-send">
                        <i class="fab fa-telegram-plane"></i>
                    </a>
                </div>
                <?php endif; ?>
        		    </div>
        		</div>
		
		<div class="overlay">
			<?php if(auth()->guard()->check()): ?>
				<?php echo $__env->make('modals.deposit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				<?php echo $__env->make('modals.withdraw', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				<?php echo $__env->make('modals.transfer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
			<?php endif; ?>
			<?php if(Auth::user() && (auth()->user()->access == 'admin' || auth()->user()->access == 'moder')): ?>
				<?php echo $__env->make('modals.mute', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
			<?php endif; ?>	
		</div>
	</body>
</html>
<?php endif; ?><?php /**PATH /home/ploi/pvp.bulk.bet/resources/views/layout.blade.php ENDPATH**/ ?>