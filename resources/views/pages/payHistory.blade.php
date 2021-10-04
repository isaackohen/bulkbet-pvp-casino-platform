<div class="title flex flex-between flex-align-center">
    <span>{{ __('platform.payhistory.title') }}</span>
    <div class="r">
		<ul class="room-selector">
			<li class="room">
				<a>
					<div class="room-name">{{ __('platform.payhistory.menu.deposits') }}</div>
				</a>
			</li>
			<li class="room">
				<a>
					<div class="room-name">{{ __('platform.payhistory.menu.withdraws') }}</div>
				</a>
			</li>
			<li class="room">
				<a>
					<div class="room-name">{{ __('platform.payhistory.menu.pendingwithdraws') }}</div>
				</a>
			</li>
		</ul>
    </div>
</div>

<div class="content">
   <div class="historyTable">
	<div class="payHistory">
		<table class="list">
			<thead>
				<tr>
					<th>{{ __('platform.payhistory.table.id') }}</th>
					<th>{{ __('platform.payhistory.table.type') }}</th>
					<th>{{ __('platform.payhistory.table.system') }}</th>
					<th>{{ __('platform.payhistory.table.amount') }}</th>
					<th>{{ __('platform.payhistory.table.status') }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach($pays as $pay)
				<tr>
					<td><div class="id">{{$pay->id}}</div></td>
					<td><div class="type ok">@if($pay->status == 1) {{ __('platform.payhistory.td.deposit') }} @elseif($pay->status == 2) {{ __('platform.payhistory.td.referralcode') }} @else {{ __('platform.payhistory.td.promocode') }} @endif</div></td>
					<td><div class="system">@if($pay->status == 1) Free-kassa @else {{$pay->code}} @endif</div></td>
					<td><div class="sum ok">+{{$pay->price}}</div></td>
					<td><div class="status ok">{{ __('platform.payhistory.td.completed') }}</div></td>
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
					<th>{{ __('platform.payhistory.table.id') }}</th>
					<th>{{ __('platform.payhistory.table.type') }}</th>
					<th>{{ __('platform.payhistory.table.system') }}</th>
					<th>{{ __('platform.payhistory.table.amount') }}</th>
					<th>{{ __('platform.payhistory.table.status') }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach($withdraws as $with)
				<tr>
					<td><div class="id">{{$with->id}}</div></td>
					<td><div class="type {{ $with->status ? 'ok' : 'dec' }}">{{ __('platform.payhistory.td.withdraw') }}</div></td>
					<td><div class="system">{{$with->system}}</div></td>
					<td><div class="sum {{ $with->status ? 'ok' : 'dec' }}">@if($with->status == 2) +{{$with->value}} @else -{{$with->value}} @endif</div></td>
					<td><div class="status {{ $with->status ? 'ok' : 'dec' }}">@if($with->status == 0) {{ __('platform.payhistory.td.pendingmoderation') }} @elseif($with->status == 1) {{ __('platform.payhistory.td.completed') }} @else {{ __('platform.payhistory.td.denied') }} @endif</div></td>
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
					<th>{{ __('platform.payhistory.table.id') }}</th>
					<th>{{ __('platform.payhistory.table.system') }}</th>
					<th>{{ __('platform.payhistory.table.amount') }}</th>
					<th>{{ __('platform.action') }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach($active as $a)
				<tr>
					<td><div class="id">{{$a->id}}</div></td>
					<td><div class="system">{{$a->wallet}} ({{$a->system}})</div></td>
					<td><div class="sum {{ $a->status ? 'ok' : 'dec' }}">@if($a->status == 2) +{{$a->value}} @else -{{$a->value}} @endif</div></td>
					<td><div class="status"><a class="buttoninzc tooltip" title="{{ __('platform.cancel') }}" href="/withdraw/cancel/{{$a->id}}"><i class="fas fa-times"></i></a></div></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
   </div>
</div>
