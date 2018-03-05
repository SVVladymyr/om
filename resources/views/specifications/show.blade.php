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
                            <span ng-click="toggleRight()" >Просмотр спецификации</span>
                        </div>
        </section>
        <section class="md-table-body">
<h1>{{ $specification->name }}</h1>

@if(Auth::user()->isManager() && !$specification->main_specification && count($specification->clients))
{{ $specification->clients->first()->name }}
@endif

{{--@if(isset($affected_clients) && count($affected_clients))--}}
    {{--<table>--}}
        {{--<tr>--}}
            {{--<th>client_name</th>--}}
            {{--<th>order_id</th>--}}
            {{--<th>order_sum</th>--}}
            {{--<th>order_status</th>--}}
        {{--</tr>--}}
{{--@foreach($affected_clients as $affected_client)--}}

    {{--<tr>--}}
        {{--<td>{{ $affected_client->name }}</td>--}}
        {{--<td></td>--}}
        {{--<td></td>--}}
        {{--<td></td>--}}
    {{--</tr>--}}

    {{--@foreach($affected_client->orders as $affected_order)--}}
    {{--<tr>--}}
        {{--<td></td>--}}
        {{--<td>{{ $affected_order->id }}</td>--}}
        {{--<td>{{ $affected_order->sum }}</td>--}}
        {{--<td>{{ $affected_order->status->name }}</td>--}}
    {{--</tr>--}}
    {{--@endforeach--}}
{{--@endforeach--}}

{{--</table>--}}

{{--@endif--}}
<div style="padding: 20px;" class="mobile-toogle">
    @if(count($products))

<table class="table table-bordered">
    <tr class="first-table-tr">
        <th><span>Артикул<span data-text="Модель данного товара"></span></span></th>
        <th><span>Имя<span data-text="Название данного товара"></span></span></th>
        <th><span>Цена<span data-text="Цена за еденицу товара"></span></span></th>
        <th><span>Лимит<span data-text="Лимит установленный на этот товар"></span></span></th>
        <th><span>Период<span data-text="Длительность периода обновления лимита"></span></span></th>
    </tr>

@foreach($products as $product)

    <tr>
        <td>{{ $product->model }}</td>
        <td>{{ $product->description->name }}</td>
        <td data-sum>{{ $product->price }}</td>
        <td data-lem>{{ $product->limit }}</td>
        <td>{{ $product->period }}</td>
    </tr>

@endforeach

</table>

@endif
        </div>
    @can('upload', $specification)

        <form action="/specifications/{{ $specification->id }}/upload"
            class="dropzone"
            id="my-awesome-dropzone">
            {{ csrf_field() }}
        </form>

    @endcan

    <a class="btn btn-large btn-warning mob-xl" href="/specifications/edit/{{ $specification->id }}">Изменить</a>
    @can('delete', $specification)
        <a class="btn btn-large btn-danger mob-xl" href="#" onclick="deleteItem('/specifications/delete/{{ $specification->id }}')" >Удалить</a>
    @endcan
<script>
for(let i = 0 ; i < $("[data-sum]").length; i++){
    $($("[data-sum]")[i]).text((+$($("[data-sum]")[i]).text()).toFixed(2))
}
for(let i = 0 ; i < $("[data-lem]").length; i++){
    $($("[data-lem]")[i]).text((+$($("[data-lem]")[i]).text()).toFixed(0))
}
</script>
    <end-table-specification />
</section>
</md-card-content>
</md-card>
</div>
@endsection
