@extends('panel')

@section('content')
<div class="row">
	<div class="col-sm-4">
		<div class="panel panel-default card-view">
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="profile-box">
						<div class="profile-info text-center mb-15">
							<div class="profile-img-wrap">
								<img alt="user" class="inline-block mb-10" src="{{$user->avatar}}">
							</div>
							<h5 class="block mt-10 weight-500 capitalize-font txt-dark">
								{{$user->username}}
							</h5>
							<h6 class="block capitalize-font">
								@if($user->is_admin) Администратор @elseif($user->is_moder) Модератор @elseif($user->is_youtuber) YouTube`r @elseif($user->fake) Бот @else Пользователь @endif
							</h6>
						</div>
						<div class="social-info">
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$dep}} руб</span> <span class="counts-text block">сумма пополнений</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$with}} руб</span> <span class="counts-text block">сумма выводов</span>
								</div>
							</div>
						</div>
						<div class="social-info">
							<h6 class="block capitalize-font text-center">
								Бонусы
							</h6>
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$bonus}} руб</span> <span class="counts-text block">сумма полученных бонусов</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$ref}} руб</span> <span class="counts-text block">сумма за приглашенных рефералов</span>
								</div>
							</div>
						</div>
						<div class="social-info">
							<h6 class="block capitalize-font text-center">
								Рефка
							</h6>
							<div class="row">
								<div class="col-xs-12 text-center">
									<span class="counts block head-font">{{ $refcount }}</span> <span class="counts-text block">пригласил</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-8">
        <div class="panel panel-default card-view">
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div class="form-wrap">
                        <form class="form-horizontal" method="post" action="/admin/userSave">
                            <input name="id" value="{{$user->id}}" type="hidden">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Фамилия Имя</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="username" value="{{$user->username}}" readonly>
                                </div>
                            </div>
							@if(!$user->fake)
                            <div class="form-group">
                                <label class="col-sm-3 control-label">IP адрес</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="username" value="{{$user->ip}}" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Баланс</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="balance" value="{{$user->balance}}" id="balance">
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" value="{{$user->balance}} руб." id="rub" readonly>
                                </div>
                            </div>
<!--
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Сколько человек пригласил</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="https://vk.com/id{{$user->user_id}}" readonly>
                                </div>
                            </div>
-->
                            <div class="form-group">
                                <label class="control-label col-md-3">Привилегии</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="priv">
                                        <option value="admin" @if($user->is_admin) selected @endif>Администратор</option>
                                        <option value="moder" @if($user->is_moder) selected @endif>Модератор</option>
                                        <option value="youtuber" @if($user->is_youtuber) selected @endif>YouTube`r</option>
                                        <option value="user" @if(!$user->is_admin && !$user->is_moder && !$user->is_youtuber) selected @endif>Пользователь</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Забанен</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="ban">
                                        <option value="0" @if($user->ban == 0) selected @endif>Нет</option>
                                        <option value="1" @if($user->ban == 1) selected @endif>Да</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button class="btn btn-success" type="submit">Сохранить</button>
                                </div>
                            </div>
							@endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection