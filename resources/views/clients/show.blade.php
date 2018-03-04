@extends('layouts.master')

@section('content')
<h1 class="center-h1">Просмотр клиента</h1>
<table class="table table-bordered">
    <tr class="first-table-tr">
        <td>Название</td>
        <td>Номер телефона</td>
        <td>Адрес</td>
    </tr>
    <tr>
        <td>{{ $client->name }}</td>
        <td>{{ $client->phone_number }}</td>
        <td>{{ $client->address }}</td>
    </tr>
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

@endsection
