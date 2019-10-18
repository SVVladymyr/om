
  	<tr style="color: #000; cursor: pointer;" class="table-td">
		<td><input name="select-items" type="checkbox"></td>
		<td onclick="location.href ='/orders/{{ $order->id }}'">{{ $order->client->name }}</td>
		<td onclick="location.href ='/orders/{{ $order->id }}'">{{ $order->customer->first_name }}</td>
		<td onclick="location.href ='/orders/{{ $order->id }}'" data-sum >{{ $order->sum }}</td>
		<td onclick="location.href ='/orders/{{ $order->id }}'" data-id="{{ $order->id }}">{{ $order->status->name }}</td>
		<td onclick="location.href ='/orders/{{ $order->id }}'">{{ $order->subs }}</td>

	@if(Auth::user()->isClientAdmin() && ($order->status_id == 1 || $order->status_id == 2 || $order->status_id == 5))
		<td onclick="location.href ='/orders/{{ $order->id }}'"></td>
		<td>{!! Form::select("statuses[$order->id]", ['2' => 'Confirmed', '1' => 'Ждет подтверждение','5'=>'Отменено'], $order->status_id); !!}</td>

	@elseif(Auth::user()->isClientAdmin() && ($order->status_id == 3 || $order->status_id == 4))
		<td>{!! Form::select("statuses[$order->id]", ['4' => 'Delivered', '3' => 'Waiting'], $order->status_id); !!}</td>
		<td onclick="location.href ='/orders/{{ $order->id }}'"></td>

	@elseif(Auth::user()->isManager())

		@if($order->status_id == 2||$order->status_id == 5)
		<td>{!! Form::select("statuses[$order->id]", ['3' => 'Confirmed', '2' => 'Ждет подтверждение','5'=>'Отменено'], $order->status_id); !!}</td>
		@else
		<td onclick="location.href ='/orders/{{ $order->id }}'"></td>
		@endif

	@elseif(Auth::user()->isConsumer())
		@if($order->status_id == 3 || $order->status_id == 4)
			<td>{!! Form::select("statuses[$order->id]", ['4' => 'Delivered', '3' => 'Waiting'], $order->status_id); !!}</td>
		@else
		<td onclick="location.href ='/orders/{{ $order->id }}'"></td>
		@endif

	@elseif(Auth::user()->isSublevel() && $order->status_id == 1)
		<td onclick="location.href ='/orders/{{ $order->id }}'"></td>
			<td>{!! Form::select("statuses[$order->id]", ['true' => 'Confirmed', 'false' => 'Ждет подтверждение'], $order->sublevel_confirm); !!}</td>

	@elseif(Auth::user()->isSublevel() && ($order->status_id == 3 || $order->status_id == 4))
		<td>{!! Form::select("statuses[$order->id]", ['4' => 'Delivered', '3' => 'Waiting'], $order->status_id); !!}</td>
		<td onclick="location.href ='/orders/{{ $order->id }}'"></td>
	@else
		<td onclick="location.href ='/orders/{{ $order->id }}'"></td>
		<td onclick="location.href ='/orders/{{ $order->id }}'"></td>
	@endif
		<td onclick="location.href ='/orders/{{ $order->id }}'">{{ $order->created_at }}</td>
	@if(Auth::user()->isManager() && $order->status_id == 3)
		<td onclick="location.href ='/orders/{{ $order->id }}'">{!! Form::date("dates[$order->id]", $order->expected_delivery_date); !!}</td>
	@else
		<td onclick="location.href ='/orders/{{ $order->id }}'">{{ $order->expected_delivery_date }}</td>
	@endif
		<td onclick="location.href ='/orders/{{ $order->id }}'">{{ $order->delivery_date }}</td>
</tr>
