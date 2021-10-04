<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title>{{$settings->sitename}} - Панель управления</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link href="{{ asset('assets/images/favicon.png') }}" rel="shortcut icon" type="image/png">
	<link href="{{ asset('cp/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('cp/css/jquery.toast.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('cp/css/style.css') }}" rel="stylesheet" type="text/css">
	@if($u->is_admin)
	<script type="text/javascript">
         const admin = '{{ $u->is_admin }}';
         const moder = 'null';
    </script>
    @endif
    @if($u->is_moder)
	<script type="text/javascript">
         const moder = '{{ $u->is_moder }}';
         const admin = 'null';
    </script>
    @endif
</head>

<body>
    <div class="wrapper theme-2-active navbar-top-light">
		<!-- Top Menu Items -->
			<nav class="navbar navbar-inverse navbar-fixed-top">
				<div class="nav-wrap">
                    <div class="mobile-only-brand pull-left">
                        <div class="nav-header pull-left">
                            <div class="logo-wrap">
                                <a href="/admin">
                                    <img class="brand-img" src="/assets/images/logo.svg" alt="brand"/>
                                    <span class="brand-text"><img  src="/assets/images/logo.svg" alt="brand"/></span>
                                </a>
                            </div>
                        </div>	
                        <a id="toggle_nav_btn" class="toggle-left-nav-btn inline-block ml-20 pull-left" href="javascript:void(0);"><i class="ti-align-left"></i></a>
                    </div>
                    <div class="mobile-only-nav pull-right">
                        <ul class="nav navbar-right top-nav pull-right">
                            <li class="auth-drp">
                                <a class="pr-0"><img src="{{$u->avatar}}" alt="{{$u->username}}" class="user-auth-img img-circle"/></a>
                            </li>
                        </ul>
                    </div>	
				</div>
			</nav>
			<!-- /Top Menu Items -->
			
			<!-- Left Sidebar Menu -->
			<div class="fixed-sidebar-left">
				<ul class="nav navbar-nav side-nav nicescroll-bar">
					<li>
						<a href="/admin">
						    <div class="pull-left">
                                <i class="ti-stats-up mr-20"></i>
                                <span class="right-nav-text">Статистика</span>
						    </div>
						    <div class="clearfix"></div>
						</a>
					</li>
					@if($u->is_admin)
					<li>
						<a href="/admin/users">
						    <div class="pull-left">
                                <i class="ti-user mr-20"></i>
                                <span class="right-nav-text">Пользователи</span>
						    </div>
						    <div class="clearfix"></div>
						</a>
					</li>
					<li>
						<a href="/admin/bots">
						    <div class="pull-left">
                                <i class="ti-face-smile mr-20"></i>
                                <span class="right-nav-text">Боты</span>
						    </div>
						    <div class="clearfix"></div>
						</a>
					</li>
					@endif
					<li>
						<a href="/admin/promo">
						    <div class="pull-left">
                                <i class="ti-shortcode mr-20"></i>
                                <span class="right-nav-text">Промокоды</span>
						    </div>
						    <div class="clearfix"></div>
						</a>
					</li>
					@if($u->is_admin)
					<li>
						<a href="/admin/bonuses">
						    <div class="pull-left">
                                <i class="icon-diamond mr-20"></i>
                                <span class="right-nav-text">Бонусы</span>
						    </div>
						    <div class="clearfix"></div>
						</a>
					</li>
					<li>
						<a href="/admin/settings">
						    <div class="pull-left">
                                <i class="ti-settings mr-20"></i>
                                <span class="right-nav-text">Настройки</span>
						    </div>
						    <div class="clearfix"></div>
						</a>
					</li>
					<li>
						<a href="/admin/withdraw">
						    <div class="pull-left">
                                <i class="ti-money mr-20"></i>
                                <span class="right-nav-text">Выводы</span>
						    </div>
						    <div class="clearfix"></div>
						</a>
					</li>
					@endif
					<li class="navigation-header">
						<span> </span> 
						<hr>
					</li>
					<li>
						<a href="/">
						    <div class="pull-left">
                                <i class="ti-share-alt mr-20"></i>
                                <span class="right-nav-text">Вернуться на сайт</span>
						    </div>
						    <div class="clearfix"></div>
						</a>
					</li>
				</ul>
			</div>
			<!-- /Left Sidebar Menu -->
		
        <!-- Main Content -->
		<div class="page-wrapper">
            <div class="container pt-30">
				@yield('content')
			</div>
			
			<!-- Footer -->
			<footer class="footer pl-30 pr-30">
				<div class="container">
					<div class="row">
						<div class="col-sm-6">
							<p>2018 &copy; {{$settings->domain}}. Панель управления</p>
						</div>
					</div>
				</div>
			</footer>
			<!-- /Footer -->
			
		</div>
        <!-- /Main Content -->

    </div>
    <!-- /#wrapper -->
	
	<!-- JavaScript -->
    <script src="{{ asset('cp/js/jquery.min.js') }}"></script>
    <script src="{{ asset('cp/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('cp/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('cp/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('cp/js/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('cp/js/init.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>
    @if($u->is_admin)
    <script src="{{ asset('cp/js/adminPost.js') }}"></script>
    @endif
    @if(session('error'))
        <script>
        $.toast({
            position: 'top-right',
            text: "{{ session('error') }}",
            icon: 'error'
        });
        </script>
    @elseif(session('success'))
        <script>
        $.toast({
            position: 'top-right',
            text: "{{ session('success') }}",
            icon: 'success'
        });
        </script>
    @endif
</body>
</html>