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
                <span ng-click="toggleRight()" >Мои клиенты</span>
            </div>
        </section>
<h1 class="center-h1">Редактирование клиента</h1>
<div style="padding-top: 15px;width: 115%;min-width: calc(50% + 40px);margin: 0 auto;float: none;display: block;" class="col-xs-12 col-md-3 create-edit user-edit">
{!! Form::model($client, ['url' => ['clients/update', $client->id]]); !!}
@if(Auth::user()->isClientAdmin() || Auth::user()->isCompanyAdmin())

                @if(Auth::user()->isCompanyAdmin() && $client->ancestor === null)

                {!! Form::label('one_c_id', 'Идентификатор'); !!}
                {!! Form::number('one_c_id', null, array('class'=>'form-control','placeholder' => 'ОКПО')); !!}</br>

                {!! Form::label('guid', 'GUID'); !!}
                {!! Form::text('guid', null, array('class'=>'form-control','placeholder' => 'Уникальный идентификатор клиента 1с')); !!}</br>

                @endif
                    <md-input-container class="md-icon-float md-block">
                        <label>Имя</label>
                        {!! Form::text('name-clients-edit', null, array('data-ng-name-clients-edit' => 'auth.email', 'required')); !!}
                    </md-input-container>

                    <md-input-container class="md-icon-float md-block">
                        <label>Код</label>
                        {!! Form::text('name-code', null, array('data-ng-code' => 'auth.email', 'required')); !!}
                    </md-input-container>

                @if(Auth::user()->isCompanyAdmin() && $client->ancestor === null)
                    <md-input-container class="md-icon-float md-block">
                        <label>Менеджер подразделения</label>
                        {!! Form::text('name-manager-id', null, array('data-ng-manager-id' => 'auth.email', 'required')); !!}
                    </md-input-container>

                @endif
                    <md-input-container class="md-icon-float md-block">
                        <label>Начальник подразделения</label>
                        {!! Form::text('name-master-id', null, array('data-ng-master-id' => 'auth.email', 'required')); !!}
                    </md-input-container>

                @if($client->ancestor()->count())
                    <md-input-container class="md-icon-float md-block">
                        <label>Вышестоящее подразделение</label>
                        {!! Form::text('name-ancestor-id', null, array('data-ng-ancestor-id' => 'auth.email', 'required')); !!}
                    </md-input-container>

                @endif

@endif


@if(Auth::user()->isClientAdmin() || Auth::user()->isManager())

                {!! Form::label('specification_id', 'ID спецификации    '); !!}
                {!! Form::select('specification_id', $specifications, null, ['placeholder' => 'ID Спецификации', 'class'=>'form-control']); !!}</br>
@endif
@if(Auth::user()->isClientAdmin() || Auth::user()->isCompanyAdmin())
    <md-input-container class="md-icon-float md-block">
        <label>Номер телефона</label>
        {!! Form::text('name-phone-number', null, array('data-ng-phone-number' => 'auth.email', 'required')); !!}
    </md-input-container>

    <md-input-container class="md-icon-float md-block">
        <label>Адрес</label>
        {!! Form::text('name-address', null, array('data-ng-address' => 'auth.email', 'required')); !!}
    </md-input-container>

    <md-input-container class="md-icon-float md-block">
        <label>Юридическое лицо</label>
        {!! Form::text('name-main-contractor', null, array('data-ng-main-contractor' => 'auth.email', 'required')); !!}
    </md-input-container>

    <md-input-container class="md-icon-float md-block">
        <label>Оргинизация</label>
        {!! Form::text('name-organization', null, array('data-ng-organization' => 'auth.email', 'required')); !!}
    </md-input-container>

@endif
<div class="clients-edit-btn">
    {!! Form::submit('Обновить', ['class' => 'btn btn-large btn-success']); !!}
</div>

{!! Form::close() !!}
</div>
</md-card-content>
</md-card>
</div>
@endsection
