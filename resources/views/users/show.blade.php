@extends('layouts.master')

@section('content')
    <div ng-controller="user" class="main-body ng-scope flex" data-ui-view="" data-flex="">
    <md-card class="md-table ng-scope _md">
    <md-card-content>
      <div class="md-table-loader" data-ng-if="!loaded">
            <md-progress-circular md-mode="indeterminate"></md-progress-circular>
        </div>
                <section class="md-table-header">
            <div class="md-table-header-title">
                            <span ng-click="toggleRight()" >Просмотр пользователя</span>
                        </div>
        </section>
        <section class="md-table-body">

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>{{ $user->id }}</th>
        </tr>
        <tr>
            <th>E-mail</th>
            <th>{{ $user->email }}</th>
        </tr>
        <tr>
            <th>Имя</th>
            <th>{{ $user->first_name }}</th>
        </tr>
        <tr>
            <th>Фамилия</th>
            <th>{{ $user->last_name }}</th>
        </tr>
        <tr>
            <th>Номер телефона</th>
            <th>{{ $user->phone_number }}</th>
        </tr>
        @if($user->employer)
            <tr>
                <th>Клиент</th>
                <th><a href="/clients/{{ $user->employer->id }}">{{ $user->employer->name }}</a></th>
            </tr>
        @endif
        <tr>
            <th>Роль</th>
            <th>{{ $user->role->name }}</th>
        </tr>
        @if($user->subject)
            <tr>
                <th>Подразделение</th>
                <th>{{ $user->subject->name }}</th>
            </tr>
        @endif
    </table>
    @can('update', $user)
        @if(Auth::user()->id != $user->id)
            <a class="btn btn-large btn-primary" href="/users/edit/{{ $user->id }}">Изменить</a>
        @endif
    @endcan
</section>
</md-card-content>
</md-card>
</div>
@endsection
