<div class="title flex flex-between flex-align-center">
    <span>История счета</span>
    <div class="r">
		<ul class="room-selector">
			<li class="room">
				<a>
					<div class="room-name">Пополнения</div>
				</a>
			</li>
			<li class="room">
				<a>
					<div class="room-name">Выводы</div>
				</a>
			</li>
			<li class="room">
				<a>
					<div class="room-name">Запросы на вывод</div>
				</a>
			</li>
		</ul>
    </div>
</div>

<div class="content">
    <div class="alert">
        <span>Активируйте Реферальный/Промо код и получите 5 рублей на баланс.</span>
        <span class="alert-close tooltip" data-close="alert" title="Закрыть">×</span>
    </div>
   <div class="historyTable">
	<div class="payHistory">
		<table class="list">
			<thead>
				<tr>
					<th>Номер</th>
					<th>Тип</th>
					<th>Система</th>
					<th>Сумма</th>
					<th>Статус</th>
				</tr>
			</thead>
			<tbody>
				@foreach($pays as $pay)
				<tr>
					<td><div class="id">{{$pay->id}}</div></td>
					<td><div class="type ok">@if($pay->status == 1) Пополнение баланса @elseif($pay->status == 2) Реферальный код @else Промокод @endif</div></td>
					<td><div class="system">@if($pay->status == 1) Free-kassa @else {{$pay->code}} @endif</div></td>
					<td><div class="sum ok">+{{$pay->price}}</div></td>
					<td><div class="status ok">Выполнен</div></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
   </div>
   <div class="historyTable">
	<div class="payHistory">
		<table class="list">
			<thead>
				<tr>
					<th>Номер</th>
					<th>Тип</th>
					<th>Система</th>
					<th>Сумма</th>
					<th>Статус</th>
				</tr>
			</thead>
			<tbody>
				@foreach($withdraws as $with)
				<tr>
					<td><div class="id">{{$with->id}}</div></td>
					<td><div class="type {{ $with->status ? 'ok' : 'dec' }}">Вывод</div></td>
					<td><div class="system">{{$with->system}}</div></td>
					<td><div class="sum {{ $with->status ? 'ok' : 'dec' }}">@if($with->status == 2) +{{$with->value}} @else -{{$with->value}} @endif</div></td>
					<td><div class="status {{ $with->status ? 'ok' : 'dec' }}">@if($with->status == 0) На модерации @elseif($with->status == 1) Выполнен @else Возвращен @endif</div></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
   </div>
   <div class="historyTable">
	<div class="payHistory">
		<table class="list">
			<thead>
				<tr>
					<th>Номер</th>
					<th>Система</th>
					<th>Сумма</th>
					<th>Действие</th>
				</tr>
			</thead>
			<tbody>
				@foreach($active as $a)
				<tr>
					<td><div class="id">{{$a->id}}</div></td>
					<td><div class="system">{{$a->wallet}} ({{$a->system}})</div></td>
					<td><div class="sum {{ $a->status ? 'ok' : 'dec' }}">@if($a->status == 2) +{{$a->value}} @else -{{$a->value}} @endif</div></td>
					<td><div class="status"><a class="buttoninzc tooltip" title="Отменить" href="/withdraw/cancel/{{$a->id}}"><i class="fas fa-times"></i></a></div></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
   </div>
</div>