
		<!-- шапка модалки -->
<md-dialog aria-label="Test">
   
				{!! Form::open(['url' => "clients/$client->id/limits"]) !!}
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



				<table class="table table-bordered">
					<tr class="first-table-tr">
						<th>Имя</th>
						<th>Состояние</th>
						<th>Лимит</th>
						<th>Период (мес.)</th>
						<th>Доступный остаток</th>
					</tr>
					<tr>
						<td>Денежный лимит</td>
						<td>{!! Form::select("active[]", ['1' => 'Вкл', '0' => 'Выкл'], "$money_limit->active"); !!}
						<td  data-lem>
						{!! Form::number("limit[]", $money_limit->limit, ['placeholder' => 'Без лимита']); !!} грн.</td>
						<td  data-lem>{!! Form::number("period[]", $money_limit->period); !!}</td>
						<td  data-lem>{!! Form::number("value[]", $money_limit->current_value, ['placeholder' => 'Без лимита']); !!} грн.</td>
						{!! Form::hidden("type[]", 'Money'); !!}
						{!! Form::hidden("id[]", 0); !!}
						{!! Form::hidden("limit_id[]", $money_limit->id); !!}
					</tr>


					@if(count($cost_items))

					@foreach($cost_items as $cost_item)

					<tr>
						<td>{{ $cost_item->name }}</td>
						<td>{!! Form::select("active[]", ['1' => 'Вкл', '0' => 'Выкл'], "$cost_item->active"); !!}</td>
						<td  data-lem>{!! Form::number("limit[]", $cost_item->limit, ['placeholder' => 'Без лимита']); !!} грн.</td>
						<td  data-lem>{!! Form::number("period[]", $cost_item->period); !!}</td>
						<td  data-lem>{!! Form::number("value[]", $cost_item->current_value, [ 'placeholder' => 'Без лимита']); !!} грн.</td>
						{!! Form::hidden("type[]", $cost_item->class_name); !!}
						{!! Form::hidden("id[]", $cost_item->id); !!}
						{!! Form::hidden("limit_id[]", $cost_item->limit_id); !!}
					</tr>


					@endforeach

					@endif




					@if(count($products))

					@foreach($products as $product)

					<tr>
						<td>{{ $product->description->name }}</td>
						<td>{!! Form::select("active[]", ['1' => 'Вкл', '0' => 'Выкл'], "$product->active"); !!}</td>
						<td  data-lem>{{ $product->limit }}</td>
						<td  data-lem>{{ $product->period }}</td>
						<td  data-lem>{!! Form::number("value[]", $product->current_value, ['placeholder' => 'Без лимита']); !!}</td>
						{!! Form::hidden("limit[]", null); !!}
						{!! Form::hidden("period[]", null); !!}
						{!! Form::hidden("type[]", $product->class_name); !!}
						{!! Form::hidden("id[]", $product->product_id); !!}
						{!! Form::hidden("limit_id[]", $product->limit_id); !!}
					</tr>

					@endforeach

					@endif

				</table>



				{!! Form::submit('Установить лимит', ['class' => "btn btn-large btn-success"]); !!}

				<script>
					for(let i = 0 ; i < $("[data-sum]").length; i++){
						if(!!$($("[data-sum]")[i]).text()) $($("[data-sum]")[i]).text((+$($("[data-sum]")[i]).text()).toFixed(0))
					}
				for(let i = 0 ; i < $("[data-lem]").length; i++){
					if(!!$($("[data-lem]")[i]).find('input').val()) $($("[data-lem]")[i]).find('input').val((+$($("[data-lem]")[i]).find('input').val()).toFixed(0))
				}
		</script>


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