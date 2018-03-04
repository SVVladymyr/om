@extends('layouts.master')

@section('content')
<h1 class="center-h1">Пользователи</h1>
        <div class="mobile-toogle">
        <table class="table table-bordered">
                <tr class="first-table-tr">
                        <th><span>E-mail<span data-text="E-mail пользователя"></span></span></th>
                        <th><span>Имя<span data-text="Имя пользователя"></span></span></th>
                        <th><span>Фамилия<span data-text="Фамилия пользователя"></span></span></th>
                        <th><span>Номер телефона<span data-text="Номер телефона пользователя"></span></span></th>
                        <th><span>Главный<span data-text="Главный адрес подразделения"></span></span></th>
                        <th><span>Роль<span data-text="Роль выставленная у данного пользователя"></span></span></th>
                        <th><span>Подразделение<span data-text="Подразделение главного пользователя"></span></span></th>
                        <th></th>
                </tr>
        @foreach($users as $user)

        @include('users.user')

        @endforeach
        </table>
        </div>
        {{ $users->links() }}

        @can('create', App\User::class)

			<a class="btn btn-large btn-success mob" href="/users/create">Создать пользователя</a></br>

        @endcan

        <div class="modal">
            <h2 class="modal-title"></h2>
            <div class="close" onclick="$('.modal').hide()">X</div>
            <div class="modal-content mobile-toogle">

            </div>
        </div>
@endsection
