@extends('layouts.master')
@section('content')
<md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="right">
      <md-toolbar class="md-theme-light">
        <h1 class="md-toolbar-tools">Фильрация</h1>
      </md-toolbar>
      <md-content ng-controller="RightCtrl" layout-padding="10">
        {!! Form::open(['route' => 'user_orders']) !!}
				 <md-card-content flex-gt-md="100">
           <!-- start select -->
					<md-input-container>
					<label>Фильтрация по статусу заказа</label>
					<md-select  ng-change="ChangeChechbox()" ng-model="selectedVegetables"
									 md-on-close="clearSearchTerm()"
									 data-md-container-class="selectdemoSelectHeader"
									 multiple>
					<md-optgroup label="Фильтрация по статусу заказа">
	          @foreach($statuses as $status)
						<md-option ng-value="'{{$status->name}}'">{{$status->name}}</md-option>
						@endforeach
					</md-optgroup>
					</md-select>
					</md-input-container>
          <!-- end select -->
          <!-- start select -->
					<md-input-container>
					<label> Фильтрация по подразделениям</label>
					<md-select  ng-change="ChangeChechbox()" ng-model="selectedVegetables"
									 md-on-close="clearSearchTerm()"
									 data-md-container-class="selectdemoSelectHeader"
									 multiple>
					<md-optgroup label=" Фильтрация по подразделениям">
	          @foreach($clients as $id => $name)
						<md-option ng-value="'{{$name}}'">{{$name}}</md-option>
						@endforeach
					</md-optgroup>
					</md-select>
					</md-input-container>
          <!-- end select -->
         	<!-- start date -->
			<label>Фильтр по дате создания заказа</label></br>
	        C
	        <md-datepicker  ng-model="myDate" md-current-view="year" md-placeholder="Enter date"></md-datepicker>
	        {!! Form::date('created_from', session()->get("filters.created_from"), ['ng-value' => 'created_from', 'class'=>'hidden'] ); !!}
	        по
	        <md-datepicker  ng-model="myDate" md-current-view="year" md-placeholder="Enter date"></md-datepicker>
	        {!! Form::date('expected_delivery_from', session()->get("filters.expected_delivery_from"), ['ng-value' => 'created_from', 'class'=>'hidden'] ); !!}
	        <!-- end start -->

			<!-- start date -->
			@if(!Auth::user()->isConsumer())
			<label>Фильтр по ожидаемой дате доставки</label></br>
	        C
	        <md-datepicker  ng-model="myDate" md-current-view="year" md-placeholder="Enter date"></md-datepicker>
	        {!! Form::date('created_from', session()->get("filters.created_from"), ['ng-value' => 'created_from', 'class'=>'hidden'] ); !!}
	        по
	        <md-datepicker  ng-model="myDate" md-current-view="year" md-placeholder="Enter date"></md-datepicker>
	        {!! Form::date('expected_delivery_from', session()->get("filters.expected_delivery_from"), ['ng-value' => 'created_from', 'class'=>'hidden'] ); !!}
	        @endif
	        <!-- end start -->
			<!-- start date -->
			@if(!Auth::user()->isConsumer())
			<label>Фильтр по дате получения заказа</label></br>
	        C
	        <md-datepicker  ng-model="myDate" md-current-view="year" md-placeholder="Enter date"></md-datepicker>
	        {!! Form::date('created_from', session()->get("filters.created_from"), ['ng-value' => 'created_from', 'class'=>'hidden'] ); !!}
	        по
	        <md-datepicker  ng-model="myDate" md-current-view="year" md-placeholder="Enter date"></md-datepicker>
	        {!! Form::date('expected_delivery_from', session()->get("filters.expected_delivery_from"), ['ng-value' => 'created_from', 'class'=>'hidden'] ); !!}
	        @endif
	        <!-- end start -->
			</md-card-content>
        <md-button flex-gt-md="100" ng-click="close()" class="md-primary">
          Закрыть
        </md-button>
          <span ng-init="defaultStatusOrder = []"> </span>
        @foreach($statuses as $status)
        <span ng-init="defaultStatusOrder.push('{{$status->name}}')" class="hidden"></span>
    		{!! Form::checkbox("statuses[$status->id]", $status->name, session()->has("filters.statuses.$status->id"), ['id' => "$status->name", 'class'=>'hidden']); !!}
    		@endforeach
        <span ng-init="defaultStatusOrder = []"> </span>
        @foreach($clients as $id => $name)
          <span ng-init="defaultClientOrder.push('{{$status->name}}')" class="hidden"></span>
    		     {!! Form::checkbox("clients[$id]", $id, session()->has("filters.clients.$id"), ['id' => "clients[$id]", 'class'=>'hidden']);!!}
    		@endforeach
        @if(isset($client))
        	{!! Form::hidden('clients[]', $client->id); !!}
        @endif
        <md-button class="md-primary md-raised" type="button" ng-click="changedate()">
            Фильтровать
        </md-button>
        {!! Form::close() !!}
      </md-content>
