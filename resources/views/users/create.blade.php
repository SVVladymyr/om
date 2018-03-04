@extends('layouts.master')

@section('content')
<h1 class="center-h1">Создание пользователя</h1>

	<div class="user-edit" style="padding-top: 25px;" class="col-xs-12 col-md-3">

			{!! Form::open(['route' => 'users']) !!}

				<div class="colum-left">
					{!! Form::label('first_name', 'Имя'); !!}
    			{!! Form::text('first_name', null, ['class'=>'form-control', 'placeholder'=>'Имя']); !!}</br>

    			{!! Form::label('last_name', 'Фамилия'); !!}
    			{!! Form::text('last_name', null, ['class'=>'form-control', 'placeholder'=>'Фамилия']); !!}</br>

    			{!! Form::label('phone_number', 'Номер телефона'); !!}
    			{!! Form::text('phone_number', null, ['class'=>'form-control', 'placeholder'=>'Номер телефона']); !!}</br>


    			{!! Form::label('email', 'E-Mail'); !!}
    			{!! Form::text('email', null, ['class'=>'form-control', 'placeholder'=>'E-mail']); !!}</br>
				</div>
				<div class="colum-right">
					{!! Form::label('password', 'Пароль'); !!}
    			{!! Form::password('password', ['class'=>'form-control', 'placeholder'=>'Пароль']); !!}</br>

    			{!! Form::label('password_confirmation', 'Подтверждение пароля'); !!}
    			{!! Form::password('password_confirmation',['class'=>'form-control', 'placeholder'=>'Подтверждение пароля']); !!}</br>

	   			{!! Form::label('role_id', 'Роль'); !!}
                {!! Form::select('role_id', $roles, null, ['class'=>'form-control']); !!}</br>
					@if(Auth::user()->isCompanyAdmin())
                {!! Form::label('employer_id', 'Работодатель'); !!}
                {!! Form::select('employer_id', $employers, null, ['placeholder' => 'Свободный', 'class'=>'form-control']); !!}</br>
					@endif

				</div>
				{!! Form::submit('Создать', ['class'=>'btn btn-large btn-success']); !!}



    			{!! Form::label('show_price_status', 'Отображать цены'); !!}
    			{!! Form::checkbox('show_price_status', '1', true); !!}</br>


			{!! Form::close() !!}

	</div>

<script>
$.ajax({
		url : '/js/ru.json',
		type: "GET",
		success: function (data) {
				for(let i = 0 ; i < $('#role_id option').length; i++){
					$($('#role_id option')[i]).text(data[$($('#role_id option')[i]).text()])
				}
		}
})

</script>

@endsection
