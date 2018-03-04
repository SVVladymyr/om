@extends('layouts.master')

@section('content')
<h1 class="center-h1">Создание спецификации</h1>
<div class="col-xs-12 col-md-3 create-edit" style="margin-top: 20px;width: 100%;float: none;font-size: 0;max-width: 100%;">
			{!! Form::open(['route' => 'specifications']) !!}

			<div class="input-block">
				{!! Form::label('name', 'Имя'); !!}
				{!! Form::text('name', null, ['placeholder'=>'Имя', 'class'=>'form-control']); !!}
			</div>


@if(Auth::user()->isClientAdmin())
<div class="input-block">
			{!! Form::label('order_begin', 'Первый возможный день для заказа'); !!}
				{!! Form::number('order_begin', null, ['placeholder'=> 'Первый возможный день для заказа', 'class'=>'form-control']); !!}
			</div>
<div class="input-block">
    		{!! Form::label('order_end', 'Последний возможный день для заказа'); !!}
    		{!! Form::number('order_end', null, ['class'=>'form-control', 'placeholder'=>'Последний возможный день для заказа']); !!}
				</div>
@endif
	{!! Form::submit('Создать', ['class'=>'btn btn-large btn-primary btn-margin mob']); !!}

</div>
@if(count($products))
	<div class="mobile-toogle">
<table class="table table-bordered">
	<tr class="first-table-tr">
		<th><span>Артикул<span data-text="Артикул товара"></span> </span></th>
		<th><span>Название<span data-text="Название товара"></span> </span></th>
		<th><span>Цена<span data-text="Цена товара"></span> </span></th>
		<th><span>Выбрать<span data-text="Выбрать товар в спецификацию"></span></span></th>
		<th><span>Лимит<span data-text="Лимит указаный на товар"></span> </span></th>
		<th><span>Период<span data-text="Период указаный на обноваления лимита"></span></span></th>
	</tr>

@foreach($products as $product)

	<tr>
		<td>{{ $product->model }}</td>
		<td>{{ $product->description->name }}</td>
		<td data-sum>  {{ $product->price }}</td>
		<td style="    text-align: center;">{!! Form::checkbox("items[$product->product_id]", $product->product_id, $product->selected); !!}</td>
		<td data-lem>{!! Form::number("limits[$product->product_id]", null,['placeholder' => 'Без лимита']); !!}</td>
		<td>{!! Form::number("periods[$product->product_id]"); !!}</td>
	</tr>

@endforeach

</table>
	</div>

@endif


{{--    			{!! Form::submit('Создать', ['class'=>'btn btn-large btn-primary btn-margin mob']); !!}--}}

			{!! Form::close() !!}
			<script>
			for(let i = 0 ; i < $("[data-sum]").length; i++){
			    if(!!$($("[data-sum]")[i]).text()) $($("[data-sum]")[i]).text((+$($("[data-sum]")[i]).text()).toFixed(2))
			}
			for(let i = 0 ; i < $("[data-lem]").length; i++){
			   if(!!$($("[data-lem]")[i]).find('input').val()) $($("[data-lem]")[i]).find('input').val((+$($("[data-lem]")[i]).find('input').val()).toFixed(0))
			}
			</script>
@endsection
<style>
	.create-edit input {
		font-size: 12px;
		padding-left: 10px!important;
	}
</style>
