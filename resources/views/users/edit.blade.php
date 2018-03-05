@extends('layouts.master')

@section('content')
<open-modal>
    <div ng-controller="user" class="main-body ng-scope flex" data-ui-view="" data-flex="">
    <md-card class="md-table ng-scope _md">
    <md-card-content>
      <div class="md-table-loader" data-ng-if="!loaded">
            <md-progress-circular md-mode="indeterminate"></md-progress-circular>
        </div>
                <section class="md-table-header">
            <div class="md-table-header-title">
                            <span ng-click="toggleRight()" >Редактирование пользователя</span>
                        </div>
        </section>
        <section class="md-table-body">
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
</section>
</md-card-content>
</md-card>
</div>
@endsection