</md-sidenav>
<div ng-controller="orders" class="main-body ng-scope flex" data-ui-view="" data-flex="">
	<md-card class="md-table ng-scope _md">
    <md-card-content>
			<div class="md-table-loader" data-ng-if="!loaded">
            <md-progress-circular md-mode="indeterminate"></md-progress-circular>
        </div>
				<section class="md-table-header">
            <div class="md-table-header-title">
							<span ng-click="toggleRight()" >Фильтры</span>
							<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
									<md-icon class="md-ic">&#xE887;</md-icon>
									<md-tooltip>
										В таблице будут показаны заказы, которые соответствуют фильтрам и параметрам, которые Вы установите в полях ниже
									</md-tooltip>
							</md-button>
						</div>
        </section>
				<section class="md-table-body">
<style>
	.button-style ~ input:hover {
		background-color: #ff9e01;
	}
	.button-style ~ input {
		transition: all 0.2s;
		color: #fff;
		border: none;
		border-radius: 5px;
		padding: 5px 50px;
		background-color: #4197e2;
	}
</style>
@if($orders->IsNotEmpty())

	{!! Form::open(['route' => 'status']) !!}
	<style>
		.table tbody tr a{
			color: #000;
		}
	</style>
	<div style="overflow: auto; font-size: 12px;margin-bottom: 10px;">
		<h1 class="center-h1">Заказы</h1>
		<section class="md-table-body">
	<table  class="table table-bordered button-style order-table">
		<!-- <colgroup></colgroup>
	       <colgroup class="slim"></colgroup>
	       <colgroup class="slim"></colgroup>
	       <colgroup class="slim"></colgroup>
	       <colgroup class="slim"></colgroup>
				 <colgroup class="slim"></colgroup>
				 <colgroup class="slim"></colgroup>
				 <colgroup class="slim"></colgroup>
	       <colgroup class="slim"></colgroup>
				 <colgroup class="slim"></colgroup> -->
		<thead>
			<tr class="first-table-tr">
				<th>Подразделение</th>
				<th>Пользователь</th>
				<th>Сумма</th>
				<th style="min-width: 280px;">Статус заказа</th>
				<th><span>Статус подтверждения<span data-text="Заказ подтвержден менеджером"></span></span></th>
				@if(Auth::user()->isClientAdmin())

					<th style="width: 200px;"><span>Подтверждение потребителем<span data-text="Подтверждения заказа потребителем"></span></span></th>
					<th style="width: 200px;"><span>Подтверждение администратором<span data-text="Подтверждения заказа администратором"></span></span></th>

				@elseif(Auth::user()->isManager())
					<th><span>Подтверждение менеджером<span data-text="Подтверждение заказа менеджером"></span></span></th>
				@elseif(Auth::user()->isConsumer())
					<th><span>Подтверждение потребителем<span data-text="Подтвердить заказ как потребитель"></span></span></th>
				@elseif(Auth::user()->isSublevel())
					<th><span>Подтверждение потребителем<span data-text="Подтвердить заказ как потребитель"></span></span></th>
					<th><span>Подтверждение руководителем подуровня<span data-text="Подтвердить заказ как руководитель подуровня"></span></span></th>
				@endif
				<th><span>Создание заказа<span data-text="Дата/время создания заказа"></span></span></th>
				<th><span>Ожидаемая дата<span data-text="Ожидаемая дата/время прибытие заказа"></span></span></th>
				<th><span>Дата доставки<span data-text="Фактическая дата/время прибытия заказа"></span></span></th>
			</tr>
		</thead>
		<tbody>
		@if(!Auth::user()->isCompanyAdmin())

			{!! Form::open(['route' => 'status']) !!}

		@endif
        @foreach($orders as $order)

			@include('orders.order')

        @endforeach
</tbody>
    </table>
		</section>
	</div>

		@if(!Auth::user()->isCompanyAdmin())

			{!! Form::submit('Установить статусы', ['class' => 'btn btn-large btn-success mob']); !!}


			{!! Form::close() !!}

	<div class="pagination-center">
	{{ $orders->links() }}
	</div>

		@endif


@endif

@if(isset($client))

		@can('create_order', $client)
		<a class="btn btn-large btn-primary mob" href="/clients/{{ $client->id }}/orders/create">Создать</a></br>
	    @endcan

@endif

<script>
    $.ajax({
        url : '/js/ru.json',
        type: "GET",
        success: function (data) {
						for(let i = 0 ; i < $('tr select').length; i++){
							for(let q = 0; q < $($('tr select')[i]).find('option').length; q++){
									$($($('tr select')[i]).find('option')[q]).text(data[$($($('tr select')[i]).find('option')[q]).text()])
							}
						}
						for(let i = 0 ; i < $("[data-id]").length; i++){
							$($("[data-id]")[i]).text(data[$($("[data-id]")[i]).text()])
						}
						for(let i = 0 ; i < $("[data-sum]").length; i++){
								$($("[data-sum]")[i]).text((+$($("[data-sum]")[i]).text()).toFixed(2))
						}
        }
    })
</script>
</section>
</md-card-content>
</md-card>
</div>
@endsection
