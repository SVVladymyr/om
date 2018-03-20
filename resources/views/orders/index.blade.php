
@extends('layouts.master')
@section('content')
<md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="right">
      <md-toolbar class="md-theme-light">
        <h1 class="md-toolbar-tools">Фильрация</h1>
      </md-toolbar>
      <md-content ng-controller="RightCtrl" layout-padding="10">
        {!! Form::open(['route' => 'user_orders']) !!}
				 <md-card-content flex-gt-md="100">

         	<!-- start date -->
			<label>Фильтр по дате создания заказа</label></br>
      С
	        <md-datepicker  ng-model="createdFrom" md-current-view="year" md-placeholder="Введите дату"></md-datepicker>
	        {!! Form::date('created_from', session()->get("filters.created_from"), ['ng-value' => 'created_from', 'class'=>'hidden'] ); !!}
	        по
	        <md-datepicker  ng-model="expectedDeliveryFrom" md-current-view="year" md-placeholder="Введите дату"></md-datepicker>
	        {!! Form::date('expected_delivery_from', session()->get("filters.expected_delivery_from"), ['ng-value' => 'expected_delivery_from', 'class'=>'hidden'] ); !!}
	      <!-- end start -->


           <!-- start select -->
					<md-input-container>
					<label>Фильтрация по статусу заказа</label>
					<md-select  ng-change="ChangeChechbox()" ng-model="selectedVegetables"
									 md-on-close="clearSearchTerm()"
									 data-md-container-class="selectdemoSelectHeader"
									 multiple>
					<md-optgroup label="Фильтрация по статусу заказа">
	          @foreach($statuses as $status)

            <!-- {{ $status->name == 'new' ? $name["new"] = 'Новые' : $status->name == 'client_admin_confirm' ? $name["client_admin_confirm"] = 'Подтвержден администратором клиента' : $status->name == 'manager_comfirm' ? $name["manager_comfirm"] = 'Подтвержден менеджером ' : $status->name == 'delivered' ? $name["delivered"] = 'Доставлено'  : null }} -->
            <md-option  ng-selected="defaultStatusOrderOnload['{{$status->name}}']" ng-value="'{{$status->name}}'">{{$name[$status->name]}}</md-option>

            @endforeach
					</md-optgroup>
					</md-select>
					</md-input-container>
          <!-- end select -->
          <!-- start select -->
					<md-input-container>
					<label> Фильтрация по подразделениям</label>
					<md-select  ng-change="ChangeChechbox()" ng-model="selectedVegetablesClient"
									 md-on-close="clearSearchTerm()"
									 data-md-container-class="selectdemoSelectHeader"
									 multiple>
					<md-optgroup label=" Фильтрация по подразделениям">
	          @foreach($clients as $id => $name)
						<md-option  ng-selected="defaultClientOrderOnload['{{$name}}']" ng-value="'{{$name}}'">{{$name}}</md-option>
						@endforeach
					</md-optgroup>
					</md-select>
					</md-input-container>
          <!-- end select -->

			<!-- start date -->
			@if(!Auth::user()->isConsumer())
			<label>Фильтр по ожидаемой дате доставки</label></br>
