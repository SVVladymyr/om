@extends('layouts.master')

@section('content')
<h1 class="center-h1">Редактирование клиента</h1>
<div style="padding-top: 25px;width: 115%;min-width: calc(50% + 40px);margin: 0 auto;float: none;display: block;" class="col-xs-12 col-md-3 create-edit user-edit">
{!! Form::model($client, ['url' => ['clients/update', $client->id]]); !!}
    	<div class="colum-left">
@if(Auth::user()->isClientAdmin() || Auth::user()->isCompanyAdmin())

                @if(Auth::user()->isCompanyAdmin() && $client->ancestor === null)

                {!! Form::label('one_c_id', 'Идентификатор'); !!}
                {!! Form::number('one_c_id', null, array('class'=>'form-control','placeholder' => 'ОКПО')); !!}</br>

                {!! Form::label('guid', 'GUID'); !!}
                {!! Form::text('guid', null, array('class'=>'form-control','placeholder' => 'Уникальный идентификатор клиента 1с')); !!}</br>

                @endif

                {!! Form::label('name', 'Имя'); !!}
                {!! Form::text('name', null, ['class'=>'form-control', 'placeholder' => 'name']); !!}</br>

              	{!! Form::label('code', 'Код'); !!}
                {!! Form::text('code', null, ['class'=>'form-control', 'placeholder' => 'code']); !!}</br>

                @if(Auth::user()->isCompanyAdmin() && $client->ancestor === null)



                    {!! Form::label('manager_id', 'Менеджер подразделения'); !!}
                    {!! Form::select('manager_id', $managers, null, ['class'=>'form-control', 'placeholder' => 'empty']); !!}</br>

                @endif

                	{!! Form::label('master_id', 'Начальник подразделения'); !!}
                {!! Form::select('master_id', $masters, null, ['placeholder' => 'empty', 'class'=>'form-control']); !!}</br>


                @if($client->ancestor()->count())

                {!! Form::label('ancestor_id', 'Вышестоящее подразделение'); !!}
                {!! Form::select('ancestor_id', $ancestors, null, ['class'=>'form-control']); !!}</br>

                @endif

@endif


@if(Auth::user()->isClientAdmin() || Auth::user()->isManager())
        {!! Form::label('specification_id', 'ID спецификации    '); !!}
{!! Form::select('specification_id', $specifications, null, ['placeholder' => 'ID Спецификации', 'class'=>'form-control']); !!}</br>
@endif
</div>
<div class="colum-right">
@if(Auth::user()->isClientAdmin() || Auth::user()->isCompanyAdmin())
{!! Form::label('phone_number', 'Номер телефона'); !!}
{!! Form::text('phone_number', null, ['class'=>'form-control', 'placeholder' => 'Номер телефона']); !!}</br>

  {!! Form::label('address', 'Адрес'); !!}
{!! Form::text('address', null, ['class'=>'form-control', 'placeholder' => 'Адрес']); !!}</br>

  {!! Form::label('main_contractor', 'Юридическое лицо'); !!}
{!! Form::text('main_contractor', null, ['class'=>'form-control', 'placeholder' => 'Юр. лицо']); !!}</br>

    {!! Form::label('organization', 'Оргинизация'); !!}
{!! Form::text('organization', null, ['class'=>'form-control', 'placeholder' => 'Организация']); !!}</br>

@endif
</div>
{!! Form::submit('Обновить', ['class' => 'btn btn-large btn-success']); !!}


{!! Form::close() !!}
</div>


@endsection
