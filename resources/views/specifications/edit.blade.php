@extends('layouts.master')

@section('content')
<div ng-controller="specification" class="main-body ng-scope flex" data-ui-view="" data-flex="">
    <md-card class="md-table ng-scope _md">
    <md-card-content>
      <div class="md-table-loader" data-ng-if="!loaded">
            <md-progress-circular md-mode="indeterminate"></md-progress-circular>
        </div>
                <section class="md-table-header">
            <div class="md-table-header-title">
                            <span ng-click="toggleRight()" >Редактирование спецификации</span>
                        </div>
        </section>
        <section class="md-table-body">
<h1>{{ $specification->name }}</h1>
<div class="col-xs-12 col-md-3  edit-spec create-edit" style="margin-top: 15px; width: 100%;float: none;font-size: 0;max-width: 100%;">
			{!! Form::model($specification, ['url' => ['specifications/update', $specification->id]]); !!}

@if($specification->main_specification != null && Auth::user()->isClientAdmin())

				<div class="input-block">
					<md-input-container class="md-icon-float md-block">
					<label>Имя</label>
						{!! Form::text('name-specification-edit', null, array('data-ng-name-specification-edit' => 'auth.email', 'required')); !!}
					</md-input-container>
				</div>

@endif



@if(Auth::user()->isClientAdmin())
<div class="input-block" style="line-height: 1.2">
	<md-input-container class="md-icon-float md-block">
		<label>Первый возможный день для заказа</label>
		{!! Form::number('order_begin', null, array('data-ng-order-begin' => 'auth.email', 'required')); !!}
	</md-input-container>
</div>
<div class="input-block" style="line-height: 1.2">
	<md-input-container class="md-icon-float md-block">
		<label>Последний возможный день для заказа</label>
		{!! Form::number('order_end', null, array('data-ng-order-end' => 'auth.email', 'required')); !!}
	</md-input-container>
	</div>
@endif
</div>
@if(count($products))
	<div class="mobile-toogle">
<table class="table table-bordered">
	<tr class="first-table-tr specification-table">
		<th><span>Артикул</span>
		<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
			<md-icon class="md-ic">&#xE887;</md-icon>
			<md-tooltip>
				Артикул товара
			</md-tooltip>
		</md-button>
		</th>
		<th><span>Название</span>
		<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
			<md-icon class="md-ic">&#xE887;</md-icon>
			<md-tooltip>
				Название товара
			</md-tooltip>
		</md-button>
		</th>
		<th><span>Цена</span>
		<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
			<md-icon class="md-ic">&#xE887;</md-icon>
			<md-tooltip>
				Цена товара
			</md-tooltip>
		</md-button>
		</th>
	@if($specification->main_specification != null)
		<th><span>Выбрать</span>
		<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
			<md-icon class="md-ic">&#xE887;</md-icon>
			<md-tooltip>
				Выбрать товар в спецификацию
			</md-tooltip>
		</md-button>
		</th>
	@endif
	<th><span>Лимит</span>
	<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
			<md-icon class="md-ic">&#xE887;</md-icon>
			<md-tooltip>
				Лимит указаный на товар
			</md-tooltip>
		</md-button>
		</th>
	<th><span>Период</span>
	<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
			<md-icon class="md-ic">&#xE887;</md-icon>
			<md-tooltip>
				Период указаный на обноваления лимита
			</md-tooltip>
		</md-button>
		</th>
	</tr>
@foreach($products as $product)

	<tr>
		<td>{{ $product->model }}</td>
		<td>{{ $product->description->name }}</td>
		<td data-sum>{{ $product->price }}</td>
	@if($specification->main_specification != null)
		<td>{!! Form::checkbox("items[$product->product_id]", $product->product_id, $product->selected); !!}</td>
	@elseif($specification->main_specification == null)
		{!! Form::hidden("items[$product->product_id]", $product->product_id); !!}
	@endif
		<td data-lem>{!! Form::number("limits[$product->product_id]", $product->limit, ['placeholder' => 'Без лимита']); !!}</td>
		<td>{!! Form::number("periods[$product->product_id]", $product->period); !!}</td>
	</tr>

@endforeach

</table>
	</div>

@endif


    			{!! Form::submit('Обновить', ['class'=>'btn btn-large btn-warning mob']); !!}

			{!! Form::close() !!}
			<script>
			for(let i = 0 ; i < $("[data-sum]").length; i++){
			    if(!!$($("[data-sum]")[i]).text()) $($("[data-sum]")[i]).text((+$($("[data-sum]")[i]).text()).toFixed(2))
			}
			for(let i = 0 ; i < $("[data-lem]").length; i++){
			    if(!!$($("[data-lem]")[i]).find('input').val()) $($("[data-lem]")[i]).find('input').val((+$($("[data-lem]")[i]).find('input').val()).toFixed(0))
			}
			</script>
</section>
</md-card-content>
</md-card>
</div>
@endsection
