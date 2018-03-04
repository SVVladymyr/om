@extends('layouts.master')

@section('content')
<h1 class="center-h1">Просмотр пользователя</h1>

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

@endsection
