<!-- шапка модалки -->
<md-dialog aria-label="Test">
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
<h1 ng-init="title = 'Просмотр спецификации: {{ $specification->name }}'"></h1>

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
<div style="padding: 0 15px;" class="mobile-toogle">
    @if(count($products))

<table class="table table-bordered">
    <tr class="first-table-tr">
        <th><span>Артикул</span>
        <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
            <md-icon class="md-ic">&#xE887;</md-icon>
            <md-tooltip>
                Модель данного товара
            </md-tooltip>
        </md-button>
        </th>
        <th><span>Имя</span>
        <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
            <md-icon class="md-ic">&#xE887;</md-icon>
            <md-tooltip>
                Название данного товара
            </md-tooltip>
        </md-button>
        </th>
        <th><span>Цена</span>
        <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
            <md-icon class="md-ic">&#xE887;</md-icon>
            <md-tooltip>
                Цена за еденицу товара
            </md-tooltip>
        </md-button>
        </th>
        <th><span>Лимит</span>
        <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
            <md-icon class="md-ic">&#xE887;</md-icon>
            <md-tooltip>
                Лимит установленный на этот товар
            </md-tooltip>
        </md-button>
        </th>
        <th><span>Период</span>
        <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
            <md-icon class="md-ic">&#xE887;</md-icon>
            <md-tooltip>
                Длительность периода обновления лимита
            </md-tooltip>
        </md-button>
        </th>
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

<script>
for(let i = 0 ; i < $("[data-sum]").length; i++){
    $($("[data-sum]")[i]).text((+$($("[data-sum]")[i]).text()).toFixed(2))
}
for(let i = 0 ; i < $("[data-lem]").length; i++){
    $($("[data-lem]")[i]).text((+$($("[data-lem]")[i]).text()).toFixed(0))
}
$("#my-awesome-dropzone").dropzone();
</script>
<!-- футер модалки -->

</md-dialog-content>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.1.1/min/dropzone.min.css" />

<md-dialog-actions layout="row">
    <a class="btn btn-large btn-warning mob-xl" href="/specifications/edit/{{ $specification->id }}">Изменить</a>
    @can('delete', $specification)
        <md-button style="margin-left: 15px!important;" class="md-warn md-raised" ng-click="DeleteItemsCreates($event,'{{ $specification->id }}')" style="margin: 0;">
            Удалить
        </md-button>
       <!-- <a class="btn btn-large btn-danger mob-xl" href="#" onclick="deleteItem('/specifications/delete/{{ $specification->id }}')" >Удалить</a> -->
    @endcan
</md-dialog-actions>
</md-dialog>

<!-- END -->
