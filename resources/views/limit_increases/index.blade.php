@extends('layouts.master')

@section('content')

@if(count($items))
{!! Form::open(['url' => "clients/$client->id/limit_increases"]) !!}

<table class="table table-bordered button-style">
	<tr class="first-table-tr">
		<th>id</th>
		<th><span>Товар<span data-text="Запрошенный товар"></span></span></th>
		<th><span>Количество<span data-text="Количество запрошенных товаров"></span></span></th>
		<th><span>Статусы<span data-text="Статусы запрошенных товаров"></span></span></th>
		<th><span>Создан<span data-text="Создания запрошенного товара"></span></span></th>
		<th><span>Дата подтверждения<span data-text="Дата подтверждения запрошенного товара"></span></span></th>
		<th><span>Дата отказа<span data-text="Дата отказа запрошенного товара"></span></span></th>
	</tr>



@foreach($items as $item)

	<tr>
		<td>{{ $item->id }}</td>
		<td>{{ $item->item }}</td>
		<td>{{ $item->amount_asked }}</td>
		@if($item->confirmed_at == null && $item->aborted_at == null)
		<td>{!! Form::select("statuses[$item->id]", ['1' => 'Подтвержденно', '0' => 'Отказ', 'null' => 'Ожидание'], 'null'); !!}</td>
		@else
		<td></td>
		@endif
		<td>{{ $item->created_at }}</td>
		<td>{{ $item->confirmed_at }}</td>
		<td>{{ $item->aborted_at }}</td>
	</tr>


@endforeach



</table>



{!! Form::submit('Установить'); !!}

{!! Form::close() !!}

@endif

@endsection