С
	        <md-datepicker  ng-model="createdToDel" md-current-view="year" md-placeholder="Введите дату"></md-datepicker>
	        {!! Form::date('created_to', session()->get("filters.created_to"), ['ng-value' => 'created_toDel', 'class'=>'hidden'] ); !!}
	        по
	        <md-datepicker  ng-model="expectedDeliveryToDel" md-current-view="year" md-placeholder="Введите дату"></md-datepicker>
	        {!! Form::date('expected_delivery_to', session()->get("filters.expected_delivery_to"), ['ng-value' => 'expected_delivery_toDel', 'class'=>'hidden'] ); !!}
  @endif
	        <!-- end start -->
			<!-- start date -->
			@if(!Auth::user()->isConsumer())
			<label>Фильтр по дате получения заказа</label></br>
      С
	        <md-datepicker  ng-model="createdToOrd" md-current-view="year" md-placeholder="Введите дату"></md-datepicker>
	        {!! Form::date('created_to', session()->get("filters.created_to"), ['ng-value' => 'created_toOrd', 'class'=>'hidden'] ); !!}
	        по
	        <md-datepicker  ng-model="expectedDeliveryToOrd" md-current-view="year" md-placeholder="Введите дату"></md-datepicker>
	        {!! Form::date('expected_delivery_to', session()->get("filters.expected_delivery_to"), ['ng-value' => 'expected_delivery_toOrd', 'class'=>'hidden'] ); !!}
	        @endif
	        <!-- end start -->
			</md-card-content>
          <span ng-init="defaultStatusOrder = []"> </span>
        @foreach($statuses as $status)
        <span ng-init="defaultStatusOrder.push('{{$status->name}}')" class="hidden"></span>
    		{!! Form::checkbox("statuses[$status->id]", $status->name, session()->has("filters.statuses.$status->id"), ['id' => "$status->name", 'class'=>'hidden']); !!}
    		@endforeach
        <span ng-init="defaultClientOrder = []"> </span>
        @foreach($clients as $id => $name)
          <span ng-init="defaultClientOrder.push('{{$name}}')" class="hidden"></span>
    		     {!! Form::checkbox("clients[$id]", $id, session()->has("filters.clients.$id"), ['id' => "$name", 'class'=>'hidden']);!!}
    		@endforeach
        <span ng-init="defaultClientOrderOnload()"> </span>
        @if(isset($client))
        	{!! Form::hidden('clients[]', $client->id); !!}
        @endif
        <md-button class="md-primary md-raised" type="submit" ng-click="changedate()">
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
			<tr class="first-table-tr order-table">
				<th>Подразделение</th>
				<th>Пользователь</th>
				<th>Сумма</th>
				<th style="min-width: 190px;">Статус заказа</th>
				<th style="min-width: 210px;">
          <span>Статус подтверждения</span>
          <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
					<md-icon class="md-ic">&#xE887;</md-icon>
					<md-tooltip>
						Заказ подтвержден менеджером
					</md-tooltip>
				</md-button>
      </th>
				@if(Auth::user()->isClientAdmin())

					<th style="min-width: 260px;">
            <span>Подтверждение потребителем</span><md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
					<md-icon class="md-ic">&#xE887;</md-icon>
					<md-tooltip>
						Подтверждения заказа потребителем
					</md-tooltip>
				</md-button></th>
					<th style="min-width: 280px;">
            <span>Подтверждение администратором</span>
            <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
			        <md-icon class="md-ic">&#xE887;</md-icon>
    					<md-tooltip>
    						Подтверждения заказа администратором
    					</md-tooltip>
				     </md-button>
        </th>

				@elseif(Auth::user()->isManager())
					<th style="min-width: 280px;" >
            <span>Подтверждение менеджером</span>
            <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
			        <md-icon class="md-ic">&#xE887;</md-icon>
    					<md-tooltip>
    						Подтверждение заказа менеджером
    					</md-tooltip>
				     </md-button>
          </th>
				@elseif(Auth::user()->isConsumer())
					<th style="min-width: 280px;" ><span>Подтверждение потребителем</span>
            <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
              <md-icon class="md-ic">&#xE887;</md-icon>
              <md-tooltip>
                Подтвердить заказ как потребитель
              </md-tooltip>
             </md-button>
          </th>
				@elseif(Auth::user()->isSublevel())
					<th style="min-width: 280px;"><span>Подтверждение потребителем </span>
            <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
              <md-icon class="md-ic">&#xE887;</md-icon>
              <md-tooltip>
                Подтвердить заказ как потребитель
              </md-tooltip>
             </md-button>
          </th>
					<th style="min-width: 280px;"><span>Подтверждение руководителем подуровня</span>
            <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
              <md-icon class="md-ic">&#xE887;</md-icon>
              <md-tooltip>
                Подтвердить заказ как руководитель подуровня
              </md-tooltip>
             </md-button>
          </th>
				@endif
				<th style="min-width: 170px;"><span>Создание заказа</span><md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
					<md-icon class="md-ic">&#xE887;</md-icon>
					<md-tooltip>
						Дата/время создания заказа
					</md-tooltip>
				</md-button></th>
				<th style="min-width: 170px;"><span>Ожидаемая дата</span>
				<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
					<md-icon class="md-ic">&#xE887;</md-icon>
					<md-tooltip>
						Ожидаемая дата/время прибытие заказа
					</md-tooltip>
				</md-button></th>
				<th style="min-width: 170px;"><span>Дата доставки</span>
				<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
					<md-icon class="md-ic">&#xE887;</md-icon>
					<md-tooltip>
						Фактическая дата/время прибытия заказа
					</md-tooltip>
				</md-button></th>
			</tr>
		</thead>
		<tbody>
		@if(!Auth::user()->isCompanyAdmin())

			{!! Form::open(['route' => 'status']) !!}

		@endif
		{{--{{dump($orders)}}--}}
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
