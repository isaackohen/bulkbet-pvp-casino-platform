@extends('panel')

@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default border-panel card-view">
			<div class="panel-heading">
				<div class="pull-left">
					<h6 class="panel-title txt-dark">
						Активные запросы
					</h6>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
                    <table id="datable_1" class="table table-hover display  pb-30 dataTable" role="grid" aria-describedby="datable_1_info">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Сумма</th>
                                <th>Система</th>
                                <th>Кошелек</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdraws as $withdraw)
                            <tr>
                                <td>{{$withdraw['id']}}</td>
                                <td><a href="/admin/user/{{$withdraw['user_id']}}"><img src="{{$withdraw['avatar']}}" style="width:50px;border-radius:50%;margin-right:10px;vertical-align:middle;"> {{$withdraw['username']}}</a></td>
                                <td>{{$withdraw['value']}}р</td>
                                <td>{{$withdraw['system']}}</td>
                                <td>{{$withdraw['wallet']}}</td>
                                <td class="text-center"><a href="/admin/withdraw/{{$withdraw['id']}}" class="btn btn-primary btn-rounded btn-xs">Отправить</a> <a href="/admin/return/{{$withdraw['id']}}" class="btn btn-danger btn-rounded btn-xs">Вернуть</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Сумма</th>
                                <th>Система</th>
                                <th>Кошелек</th>
                                <th>Действия</th>
                            </tr>
                        </tfoot>
                    </table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default border-panel card-view">
			<div class="panel-heading">
				<div class="pull-left">
					<h6 class="panel-title txt-dark">
						Обработанные запросы
					</h6>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
                    <table id="datable_2" class="table table-hover display  pb-30 dataTable" role="grid" aria-describedby="datable_2_info">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Сумма</th>
                                <th>Система</th>
                                <th>Кошелек</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($finished as $finish)
                            <tr>
                                <td>{{$finish['id']}}</td>
								<td><a href="/admin/user/{{$finish['user_id']}}"><img src="{{$finish['avatar']}}" style="width:50px;border-radius:50%;margin-right:10px;vertical-align:middle;"> {{$finish['username']}}</a></td>
                                <td>{{$finish['value']}}р</td>
                                <td>{{$finish['system']}}</td>
                                <td>{{$finish['wallet']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Сумма</th>
                                <th>Система</th>
                                <th>Кошелек</th>
                            </tr>
                        </tfoot>
                    </table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection