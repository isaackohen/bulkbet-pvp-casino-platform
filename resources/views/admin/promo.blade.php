@extends('panel')

@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default border-panel card-view">
			<div class="panel-heading">
				<div class="pull-left">
					<h6 class="panel-title txt-dark">
						Промокоды
					</h6>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
                    <div id="createPromo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createPromoLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h5 class="modal-title" id="myModalLabel">Новый промокод</h5>
                                </div>
                                <form action="/admin/promoNew" method="post">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label mb-10">Код (только английские символы):</label>
                                            <input type="text" class="form-control" name="code" placeholder="Код">
                                        </div>
                                        <div class="form-group">
                                            <label for="message-text" class="control-label mb-10">Лимит:</label>
                                            <select class="form-control" name="limit">
                                                <option value="0">Без лимита</option>
                                                <option value="1">По кол-ву</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label mb-10">Сумма (в монетах):</label>
                                            <input type="text" class="form-control" name="amount" placeholder="Сумма">
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label mb-10">Кол-во:</label>
                                            <input type="text" class="form-control" name="count_use" placeholder="Кол-во">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                                        <button type="submit" class="btn btn-success">Создать</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @foreach($codes as $code)
                    <div id="editPromo{{$code->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createPromoLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h5 class="modal-title" id="myModalLabel">Редактировать промокод</h5>
                                </div>
                                <form action="/admin/promoSave" method="post">
                                    <div class="modal-body">
                                        <input type="hidden" value="{{$code->id}}" name="id">
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label mb-10">Код (только английские символы):</label>
                                            <input type="text" class="form-control" name="code" placeholder="Код" value="{{$code->code}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="message-text" class="control-label mb-10">Лимит:</label>
                                            <select class="form-control" name="limit">
                                                <option value="0" @if($code->limit == 0) selected @endif>Без лимита</option>
                                                <option value="1" @if($code->limit == 1) selected @endif>По кол-ву</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label mb-10">Сумма (в монетах):</label>
                                            <input type="text" class="form-control" name="amount" placeholder="Сумма" value="{{$code->amount}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label mb-10">Кол-во:</label>
                                            <input type="text" class="form-control" name="count_use" placeholder="Кол-во" value="{{$code->count_use}}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                                        <button type="submit" class="btn btn-success">Сохранить</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="text-center">
                        <a class="btn btn-success btn-rounded" data-toggle="modal" data-target="#createPromo">Создать промокод</a>
                    </div>
                    <table id="datable_1" class="bootstrap-table bootstrap-table-hover display  pb-30 dataTable" role="grid" aria-describedby="datable_1_info">
                        <thead>
                            <tr>
                                <th>Код</th>
                                <th>Лимит</th>
                                <th>Сумма</th>
                                <th>Кол-во</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($codes as $code)
                            <tr>
                                <td>{{$code->code}}</td>
                                <td>@if($code->limit) По кол-ву @else Без лимита @endif</td>
                                <td>{{$code->amount}}pt</td>
                                <td>{{$code->count_use}}</td>
                                <td class="text-center"><a class="btn btn-primary btn-rounded btn-xs" data-toggle="modal" data-target="#editPromo{{$code->id}}">Редактировать</a> / <a href="/admin/promoDelete/{{$code->id}}" class="btn btn-danger btn-rounded btn-xs">Удалить</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Код</th>
                                <th>Лимит</th>
                                <th>Сумма</th>
                                <th>Кол-во</th>
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