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
  <md-dialog-content>

<!-- END -->

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
