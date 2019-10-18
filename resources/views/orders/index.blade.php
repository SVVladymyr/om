@extends('layouts.master')
@section('content')
	<md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="right">

      <md-toolbar class="md-theme-light">
        <h1 class="md-toolbar-tools">Фильрация</h1>
      </md-toolbar>
      <md-content ng-controller="RightCtrl" layout-padding="10">
        {{--{!! Form::open(['route' => 'user_orders']) !!}--}}
        {!! Form::open(['url' => '/orders','method'=>'get', 'class' => 'filter-form']) !!}



		  <!-- state date -->



			<!--end-->
				 <md-card-content flex-gt-md="100">

         	<!-- start date -->
			<label>Фильтр по дате создания заказа</label></br>

      С
	        <md-datepicker  ng-model="createdFrom" md-current-view="year" md-placeholder="Введите дату"></md-datepicker>
	        {!! Form::date('created_from', session()->get("filters.created_from"), ['ng-value' => 'created_from', 'class'=>'hidden'] )!!}
	        по
	        <md-datepicker  ng-model="createdToOrd" md-current-view="year" md-placeholder="Введите дату"></md-datepicker>
	        {!! Form::date('created_to', session()->get("filters.created_to"), ['ng-value' => 'created_toOrd', 'class'=>'hidden'] ); !!}
	      <!-- end start -->
					 @if(!empty(session()->get("filters.created_from"))||!empty(session()->get("filters.expected_delivery_from")))
						 @if(!empty(session()->get("filters.created_from")))
							 <input type="text" ng-init='dateState("created_from", "{{session()->get("filters.created_from")}}")' hidden>
						 @endif
						 @if(!empty(session()->get("filters.expected_delivery_from")))
								 <input type="text" ng-init='dateState("expected_delivery_from", "{{session()->get("filters.expected_delivery_from")}}")' hidden>

						 @endif
					 @endif
					 @if(!empty(session()->get("filters.created_to"))||!empty(session()->get('filters.expected_delivery_to')))
						 @if(!empty(session()->get("filters.created_to")))
							 <input type="text" ng-init='dateState("created_to", "{{session()->get("filters.created_to")}}")' hidden>

						 @endif
						 @if(!empty(session()->get('filters.expected_delivery_to')))
							 <input type="text" ng-init='dateState("expected_delivery_to", "{{session()->get('filters.expected_delivery_to')}}")' hidden>

						 @endif
					 @endif
           <!-- start select -->
					<md-input-container>
					<label>Фильтрация по статусу заказа</label>
					<md-select  ng-change="ChangeChechbox()" ng-model="selectedVegetables"
									 md-on-close="clearSearchTerm()"
									 data-md-container-class="selectdemoSelectHeader"
									 multiple>
					<md-optgroup label="Фильтрация по статусу заказа">

						@foreach($statuses as $status)
						<!-- {{ $status->name == 'requires_confirmation' ? $name["requires_confirmation"] = 'Ждет подтверждение' : $status->name == 'client_admin_confirm' ? $name["client_admin_confirm"] = 'Подтвержден администратором клиента' : $status->name == 'manager_comfirm' ? $name["manager_comfirm"] = 'Подтвержден менеджером ' : $status->name == 'delivered' ? $name["delivered"] = 'Доставлено'  : $status->name == 'cancelled' ? $name["cancelled"] = 'Отменено'  : null }} -->
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
						<md-select-header class="demo-select-header">
							<input ng-model="searchTerm"
								   type="search"
								   placeholder="Поиск по подразделениям"
								   class="demo-header-searchbox md-text">
						</md-select-header>
						@foreach($clients as $id => $name)
							<span ng-init="clients.push('{{$name}}')"></span>
						@endforeach
				  		<md-option ng-repeat="client in clients | filter:searchTerm"  ng-selected="defaultClientOrderOnload['@{{client}}']"  ng-value="client">@{{client}}</md-option>

					</md-select>
					</md-input-container>
          	<!-- end select -->
			<!-- start select -->
			@if(Auth::user()->isManager())
				<md-input-container class="layout-row flex-66">
					<label>Фильтрация по корневому агенту</label>
					<md-select ng-model="root" placeholder="Фильтрация по корневому агенту">
						<md-option ng-repeat="item in {{ $root }}" ng-value="item.id" ng-selected=" item.id == {{ session()->get('filters.root_id') }}">@{{ item.name }}</md-option>
					</md-select>
				</md-input-container>
			@endif
          	<!-- end select -->
			  
			<!-- start date -->
			{{--@if(!Auth::user()->isConsumer())--}}
			{{--<label>Фильтр по ожидаемой дате доставки</label></br>--}}

