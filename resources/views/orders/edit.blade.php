@extends('layouts.master')

@section('content')
<script type="text/javascript">
$('.create-orders-info-money-limit').hide();
$('.create-orders-info-money-limit-info').hide()
</script>
<h1 class="center-h1">Редактирование заказа</h1>
			{!! Form::open(['url' => "orders/update/$order->id"]) !!}
<table class="table table-bordered">
	<tr class="first-table-tr">
		<th><span>Название<span data-text="Название статьи затрат"></span></span></th>
		<th><span>Доступный остаток<span data-text="Доступный остаток лимита"></span></span></th>
		<th><span>Лимит<span data-text="Общий лимит статьи затрат"></span></span></th>

	</tr>
@foreach($limits as $limit)
<tr>
	@if($limit->limitable_type == 'Money')
	<script>
			$('.create-orders-info-money-limit').text((+'{{$limit->current_value}}').toFixed(2)+' грн')
			$('.create-orders-info-money-limit').show();
			$('.create-orders-info-money-limit-info').show()
	</script>
		<td>Денежный лимит</td>
		<td data-sum>{{ $limit->current_value }}</td>
		<td data-sum>{{ $limit->limit }}</td>
	@elseif($limit->limitable_type == 'App\CostItem')
		<td>{{ $limit->limitable->name }}</td>
		<td data-sum>{{ $limit->current_value }}</td>
		<td data-sum>{{ $limit->limit }}</td>
	@endif
	</tr>
@endforeach

@if(count($products))
<table class="table table-bordered">
	<tr  class="first-table-tr">
		<th><span>Артикул<span data-text="Артикул товара"></span> </span></th>
		<th><span>Название<span data-text="Название товара"></span> </span></th>
		<th><span>Цена<span data-text="Цена товара"></span> </span></th>
		<th><span>Количество<span data-text="Количество товара"></span> </span></th>
		<th><span>Доступно<span data-text="Доступно товаров для заказа"></span> </span></th>
		<th><span>Лимит<span data-text="Лимит указаный на товар"></span> </span></th>
	</tr>
@foreach($products as $product)

	<tr>
		<td>{{ $product->model }}</td>
		<td>{{ $product->description->name }}</td>
		<td data-sum >{{ $product->price }}</td>
		<td data-key>{!! Form::number("amounts[$product->product_id]", $product->amount); !!}</td>
		<td data-available>{{ $product->current_value }}</td>
		<td data-limit>{{ $product->limit }}</td>
	</tr>

@endforeach

</table>

@endif


    			{!! Form::submit('Обновить', ['class'=>'btn btn-large btn-success mob']); !!}

			{!! Form::close() !!}

			<script>
			for(let i = 0 ; i < $("[data-sum]").length; i++){
					$($("[data-sum]")[i]).text((+$($("[data-sum]")[i]).text()).toFixed(2))
			}
			for(let i = 0 ; i < $("[data-available]").length; i++){
				if($($("[data-available]")[i]).text() != '') $($("[data-available]")[i]).text((+$($("[data-available]")[i]).text()).toFixed(2))
				else $($("[data-available]")[i]).text('Нет ограничений')
			}
			for(let i = 0 ; i < $("[data-limit]").length; i++){
					if($($("[data-limit]")[i]).text() != '') $($("[data-limit]")[i]).text((+$($("[data-limit]")[i]).text()).toFixed(2))
					else  $($("[data-limit]")[i]).text('Без лимита')
			}
			var prevVal;
	    $("[data-key]").keydown(function() {
	        prevVal = $(this).find('input').val();
	    });
	    $("[data-key]").keyup(function() {
	        var val = $(this).find('input').val();
	        if (!+val && val !=='' || +val < 0 ) $(this).find('input').val(prevVal);
					setTimeout(function(){
						count()
					},1)
	    });
			function count (){
				var total = '0';
				for(var i = 0 ; i <  $("[data-key]").length; i++){
						total = +total + ((+($($("[data-key]")[i]).parent().find("[data-sum]").text())) * +$($("[data-key]")[i]).find('input').val() );
				}
				$('.create-orders-info-money-total').text(total.toFixed(2)+'грн')
			}
			$("[data-key]").click(function() {
	        var val = $(this).find('input').val();
	        if (!+val && val !=='' || +val < 0 ) $(this).find('input').val(prevVal);
					setTimeout(function(){
						var total = '0';
						for(var i = 0 ; i <  $("[data-key]").length; i++){
								total = +total + ((+($($("[data-key]")[i]).parent().find("[data-sum]").text())) * +$($("[data-key]")[i]).find('input').val() );
						}
						$('.create-orders-info-money-total').text(total.toFixed(2)+'грн')
					},1)
	    });
			count();
			</script>
@endsection
