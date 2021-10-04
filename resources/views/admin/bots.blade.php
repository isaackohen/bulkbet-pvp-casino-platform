@extends('panel')

@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default border-panel card-view">
			<div class="panel-heading">
				<div class="pull-left">
					<h6 class="panel-title txt-dark">
						Боты
					</h6>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
                    <div class="text-center mb-10">
						<div class="title">Добавление бота</div>
						<div class="form-wrap">
							<form method="post" action="/admin/fakeSave" class="horizontal-form" id="save">
								<div class="form-group">
									<div class="input-group">
										<input type="text" name="url" id="url" class="form-control" placeholder="Ссылка на страницу фейк пользователя">
										<span class="input-group-btn">
											<button type="submit" class="btn btn-orange btn-anim"><i class="ti-check"></i><span class="btn-text">Сохранить</span></button>
										</span> 
									</div>
								</div>
							</form>
							<div class="form-wrap">
								<div class="form-inline" id="prof" style="display: none">
									<div class="profile"><div class="avatar"><img src="" alt="" id="ava"><a href="" class="btn-vk" target="_blank"></a></div></div>
									<div class="form-group mr-15">
										<label class="control-label mr-10" for="name">Имя Фамилия</label>
										<input type="text" class="form-control" name="username" value="" readonly="readonly" id="name">
									</div>
									<div class="form-group mr-15">
										<label class="control-label mr-10" for="vkId">VK ID</label>
										<input type="text" class="form-control" name="user_id" value="" readonly="readonly" id="vkId">
									</div>
								</div>
							</div>
						</div>
                    </div>
                    <table id="datable_1" class="bootstrap-table bootstrap-table-hover display  pb-30 dataTable" role="grid" aria-describedby="datable_1_info">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Профиль VK</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bots as $bot)
                            <tr>
                                <td>{{$bot->id}}</td>
                                <td><img src="{{$bot->avatar}}" style="width:50px;border-radius:50%;margin-right:10px;vertical-align:middle;"> {{$bot->username}}</td>
                                <td><a href="https://vk.com/id{{$bot->user_id}}" target="_blank">Перейти</a></td>
                                <td class="text-center"><a href="/admin/user/delete/{{$bot->id}}" class="btn btn-danger btn-rounded btn-xs">Удалить</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Профиль VK</th>
                                <th>Действия</th>
                            </tr>
                        </tfoot>
                    </table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection