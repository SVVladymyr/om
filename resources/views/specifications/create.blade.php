<!-- шапка модалки -->
<md-dialog aria-label="Test">
  	{!! Form::open(['route' => 'specifications']) !!}
  <md-toolbar>
    <div class="md-toolbar-tools">
      <h2 ng-bind-html="title"></h2>
      <span flex></span>
      <md-button class="md-icon-button" ng-click="cancel()">
        <md-icon md-svg-src="img/icons/ic_close_24px.svg" aria-label="Close dialog"></md-icon>
      </md-button>
    </div>
  </md-toolbar>
  <md-dialog-content style="padding: 30px 20px;">

<!-- END -->

@if(count($products))
	<div class="mobile-toogle">
<table class="table table-bordered">
	<tr class="first-table-tr create-specifications-table">
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
		<th><span>Выбрать</span>
		<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
			<md-icon class="md-ic">&#xE887;</md-icon>
			<md-tooltip>
				Выбрать товар в спецификацию
			</md-tooltip>
		</md-button>
		</th>
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
		<td data-sum>  {{ $product->price }}</td>
		<td style="    text-align: center;">{!! Form::checkbox("items[$product->product_id]", $product->product_id, $product->selected); !!}</td>
		<td data-lem>
			<!--<md-input-container class="md-icon-float md-block" style="margin: 0;"> -->
				{!! Form::number("limits[$product->product_id]", null, array('data-ng-limits' => 'auth.email')); !!}
			<!--</md-input-container> -->
		</td>
		<td>
			<!--<md-input-container class="md-icon-float md-block" style="margin: 0;"> -->
				{!! Form::number("periods[$product->product_id]", null, array('data-ng-periods' => 'auth.email')); !!}
			<!--</md-input-container> -->
		</td>
	</tr>

@endforeach

</table>
	</div>

@endif
<md-input-container class="md-icon-float md-block">
  <label>Имя</label>
  {!! Form::text('name', null, array('data-ng-name' => 'auth.email', 'required')); !!}
</md-input-container>

<!-- <div class="input-block">
  {!! Form::label('name', 'Имя'); !!}
  {!! Form::text('name', null, ['placeholder'=>'Имя', 'class'=>'form-control']); !!}
</div> -->


@if(Auth::user()->isClientAdmin())
<md-input-container class="md-icon-float md-block">
<label>Первый возможный день для заказа</label>
{!! Form::number('order_begin', null, array('data-ng-order-begin' => 'auth.email', 'required')); !!}
</md-input-container>
<md-input-container class="md-icon-float md-block">
<label>Последний возможный день для заказа</label>
{!! Form::number('order_end', null, array('data-ng-order-end' => 'auth.email', 'required')); !!}
</md-input-container>
@endif


			<script>
			for(let i = 0 ; i < $("[data-sum]").length; i++){
			    if(!!$($("[data-sum]")[i]).text()) $($("[data-sum]")[i]).text((+$($("[data-sum]")[i]).text()).toFixed(2))
			}
			for(let i = 0 ; i < $("[data-lem]").length; i++){
			   if(!!$($("[data-lem]")[i]).find('input').val()) $($("[data-lem]")[i]).find('input').val((+$($("[data-lem]")[i]).find('input').val()).toFixed(0))
			}
			</script>
</style>

<!-- футер модалки -->

</md-dialog-content>
<md-dialog-actions layout="row">
  <md-button type="submit" class="md-primary md-raised">
    Создать
  </md-button>
</md-dialog-actions>
	{!! Form::close() !!}
</md-dialog>

<!-- END -->
