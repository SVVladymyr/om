@extends('layouts.master')

@section('content')
<div ng-controller="orders" class="main-body ng-scope flex" data-ui-view="" data-flex="">
    <md-card class="md-table ng-scope _md">
    <md-card-content>
      <div class="md-table-loader" data-ng-if="!loaded">
            <md-progress-circular md-mode="indeterminate"></md-progress-circular>
        </div>
                <section class="md-table-header">
            <div class="md-table-header-title">
                            <span ng-click="toggleRight()" >Создание заказа</span>
                        </div>
        </section>
{!! Form::open(['route' => ['orders', $client->id]]) !!}

@if(count($products))
<table class="table table-bordered">
	<tr class="first-table-tr">
		<th>Артикул</th>
		<th>Название</th>
		<th>Цена</th>
		<th><span>Количество<span data-text="Укажите количество, которое хотите заказать. После того как укажите количество по всем товарам - щелкните кнопку 'Создать товар' "></span> </span></th>
		<th><span>Доступно<span data-text="В этой колонке отображается доступное для заказа количество товара. Изначально доступное количество товара равно лимиту, и уменьшается по мере создания Вами заказов. С периодичностью, указаной в столбце 'Период' это значение восстанавливается"></span> </span></th>
		<th><span>Лимит<span data-text="Лимит - заданное руководителем Вашей сети количество товара, которое Вы сможете заказать за указанное в соседней колонке 'Период' количество месяцев"></span> </span></th>
		<th><span>Период<span data-text="В этой колонке отображается периодичность в месяцах с которой доступное к заказу лимита, заданного Вашим руководителем"></span></span></th>
	</tr>

@foreach($products as $product)

	<tr>
		<td>{{ $product->model }}</td>
		<td>{{ $product->description->name }}</td>
		<td data-sum>{{ $product->price }}</td>
		<td data-key>{!! Form::number("amounts[$product->product_id]"); !!}</td>
		<td data-available>{{ $product->current_value }}</td>
		<td data-limit>{{ $product->limit }}</td>
		<td>{{ $product->period }}</td>
	</tr>

@endforeach

</table>

@endif


    			{!! Form::submit('Создать заказ', ['class'=>'btn btn-large btn-success mob']); !!}

			{!! Form::close() !!}

	@can('limit_increase_request', $client)

		{!! Form::open(['route' => ['limit_increase', $client->id]]) !!}

	@endcan

<h2 class="center-h1"><span class="help-text">Лимиты и доступные остатки<span data-text="В этой таблице Вы можете видеть лимиты по суммам и количеству товаров, которые установлены для Вашего подразделения администратором сети"></span></span></h2>
			<table style="margin-top:15px;" class="table table-bordered">
				<tr class="first-table-tr">
					<th>Название</th>
					<th><span>Доступый остаток<span data-text="В этой колонке отображается доступное для заказа количество товара. Изначально доступное количество товара равно лимиту, и уменьшается по мере создания Вами заказов. С периодичностью, указаной в столбце 'Период' это значение восстанавливается"></span></span></th>
					<th><span>Лимит<span data-text="Лимит - заданное руководителем Вашей сети количество товара, которое Вы сможете заказать за указанное в соседней колонке 'Период' количество месяцев"></span></span></th>
					<th><span>Период<span data-text="В этой колонке отображается периодичность в месяцах с которой доступное к заказу лимита, заданного Вашим руководителем"></span></span></th>
					@can('limit_increase_request', $client)
					<th><span>Запрос на лимит<span data-text="Если доступный остаток равен нулю, а необходимо заказать товары, Вы можете заполнить значение напротив соответствующих товаров или лимитов и щелкнуть 'Запросить повышение лимитов'. Ваш руководитель получит этот вопрос и сможет увеличить для Вас доступный остаток " ></span></span></th>
					@endcan
				</tr>
				<script type="text/javascript">
						$('.create-orders-info-money-limit').hide();
						$('.create-orders-info-money-limit-info').hide()
				</script>
			@foreach($limits as $limit)

				@if($limit->limitable_type == 'Money')
					<tr>
						<td>Денежный лимит</td>
						<td data-available>{{ $limit->current_value }}</td>
						<td data-limit>{{ $limit->limit }}</td>
						<td data-sum>{{ $limit->period }}</td>
						@can('limit_increase_request', $client)
						<td>{!! Form::number("amounts[$limit->limitable_type]"); !!}</td>
						@endcan
					</tr>
					<script type="text/javascript">
					$('.create-orders-info-money-limit').text((+'{{$limit->current_value}}').toFixed(2)+' грн')
					$('.create-orders-info-money-limit').show();
					$('.create-orders-info-money-limit-info').show()
					</script>
				@endif
			@endforeach
			@foreach($limits as $limit)
			<tr>
				@if($limit->limitable_type == 'App\CostItem')
					<td>{{ $limit->limitable->name }}</td>
					<td data-available>{{ $limit->current_value }}</td>
					<td data-sum>{{ $limit->limit }}</td>
					<td>{{ $limit->period }}</td>
					@can('limit_increase_request', $client)
					<td>{!! Form::number("amounts[".$limit->limitable->name."]"); !!}</td>
					@endcan
				@elseif($limit->limitable_type == 'App\Product')
					<td >{{ $products->where('product_id', $limit->limitable_id)->first()->description->name }}</td>
					<td data-available>{{ $limit->current_value }}</td>
					<td data-limit>{{ $products->where('product_id', $limit->limitable_id)->first()->limit }}</td>
					<td data-sum>{{ $products->where('product_id', $limit->limitable_id)->first()->period }}</td>
					@can('limit_increase_request', $client)
					<td>{!! Form::number("amounts[$limit->limitable_id]"); !!}</td>
					@endcan
				@endif
				</tr>
			@endforeach
			</table>
			@can('limit_increase_request', $client)

			<span class="help-text">{!! Form::submit('Запросить повышение лимита', ['class'=>'btn btn-large btn-primary mob']); !!}<span data-text="Нажмите эту кнопку после заполнения стобца 'Запрос на лимит'"></span></span>


			{!! Form::close() !!}
		</md-card-content>
	</md-card>
</div>
			@endcan

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
						var total = '0';
						for(var i = 0 ; i <  $("[data-key]").length; i++){
								total = +total + ((+($($("[data-key]")[i]).parent().find("[data-sum]").text())) * +$($("[data-key]")[i]).find('input').val() );
						}
						$('.create-orders-info-money-total').text(total.toFixed(2)+'грн')
					},1)
	    });
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
			</script>

@endsection
