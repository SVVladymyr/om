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

@if(session('message'))
			<div class="alert alert-info">
				{{session('message')}}
			</div>
@endif
@if(count($products))
<table class="table table-bordered">
	<tr class="first-table-tr">
		<th>Артикул</th>
		<th>Название</th>
		<th>Цена</th>
		<th><span>Количество
				<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
									<md-icon class="md-ic">&#xE887;</md-icon>
									<md-tooltip>

				Укажите количество, которое хотите заказать. После того как укажите количество по всем товарам - щелкните кнопку 'Создать товар'
									</md-tooltip>
							</md-button>
			</span></th>
		<th><span>Доступно
				<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
									<md-icon class="md-ic">&#xE887;</md-icon>
									<md-tooltip>
				В этой колонке отображается доступное для заказа количество товара.

									</md-tooltip>
							</md-button>
			</span></th>
		<th><span>Лимит
				<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
									<md-icon class="md-ic">&#xE887;</md-icon>
									<md-tooltip>

				Лимит - заданное руководителем Вашей сети количество товара, которое Вы сможете заказать за указанное в соседней колонке 'Период' количество месяцев
									</md-tooltip>
							</md-button>
			</span></th>
		<th><span>Период
				<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
									<md-icon class="md-ic">&#xE887;</md-icon>
									<md-tooltip>

				В этой колонке отображается периодичность в месяцах с которой доступное к заказу лимита, заданного Вашим руководителе
									</md-tooltip>
							</md-button>
			</span></th>
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

		<section class="md-table-header">
			<div class="md-table-header-title">
				<span >Лимиты и доступные остатки</span>
				<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
					<md-icon class="md-ic">&#xE887;</md-icon>
					<md-tooltip>
						В этой таблице Вы можете видеть лимиты по суммам и количеству товаров, которые установлены для Вашего подразделения администратором сети
					</md-tooltip>
				</md-button>
			</div>
		</section>
			<table style="margin-top:15px;" class="table table-bordered">
				<tr class="first-table-tr">
					<th>Название</th>
					<th><span>Доступый остаток
							<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
									<md-icon class="md-ic">&#xE887;</md-icon>
									<md-tooltip>
							В этой колонке отображается доступное для заказа количество товара.
									</md-tooltip>
							</md-button>
						</span></th>
					<th><span>Лимит
							<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
									<md-icon class="md-ic">&#xE887;</md-icon>
									<md-tooltip>
							Лимит - заданное руководителем Вашей сети количество товара, которое Вы сможете заказать за указанное в соседней колонке 'Период' количество месяцев
									</md-tooltip>
							</md-button>
						</span></th>
					<th><span>Период
							<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
									<md-icon class="md-ic">&#xE887;</md-icon>
									<md-tooltip>
							В этой колонке отображается периодичность в месяцах с которой доступное к заказу лимита, заданного Вашим руководителем
									</md-tooltip>
							</md-button>
						</span></th>
					@can('limit_increase_request', $client)
					<th><span>Запрос на лимит
							<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
									<md-icon class="md-ic">&#xE887;</md-icon>
									<md-tooltip>
							Если доступный остаток равен нулю, а необходимо заказать товары, Вы можете заполнить значение напротив соответствующих товаров или лимитов и щелкнуть 'Запросить повышение лимитов'.
									</md-tooltip>
							</md-button>
						</span></th>
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

			<span class="help-text">{!! Form::submit('Запросить повышение лимита', ['class'=>'btn btn-large btn-primary mob']); !!}
				<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
					<md-icon class="md-ic">&#xE887;</md-icon>
					<md-tooltip>
						Нажмите эту кнопку после заполнения стобца 'Запрос на лимит'
					</md-tooltip>
				</md-button>
			</span>


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
