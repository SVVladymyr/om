@extends('layouts.master')

@section('content')
<h1 class="center-h1">Просмотр заказа</h1>
<!-- {{ $order->id }}</br> -->



    @if(count($products))
<table class="table table-bordered">
    <tr class="first-table-tr">
      <th><span>Артикул<span data-text="Артикул товара"></span> </span></th>
  		<th><span>Название<span data-text="Название товара"></span> </span></th>
  		<th><span>Цена<span data-text="Цена товара"></span> </span></th>
  		<th><span>Количество<span data-text="Количество товара"></span> </span></th>
    </tr>

@foreach($products as $product)

    <tr>
        <td>{{ $product->model }}</td>
        <td>{{ $product->description->name }}</td>
        <td data-sum>{{ $product->price }}</td>
        <td>{{ $product->amount }}</td>
    </tr>

@endforeach

</table>

@endif

        @can('update', $order)

            <a class="btn btn-large btn-primary mob"  href="/orders/edit/{{ $order->id }}">Изменить</a>

        @endcan
        <script>
        for(let i = 0 ; i < $("[data-sum]").length; i++){
            $($("[data-sum]")[i]).text((+$($("[data-sum]")[i]).text()).toFixed(2))
        }
        </script>
@endsection
