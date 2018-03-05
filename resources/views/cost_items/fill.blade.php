@extends('layouts.master')

@section('content')
<div ng-controller="cost-item" class="main-body ng-scope flex" data-ui-view="" data-flex="">
    <md-card class="md-table ng-scope _md">
    <md-card-content>
      <div class="md-table-loader" data-ng-if="!loaded">
            <md-progress-circular md-mode="indeterminate"></md-progress-circular>
        </div>
                <section class="md-table-header">
            <div class="md-table-header-title">
                            <span ng-click="toggleRight()" >Заполнение статьи затрат</span>
                        </div>
        </section>

			{!! Form::open(['url' => 'fill_product_cost_items']) !!}



@if(count($products))
<table class="table table-bordered">
	<tr class="first-table-tr">
		<th><span>Артикул<span data-text="Артикул товара"></span> </span></th>
		<th><span>Название<span data-text="Название товара"></span> </span></th>
		<th><span>Цена<span data-text="Цена товара"></span> </span></th>
		<th><span>Статья затрат<span data-text="Указаная статья затрат"></span> </span></th>
	</tr>

@foreach($products as $product)

	<tr>
		<td>{{ $product->model }}</td>
		<td>{{ $product->description->name }}</td>
		<td data-sum>{{ $product->price }}</td>
		<td>{!! Form::select("items[$product->product_id]", $cost_items, $product->cost_item, ['placeholder' => 'empty']); !!}</td>
	</tr>

@endforeach

</table>

@endif


    			{!! Form::submit('Сохранить', ['class'=>'btn btn-large btn-success']); !!}

			{!! Form::close() !!}
			<script>
			for(let i = 0 ; i < $("[data-sum]").length; i++){
					$($("[data-sum]")[i]).text((+$($("[data-sum]")[i]).text()).toFixed(2))
			}
			</script>

</md-card-content>
</md-card>
</div>
@endsection
