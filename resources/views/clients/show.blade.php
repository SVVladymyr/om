@extends('layouts.master')

@section('content')
<div ng-controller="client" class="main-body ng-scope flex" data-ui-view="" data-flex="">
    <md-card class="md-table ng-scope _md">
        <md-card-content>
          <div class="md-table-loader" data-ng-if="!loaded">
            <md-progress-circular md-mode="indeterminate"></md-progress-circular>
        </div>
        <section class="md-table-header">
            <div class="md-table-header-title">
                <span ng-click="toggleRight()" >Просмотр клиента</span>
            </div>
        </section>
        <table class="table table-bordered">
            <thead>
                <tr class="first-table-tr">
                    <td>Название</td>
                    <td>Номер телефона</td>
                    <td>Адрес</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->phone_number }}</td>
                    <td>{{ $client->address }}</td>
                </tr>
            </tbody>
        </table>
        @if(Auth::user()->isClientAdmin())
        @can('limits', $client)
        <a class="btn btn-large btn-warning" href="/clients/{{ $client->id }}/limits">Лимит</a>
        @endcan
        @can('orders', $client)
        <a class="btn btn-large btn-info"    href="/clients/{{ $client->id }}/orders">Заказать</a>
        @endcan
        @can('limit_increases', $client)
        <a class="btn btn-large btn-warning" href="/clients/{{ $client->id }}/limit_increases">Запрос лимита</a>
        @endcan
        @endif
        @can('update', $client)
        <a style="margin-bottom: 0;" class="btn btn-large btn-primary" href="/clients/edit/{{ $client->id }}">Изменить</a>
        @endcan
        @if( Auth::user()->isSublevel() || Auth::user()->isConsumer()) 
        <a style="margin-bottom: 0;" class="btn btn-large btn-primary" href="/clients/{{Auth::user()["subject"]->id}}/network">Вернуться</a>
        @else
        <a style="margin-bottom: 0;" class="btn btn-large btn-primary" href="/clients/{{ $client->root_id}}/network">Вернуться</a>
        @endif

        @include('layouts.errors')
    </md-card-content>
</md-card>
</div>
@endsection