{{--С--}}
	        {{--<md-datepicker  ng-model="createdToDel" md-current-view="year" md-placeholder="Введите дату"></md-datepicker>--}}
	        {{--{!! Form::date('created_to', session()->get("filters.created_to"), ['ng-value' => 'created_toDel', 'class'=>'hidden'] ); !!}--}}
	        {{--по--}}
	        {{--<md-datepicker  ng-model="expectedDeliveryToDel" md-current-view="year" md-placeholder="Введите дату"></md-datepicker>--}}
	        {{--{!! Form::date('expected_delivery_to', session()->get("filters.expected_delivery_to"), ['ng-value' => 'expected_delivery_toDel', 'class'=>'hidden'] ); !!}--}}
  {{--@endif--}}
	        <!-- end start -->
			<!-- start date -->
			@if(!Auth::user()->isConsumer())
			<label>Фильтр по дате получения заказа</label></br>
      С
      		<md-datepicker  ng-model="expectedDeliveryFrom" md-current-view="year" md-placeholder="Введите дату"></md-datepicker>
	        {!! Form::date('expected_delivery_from', session()->get("filters.expected_delivery_from"), ['ng-value' => 'expected_delivery_from', 'class'=>'hidden'] ) !!}
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

		<input type="hidden" ng-init='{{ session()->get("filters.root_id") }}' name="root_id" id="root" ng-value="root">


        <md-button class="md-primary md-raised" type="submit" ng-click="changedate();">
            Фильтровать
        </md-button>
		<md-button class="md-primary md-raised" type="reset" ng-click="clearFilter()">
            Очистить
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
							<span ng-click="toggleRight()" >Фильтр</span>
							<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" ng-click="toggleRight()">
									<md-icon class="md-ic">&#xE152;</md-icon>
									<md-tooltip>
										Открыть фильтра
									</md-tooltip>
							</md-button>
							<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
												<md-icon class="md-ic">&#xE887;</md-icon>
												<md-tooltip>
													В таблице будут показаны заказы, которые соответствуют фильтрам и параметрам, которые Вы установите в полях ниже
												</md-tooltip>
										</md-button>

						</div>
        </section>
				<section class="md-table-body">
					@if(session()->get("filters"))
					<p>Активные фильтра</p>
					@endif
					<ul class="list-inline">
						@if(!empty(session()->get("filters.created_from"))||!empty(session()->get("filters.created_to")))
							<p>Фильтр по дате создания заказа</p>
							@if(!empty(session()->get("filters.created_from")))
								<span>С:</span><li class="list-inline-item">{{session()->get("filters.created_from")}}</li>
								<input type="text" ng-init='dateState("created_from", "{{session()->get("filters.created_from")}}")' hidden>
							@endif
							@if(!empty(session()->get("filters.created_to")))
								<span>По:</span><li class="list-inline-item">{{session()->get("filters.created_to")}}</li>
							@endif
						@endif
						@if(!empty(session()->get("filters.statuses")))
							<p>Выбранные статусы</p>
							@foreach(session()->get("filters.statuses") as $status)
								@if($status=='requires_confirmation')
									<span class="status-wrap">- Ждет подтверждение</span>
								@elseif($status=='client_admin_confirm')
									<span class="status-wrap">- Подтвержден администратором клиента</span>
								@elseif($status=='client_admin_confirm')
									<span class="status-wrap">- Подтвержден администратором клиента</span>
								@elseif($status=='manager_comfirm')
									<span class="status-wrap">- Подтвержден менеджером</span>
								@elseif($status=='delivered')
									<span class="status-wrap">- Доставлено</span>
								@elseif($status=='cancelled')
									<span class="status-wrap">- Отменено</span>
								@endif
							@endforeach
						@endif
						@if(!empty(session()->get("filters.clients")))
							<p>Выбранные клиенты</p>
								@foreach(session()->get("filters.clients") as $id )
									<span class="status-wrap">- {{$clients[$id]}}</span>
								@endforeach
						@endif
						@if(!empty(session()->get("filters.root_id")))
							<p>Выбранный корневой агент</p>
							<span class="status-wrap">- {{ $root_id[session()->get("filters.root_id")] }}</span>
						@endif
						@if(!empty(session()->get("filters.expected_delivery_to"))||!empty(session()->get("filters.expected_delivery_from")))
							<p>Фильтр по дате получения заказа</p>
							@if(!empty(session()->get("filters.expected_delivery_to")))
								<span>С:</span><li class="list-inline-item">{{session()->get("filters.expected_delivery_to")}}</li>
							@endif
							@if(!empty(session()->get('filters.expected_delivery_from')))
								<span>По:</span><li class="list-inline-item">{{session()->get('filters.expected_delivery_from')}}</li>
							@endif
						@endif
					</ul>

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
			@if ( session()->get("filters.select-all") == true )
				<th><input name="select-all" type="checkbox" checked></th>
			@else
				<th><input name="select-all" type="checkbox"></th>
			@endif
				<th>Подразделение</th>
				<th>Пользователь</th>
				@if(Auth::user()->isManager())
					<th>Корневой контрагент</th>
				@endif
				<th>Сумма</th>
				<th style="min-width: 190px;">Статус заказа</th>
				<th style="min-width: 120px;">
          <span>Статус подтверждения</span>
          <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
					<md-icon class="md-ic">&#xE887;</md-icon>
					<md-tooltip>
						Заказ подтвержден менеджером
					</md-tooltip>
				</md-button>
      </th>
				@if(Auth::user()->isClientAdmin())

					<th style="min-width: 120px;">
            <span>Подтверждение потребителем</span><md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
					<md-icon class="md-ic">&#xE887;</md-icon>
					<md-tooltip>
						Подтверждения заказа потребителем
					</md-tooltip>
				</md-button></th>
					<th style="min-width: 120px;">
            <span>Подтверждение администратором</span>
            <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
			        <md-icon class="md-ic">&#xE887;</md-icon>
    					<md-tooltip>
    						Подтверждения заказа администратором
    					</md-tooltip>
				     </md-button>
        </th>

				@elseif(Auth::user()->isManager())
					<th style="min-width: 120px;" >
            <span>Подтверждение менеджером</span>
            <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
			        <md-icon class="md-ic">&#xE887;</md-icon>
    					<md-tooltip>
    						Подтверждение заказа менеджером
    					</md-tooltip>
				     </md-button>
          </th>
				@elseif(Auth::user()->isConsumer())
					<th style="min-width: 120px;" ><span>Подтверждение потребителем</span>
            <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
              <md-icon class="md-ic">&#xE887;</md-icon>
              <md-tooltip>
                Подтвердить заказ как потребитель
              </md-tooltip>
             </md-button>
          </th>
				@elseif(Auth::user()->isSublevel())
					<th style="min-width: 120px;"><span>Подтверждение потребителем </span>
            <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
              <md-icon class="md-ic">&#xE887;</md-icon>
              <md-tooltip>
                Подтвердить заказ как потребитель
              </md-tooltip>
             </md-button>
          </th>
					<th style="min-width: 120px;"><span>Подтверждение руководителем подуровня</span>
            <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
              <md-icon class="md-ic">&#xE887;</md-icon>
              <md-tooltip>
                Подтвердить заказ как руководитель подуровня
              </md-tooltip>
             </md-button>
          </th>
				@endif
				<th style="min-width: 120px;"><span>Создание заказа</span><md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
					<md-icon class="md-ic">&#xE887;</md-icon>
					<md-tooltip>
						Дата/время создания заказа
					</md-tooltip>
				</md-button></th>
				<th style="min-width: 120px;"><span>Ожидаемая дата</span>
				<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
					<md-icon class="md-ic">&#xE887;</md-icon>
					<md-tooltip>
						Ожидаемая дата/время прибытие заказа
					</md-tooltip>
				</md-button></th>
				<th style="min-width: 100px;"><span>Дата доставки</span>
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
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			@if(Auth::user()->isClientAdmin())
				<td>{!! Form::select("active-select", ['' => 'Не выбрано', '4' => 'Delivered', '3' => 'Waiting']); !!}</td>
				<td>{!! Form::select("active-select-1", ['' => 'Не выбрано', '2' => 'Confirmed', '1' => 'Ждет подтверждение','5'=>'Отменено']); !!}</td>
			@elseif(Auth::user()->isManager())
				<td></td>
				<td>{!! Form::select("active-select", ['' => 'Не выбрано', '3' => 'Confirmed', '2' => 'Ждет подтверждение','5'=>'Отменено']); !!}</td>
			@elseif(Auth::user()->isConsumer())
				<td>{!! Form::select("active-select", ['' => 'Не выбрано', '4' => 'Delivered', '3' => 'Waiting']); !!}</td>
			@elseif(Auth::user()->isSublevel())
				<td>{!! Form::select("active-select", ['' => 'Не выбрано', '4' => 'Delivered', '3' => 'Waiting']); !!}</td>
				<td>{!! Form::select("active-select-1", ['' => 'Не выбрано', 	'true' => 'Confirmed', 'false' => 'Ждет подтверждение']); !!}</td>
			@else
				<td></td>
				<td></td>
			@endif
			<td></td>
			<td></td>
			<td></td>
		</tr>
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
	{{ $orders->appends($_GET)->links() }}
	</div>

		@endif


@endif

@if(isset($client))

		@can('create_order', $client)
		<a class="btn btn-large btn-primary mob" href="/clients/{{ $client->id }}/orders/create">Создать</a></br>
	    @endcan

@endif

<script defer>
		$( document ).ready(function() {
			$('[name="select-all"]').click(function () {
	        const statut = $(this).prop("checked");
	        if(statut == true){
	            $('[name="active-select"]').each(function () {
	                $(this).removeAttr('disabled');
				});
				$('[name="active-select-1"]').each(function () {
	                $(this).removeAttr('disabled');
				});
				sessionStorage.setItem("select-all", true);
			} else {
	            $('[name="active-select"]').each(function () {
	                $(this).attr('disabled',"disabled");
				});
				$('[name="active-select-1"]').each(function () {
	                $(this).attr('disabled',"disabled");
				});
				sessionStorage.setItem("select-all", false);
				sessionStorage.setItem("active-select", false);
				sessionStorage.setItem("active-select-1", false);
			}
	        $('[name="select-items"]').each(function () {
				$(this).prop("checked", statut)
	        });
	    })
		$('[name="select-items"]').click(function () {
			$('[name="select-items"]').each(function () {
				if($(this).prop("checked")){
	                $('[name="active-select"]').each(function () {
	                    $(this).removeAttr('disabled');
					});
					$('[name="active-select-1"]').each(function () {
	                    $(this).removeAttr('disabled');
	                })
				    return false;
				} else {
	                $('[name="active-select"]').each(function () {
	                    $(this).attr('disabled',"disabled");
					});
					$('[name="active-select-1"]').each(function () {
	                    $(this).attr('disabled',"disabled");
					});
				}
	        });
	    })
	    $('[name="active-select"]').each(function () {
	        $(this).attr('disabled',"disabled");
		});
		$('[name="active-select-1"]').each(function () {
	        $(this).attr('disabled',"disabled");
	    })
		$('[name="active-select"]').change(function () {
			sessionStorage.setItem("active-select", $(this).val());
		    const val =  $(this).val();
		    const $set = $(this).parent().parent().find('td');
			const n = $set.index($(this).parent());
			console.log("Tuta n= " + n);
			$('tbody tr').each(function (i) {
			    let _this = this;
			    if($('tbody tr').length-1 != i){
			        $(this).find(' > td').each(function (q) {
			        	console.log($(_this).find('>td:nth-child(1) > input').prop("name") + ":" + $(_this).find('>td:nth-child(1) > input').prop("checked"));
			            if($(_this).find('>td:nth-child(1) > input').prop("checked")){
							if(q == n){
								$(this).find('select').val(val)
							}
						}
	                })
				}
	        })
	    })
		$('[name="active-select-1"]').change(function () {
			sessionStorage.setItem("active-select-1", $(this).val());
		    const val =  $(this).val();
		    const $set = $(this).parent().parent().find('td');
		    const n = $set.index($(this).parent());
			
			$('tbody tr').each(function (i) {
			    let _this = this;
			    if($('tbody tr').length-1 != i){
			        $(this).find(' > td').each(function (q) {
			            if($(_this).find('>td:nth-child(1) > input').prop("checked")){
							if(q == n){
								$(this).find('select').val(val);
							}
						}
	                })
				}
	        })
		});

		if (sessionStorage.getItem("select-all") == "true")
		{
			$('[name="select-all"]').checked;
			$('[name="select-all"]').trigger('click');
			if( sessionStorage.getItem("active-select") != "false" )
			{
				$('[name="active-select"]').val(parseInt(sessionStorage.getItem("active-select")));
				$('[name="active-select"]').trigger('change');
				console.log("And tuta");
			}

			if( sessionStorage.getItem("active-select-1") != "false" )
			{
				$('[name="active-select-1"]').val(parseInt(sessionStorage.getItem("active-select-1")));
				$('[name="active-select-1"]').trigger('change');
			}

		}		
	});

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
