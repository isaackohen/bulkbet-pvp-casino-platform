@extends('panel')

@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default border-panel card-view">
			<div class="panel-heading">
				<div class="pull-left">
					<h6 class="panel-title txt-dark">
						Пользователи
					</h6>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
                    <table id="datable_users" class="bootstrap-table bootstrap-table-hover display  pb-30 dataTable" role="grid" aria-describedby="datable_1_info" >
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Имя Фамилия</th>
                                <th>Пользователь</th>
                                <th>Баланс</th>
                                <th>Привилегии</th>
                                <th>IP Адрес</th>
                                <th>Бан</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Имя Фамилия</th>
                                <th>Пользователь</th>
                                <th>Баланс</th>
                                <th>Привилегии</th>
                                <th>IP Адрес</th>
                                <th>Бан</th>
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