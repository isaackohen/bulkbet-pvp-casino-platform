@extends('panel')

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="row">
		    <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="panel panel-default card-view">
                            <div class="panel-heading">
                                <div class="text-center">
                                    <h6 class="panel-title txt-dark">
                                        Статистика пополнений
                                    </h6>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<div class="@if($u->is_admin) col-sm-2 @else col-sm-3 @endif">
				<div class="panel panel-default border-panel card-view pa-0">
					<div class="panel-wrapper collapse in">
						<div class="panel-body pa-0">
							<div class="sm-data-box">
								<div class="container-fluid">
									<div class="row">
										<div class="col-xs-8 text-center pl-0 pr-0 data-wrap-left">
											<span class="txt-dark block counter">{{$pay_today}}</span> <span class="uppercase-font block">За сегодня</span>
										</div>
										<div class="col-xs-4 text-center pl-0 pr-0 data-wrap-right">
											<i class="fa fa-rub data-right-rep-icon"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="@if($u->is_admin) col-sm-2 @else col-sm-3 @endif">
				<div class="panel panel-default border-panel card-view pa-0">
					<div class="panel-wrapper collapse in">
						<div class="panel-body pa-0">
							<div class="sm-data-box">
								<div class="container-fluid">
									<div class="row">
										<div class="col-xs-8 text-center pl-0 pr-0 data-wrap-left">
											<span class="txt-dark block counter">{{$pay_week}}</span> <span class="uppercase-font block">За 7 дней</span>
										</div>
										<div class="col-xs-4 text-center pl-0 pr-0 data-wrap-right">
											<i class="fa fa-rub data-right-rep-icon"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="@if($u->is_admin) col-sm-2 @else col-sm-3 @endif">
				<div class="panel panel-default border-panel card-view pa-0">
					<div class="panel-wrapper collapse in">
						<div class="panel-body pa-0">
							<div class="sm-data-box">
								<div class="container-fluid">
									<div class="row">
										<div class="col-xs-8 text-center pl-0 pr-0 data-wrap-left">
											<span class="txt-dark block counter">{{$pay_month}}</span> <span class="uppercase-font block">За месяц</span>
										</div>
										<div class="col-xs-4 text-center pl-0 pr-0 data-wrap-right">
											<i class="fa fa-rub data-right-rep-icon"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="@if($u->is_admin) col-sm-2 @else col-sm-3 @endif">
				<div class="panel panel-default border-panel card-view pa-0">
					<div class="panel-wrapper collapse in">
						<div class="panel-body pa-0">
							<div class="sm-data-box">
								<div class="container-fluid">
									<div class="row">
										<div class="col-xs-8 text-center pl-0 pr-0 data-wrap-left">
											<span class="txt-dark block counter">{{$pay_all}}</span> <span class="uppercase-font block">За все время</span>
										</div>
										<div class="col-xs-4 text-center pl-0 pr-0 data-wrap-right">
											<i class="fa fa-rub data-right-rep-icon"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@if($u->is_admin)
			<div class="col-sm-2">
				<div class="panel panel-default border-panel card-view pa-0">
					<div class="panel-wrapper collapse in">
						<div class="panel-body pa-0">
							<div class="sm-data-box">
								<div class="container-fluid">
									<div class="row">
										<div class="col-xs-8 text-center pl-0 pr-0 data-wrap-left">
											<span class="txt-dark block counter" id="fkBal"><img src="https://i1.wp.com/caringo.com/wp-content/themes/bootstrap/wwwroot/img/spinning-wheel-1.gif" height="26px"/></span> <span class="uppercase-font block">На кошельке</span>
										</div>
										<div class="col-xs-4 text-center pl-0 pr-0 data-wrap-right">
											<i class="fa fa-rub data-right-rep-icon"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				<div class="panel panel-default border-panel card-view pa-0">
					<div class="panel-wrapper collapse in">
						<div class="panel-body pa-0">
							<div class="sm-data-box">
								<div class="container-fluid">
									<div class="row">
										<div class="col-xs-8 text-center pl-0 pr-0 data-wrap-left">
											<span class="txt-dark block counter">{{$with_req}}</span> <span class="uppercase-font block">На вывод</span>
										</div>
										<div class="col-xs-4 text-center pl-0 pr-0 data-wrap-right">
											<i class="fa fa-rub data-right-rep-icon"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endif
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="row">
		    <div class="col-md-12">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="panel panel-default card-view">
                            <div class="panel-heading">
                                <div class="text-center">
                                    <h6 class="panel-title txt-dark">
                                        Статистика дохода
                                    </h6>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			
			<div class="col-sm-3">
				<div class="row">
					<div class="col-sm-12 col-xs-12">
						<div class="panel panel-default border-panel card-view">
							<div class="panel-heading">
								<div class="text-center">
									<h6 class="panel-title txt-dark">Комиссия Jackpot</h6>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="panel-wrapper collapse in">
								<div class="panel-body row">
									<div class="text-center">
										<div class="col-md-12 mb-5" style="font-size: 20px;"><span style="color: #22af47;">{{$jpCom}}</span>руб.</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="row">
					<div class="col-sm-12 col-xs-12">
						<div class="panel panel-default border-panel card-view">
							<div class="panel-heading">
								<div class="text-center">
									<h6 class="panel-title txt-dark">Комиссия PvP</h6>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="panel-wrapper collapse in">
								<div class="panel-body row">
									<div class="text-center">
										<div class="col-md-12 mb-5" style="font-size: 20px;"><span style="color: #22af47;">{{$pvpCom}}</span>руб.</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="row">
					<div class="col-sm-12 col-xs-12">
						<div class="panel panel-default border-panel card-view">
							<div class="panel-heading">
								<div class="text-center">
									<h6 class="panel-title txt-dark">Профит Double</h6>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="panel-wrapper collapse in">
								<div class="panel-body row">
									<div class="text-center">
										<div class="col-md-12 mb-5" style="font-size: 20px;"><span style="color: #22af47;">{{$dlwin}}</span>руб. / <span style="color: #f83f37;">{{$dllose}}</span>руб. = <span style="color: #008eff;">{{$dlwin-$dllose}}</span>руб.</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="row">
					<div class="col-sm-12 col-xs-12">
						<div class="panel panel-default border-panel card-view">
							<div class="panel-heading">
								<div class="text-center">
									<h6 class="panel-title txt-dark">Общий профит</h6>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="panel-wrapper collapse in">
								<div class="panel-body row">
									<div class="text-center">
										<div class="col-md-12 mb-5" style="font-size: 20px;"><span class="text-success">{{ $jpCom+$pvpCom+$dlwin }}</span>руб.</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
@if($u->is_admin)
<div class="row">
	<div class="col-sm-4">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="panel panel-default border-panel card-view">
                    <div class="panel-heading">
                        <div class="text-center">
                            <h6 class="panel-title txt-dark">Подкрутка Jackpot (small)</h6>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-wrapper collapse in" style="height: 370px; overflow-x: hidden;">
                        <div class="panel-body row pa-0">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Пользователь</th>
                                        <th>Шанс</th>
                                        <th>Действие</th>
                                    </tr>
                                </thead>
                                <tbody id="chance_small">
                                    @if($chances_small) @foreach($chances_small as $user)
                                    <tr>
                                        <td><img src="{{ $user['avatar'] }}" width="35px" class="img-circle"></td>
                                        <td class="clip">{{ $user['username'] }}</td>
                                        <td>{{ $user['chance'] }}%</td>
                                        <td><button class="btn btn-primary btn-outline btn-xs" onclick="gotRoulette('{{ $user['room'] }}', {{ $user['id'] }})">подкрутить</button></td>
                                    </tr>
                                    @endforeach @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="col-sm-4">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="panel panel-default border-panel card-view">
                    <div class="panel-heading">
                        <div class="text-center">
                            <h6 class="panel-title txt-dark">Подкрутка Jackpot (classic)</h6>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-wrapper collapse in" style="height: 370px; overflow-x: hidden;">
                        <div class="panel-body row pa-0">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Пользователь</th>
                                        <th>Шанс</th>
                                        <th>Действие</th>
                                    </tr>
                                </thead>
                                <tbody id="chance_classic">
                                    @if($chances_classic) @foreach($chances_classic as $user)
                                    <tr>
                                        <td><img src="{{ $user['avatar'] }}" width="35px" class="img-circle"></td>
                                        <td class="clip">{{ $user['username'] }}</td>
                                        <td>{{ $user['chance'] }}%</td>
                                        <td><button class="btn btn-primary btn-outline btn-xs" onclick="gotRoulette({{ $user['id'] }})">подкрутить</button></td>
                                    </tr>
                                    @endforeach @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="col-sm-4">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="panel panel-default border-panel card-view">
                    <div class="panel-heading">
                        <div class="text-center">
                            <h6 class="panel-title txt-dark">Подкрутка Jackpot (major)</h6>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-wrapper collapse in" style="height: 370px; overflow-x: hidden;">
                        <div class="panel-body row pa-0">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Пользователь</th>
                                        <th>Шанс</th>
                                        <th>Действие</th>
                                    </tr>
                                </thead>
                                <tbody id="chance_major">
                                    @if($chances_major) @foreach($chances_major as $user)
                                    <tr>
                                        <td><img src="{{ $user['avatar'] }}" width="35px" class="img-circle"></td>
                                        <td class="clip">{{ $user['username'] }}</td>
                                        <td>{{ $user['chance'] }}%</td>
                                        <td><button class="btn btn-primary btn-outline btn-xs" onclick="gotRoulette({{ $user['id'] }})">подкрутить</button></td>
                                    </tr>
                                    @endforeach @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="panel panel-default border-panel card-view">
                    <div class="panel-heading">
                        <div class="text-center">
                            <h6 class="panel-title txt-dark">Управление ботом</h6>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-wrapper collapse in">
                        <div class="panel-body row">
                            <div class="text-center">
                                <div class="col-md-6 mb-5"><a href="/admin/botOn" class="btn btn-success btn-rounded">Включить бота</a></div>
                                <div class="col-md-6 mb-5"><a href="/admin/botOff" class="btn btn-danger btn-rounded">Выключить бота</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
		<div class="panel panel-default border-panel card-view">
			<div class="panel-heading">
				<div class="text-center">
					<h6 class="panel-title txt-dark">Подкрутка (Батл)</h6>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="text-center mb-5">
						<button class="btn btn-danger btn-rounded gotBattle" data-color="red">Красная</button>
						<button class="btn btn-primary btn-rounded gotBattle" data-color="blue">Синяя</button>
					</div>
				</div>
			</div>
		</div>
	</div>
    <div class="col-sm-4">
		<div class="panel panel-default border-panel card-view">
			<div class="panel-heading">
				<div class="text-center">
					<h6 class="panel-title txt-dark">Подкрутка (Дабл)</h6>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="text-center mb-5">
						<button class="btn btn-danger btn-rounded gotDouble" data-color="red">Красное</button>
						<button class="btn btn-success btn-rounded gotDouble" data-color="green">Зеленое</button>
						<button class="btn btn-default btn-rounded gotDouble" data-color="black">Черное</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="panel panel-default border-panel card-view">
                    <div class="panel-heading">
                        <div class="text-center">
                            <h6 class="panel-title txt-dark">Ставка в Jackpot</h6>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-wrapper collapse in">
						<div class="panel-body">
							<div class="form-group">
								<label class="control-label mb-10 text-left">Выбрать пользователя</label>
								<select class="form-control" id="users_jackpot">
									@foreach($fake as $user)
										<option value="{{ $user->user_id }}">{{ $user->username }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group">
								<label class="control-label mb-10 text-left">Сумма ставки</label>
								<input type="text" class="form-control" placeholder="0" id="sum_jackpot">
							</div>
							<div class="form-group">
								<label class="control-label mb-10 text-left">Комната</label>
								<select class="form-control" id="room_jackpot">
									<option value="small">Small</option>
									<option value="classic">Classic</option>
									<option value="major">Major</option>
								</select>
							</div>
							<div class="text-center mt-10">
								<button class="btn btn-success betJackpot">Поставить</button>
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
	<div class="col-sm-6">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="panel panel-default border-panel card-view">
                    <div class="panel-heading">
                        <div class="text-center">
                            <h6 class="panel-title txt-dark">Ставка в Double</h6>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-wrapper collapse in">
						<div class="panel-body">
							<div class="form-group">
								<label class="control-label mb-10 text-left">Выбрать пользователя</label>
								<select class="form-control" id="users_double">
									@foreach($fake as $user)
										<option value="{{ $user->user_id }}">{{ $user->username }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group">
								<label class="control-label mb-10 text-left">Сумма ставки</label>
								<input type="text" class="form-control" placeholder="0" id="sum_double">
							</div>
							<div class="form-group">
								<label class="control-label mb-10 text-left">Цвет</label>
								<select class="form-control" id="color_double">
									<option value="red">Красный</option>
									<option value="green">Зеленый</option>
									<option value="black">Черный</option>
								</select>
							</div>
							<div class="text-center mt-10">
								<button class="btn btn-success betDouble">Поставить</button>
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default border-panel card-view">
			<div class="panel-wrapper collapse in">
				<div class="panel-body pa-0">
					<div class="chat-cmplt-wrap">
						<div class="recent-chat-wrap">
							<div class="panel-heading">
								<div class="text-center">
									<h6 class="panel-title txt-dark">Чат</h6>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="panel-wrapper collapse in">
								<div class="panel-body">
									<div class="chat-content">
										<ul class="chatapp-chat-nicescroll-bar pt-20" style="overflow: hidden; width: auto; height: 483px;">
											@if($messages != 0) @foreach($messages as $sms)
											<li class="friend">
												<div class="friend-msg-wrap">
													<img class="user-img img-circle block pull-left" src="{{$sms['avatar']}}" alt="user">
													<div class="msg pull-left">
														<p>{!!$sms['messages']!!}</p>
														<div class="msg-per-detail text-right">
															<span class="msg-time txt-light">{{$sms['username']}}</span>
														</div>
													</div>
													<div class="clearfix"></div>
												</div>	
											</li>
											@endforeach @endif
										</ul>
									</div>
									<div class="form-group" style="margin-top: 10px;">
										<label>Выбрать пользователя</label>
										<select class="form-control" id="users">
											@foreach($fake as $user)
												<option value="{{ $user->user_id }}">{{ $user->username }}</option>
											@endforeach
										</select>
									</div>
									<div class="input-group">
										<input type="text" id="chatmess" name="chatmess" class="input-msg-send form-control" placeholder="Написать сообщение">
										<div class="input-group-btn attachment">
											<div class="fileupload btn  btn-default"><i class="ti-shift-right" id="chatsend"></i></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="panel panel-default border-panel card-view">
                    <div class="panel-heading">
                        <div class="text-center">
                            <h6 class="panel-title txt-dark">Топ богачей</h6>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-wrapper collapse in" style="height: 646px; overflow-x: hidden;">
                        <div class="panel-body row pa-0">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Пользователь</th>
                                        <th>Баланс</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@foreach($userTop as $top)
									<tr>
										<td><img src="{{$top->avatar}}" width="35px" class="img-circle"></td>
										<td><a href="/admin/user/{{$top->id}}">{{$top->username}}</a></td>
										<td>{{ $top->balance }}р.</td>
									</tr>
									@endforeach
								</tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<div class="row">
    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="panel panel-default border-panel card-view">
                    <div class="panel-heading">
                        <div class="text-center">
                            <h6 class="panel-title txt-dark">Последние пополнения</h6>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-wrapper collapse in" style="height: 370px; overflow-x: hidden;">
                        <div class="panel-body row pa-0">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Пользователь</th>
                                        <th>Сумма</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @foreach($last_dep as $pay)
                                    <tr>
                                        <td><img src="{{$pay['avatar']}}" width="35px" class="img-circle"></td>
                                        <td><a href="/admin/user/{{$pay['id']}}">{{$pay['username']}}</a></td>
                                        <td>{{$pay['sum']}}р.</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="panel panel-default border-panel card-view">
                    <div class="panel-heading">
                        <div class="text-center">
                            <h6 class="panel-title txt-dark">Последние пользователи</h6>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-wrapper collapse in" style="height: 370px; overflow-x: hidden;">
                        <div class="panel-body row pa-0">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Пользователь</th>
                                        <th>Когда зарегистрировался</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @foreach($users as $user)
                                    <tr>
                                        <td><img src="{{$user->avatar}}" width="35px" class="img-circle"></td>
                                        <td><a href="/admin/user/{{$user->id}}">{{$user->username}}</a></td>
                                        <td>{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection