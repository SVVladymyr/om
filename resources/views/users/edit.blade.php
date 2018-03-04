@extends('layouts.master')

@section('content')
<open-modal>
<h1 class="center-h1">Редактирование пользователя</h1>

	<div class="user-edit" style="padding-top: 25px;" class="col-xs-12 col-md-3">

{!! Form::model($user, ['url' => ['users/update', $user->id]]); !!}

<div class="colum-left">
    			{!! Form::label('first_name', 'Имя'); !!}
    			{!! Form::text('first_name', null, ['class'=>'form-control', 'placeholder'=>'Имя']); !!}</br>

    			{!! Form::label('last_name', 'Фамилия'); !!}
    			{!! Form::text('last_name', null, ['class'=>'form-control', 'placeholder'=>'Фамилия']); !!}</br>

    			{!! Form::label('phone_number', 'Номер телефона'); !!}
    			{!! Form::text('phone_number', null, ['class'=>'form-control', 'placeholder'=>'Номер телефона']); !!}</br>
				</div>
				<div class="colum-right">

    			{!! Form::label('email', 'E-Mail'); !!}
    			{!! Form::text('email', null, ['class'=>'form-control', 'placeholder'=>'E-Mail']); !!}</br>

    			{!! Form::label('role_id', 'Роль'); !!}
                {!! Form::select('role_id', $roles, null, ['class'=>'form-control', 'placeholder'=>'Роль']); !!}</br>

                @if(Auth::user()->isCompanyAdmin())

                    {!! Form::label('employer_id', 'Работодатель'); !!}
                    {!! Form::select('employer_id', $employers, null, ['placeholder' => 'free', 'class'=>'form-control']); !!}</br>
                @endif

				{!! Form::label('show_price_status', 'Отображать цены'); !!}
    			{!! Form::checkbox('show_price_status', '1', true); !!}</br>
</div>
    			{!! Form::submit('Обновить', ['class'=>'btn btn-large btn-success']); !!}

			{!! Form::close() !!}

	</div>
  @include('layouts.errors')
<close-modal>

@endsection
