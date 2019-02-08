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
                <span >Редактирование клиента</span>
            </div>
        </section>
<div style="padding-top: 15px;width: 115%;min-width: calc(50% + 40px);margin: 0 auto;float: none;display: block;" class="col-xs-12 col-md-3 create-edit user-edit">
    <div class="colum-left">
    {!! Form::model($client, ['url' => ['clients/update', $client->id]]); !!}
@if(Auth::user()->isClientAdmin() || Auth::user()->isCompanyAdmin())

                @if(Auth::user()->isCompanyAdmin() && $client->ancestor === null)
                
                <md-input-container class="md-icon-float md-block">
                    <label>Идентификатор</label>
                    {!! Form::text('one_c_id', null, array('data-ng-name-one-c-id' => 'auth.email', 'required')); !!}
                </md-input-container>

                <md-input-container class="md-icon-float md-block">
                    <label>GUID</label>
                    {!! Form::text('guid', null, array('data-ng-name-guid' => 'auth.email', 'required')); !!}
                </md-input-container>

                @endif
                    <md-input-container class="md-icon-float md-block">
                        <label>Имя</label>
                        {!! Form::text('name', null, array('data-ng-name-clients-edit' => 'auth.email', 'required')); !!}
                    </md-input-container>

                    <md-input-container class="md-icon-float md-block">
                        <label>Код</label>
                        {!! Form::text('code', null, array('data-ng-code' => 'auth.email', 'required')); !!}
                    </md-input-container>

                @if(Auth::user()->isCompanyAdmin() && $client->ancestor === null)
                        <div style="margin-top: 0px; margin-bottom: 27px;">
                            {!! Form::label('manager_id', 'Менеджер подразделения'); !!}
                            {!! Form::select('manager_id', $managers, null, ['class'=>'form-control', 'placeholder' => 'empty']); !!}
                        </div>

                            @endif
                    <div style="margin-top: 0px; margin-bottom: 27px;">
                    {!! Form::label('master_id', 'Начальник подразделения'); !!}
                    <!--  {!! Form::select('master_id', $masters, null, array('class'=>'form-control','placeholder' => 'Начальник подразделения', 'keydown'=>'filterClientCreate()')); !!}</br>-->
                        <select class="limitedNumbChosen form-control" name="master_id" id="master_id">
{{--                            @if(!isset($client->master_id))--}}
                                <option selected="selected" value="0">empty</option>
                            {{--@endif--}}
                            @foreach($masters as $id=>$master)
                                <option {{ $client->master_id == $id ? 'selected' : '' }} value="{{$id}}">{{$master}}</option>
                            @endforeach
                        </select>

                    </div>
                    @if($client->ancestor()->count())
                        <div style="margin-top: 0px; margin-bottom: 27px;">
                        {!! Form::label('ancestor_id', 'Вышестоящее подразделение'); !!}
                        <!--{!! Form::select('ancestor_id', $ancestors, null, array('class'=>'form-control','placeholder' => 'Вышестоящее подразделение')); !!}</br>-->
                            <select class="limitedNumbChosen form-control" name="ancestor_id" id="ancestor_id">
                                @if(!isset($client->ancestor_id))
                                    <option selected="selected" value="">Вышестоящее подразделение</option>
                                @endif
                                @foreach($ancestors as $id=>$ancestor)
                                    <option {{ $client->ancestor_id == $id ? 'selected' : '' }} value="{{$id}}">{{$ancestor}}</option>
                                @endforeach
                            </select>

                        </div>
                @endif

@endif
    </div>
    <div class="colum-right">

@if(Auth::user()->isClientAdmin() || Auth::user()->isManager())
        <div style="margin-top: 0px; margin-bottom: 27px;">
            {!! Form::label('specification_id', 'ID спецификации    '); !!}
            {!! Form::select('specification_id', $specifications, null, ['placeholder' => 'ID Спецификации', 'class'=>'form-control']); !!}
        </div>
@endif
@if(Auth::user()->isClientAdmin() || Auth::user()->isCompanyAdmin())
    <md-input-container class="md-icon-float md-block">
        <label>Номер телефона</label>
        {!! Form::text('phone_number', null, array('data-ng-phone_number' => 'auth.email', 'required', 'id'=>'phone_number')); !!}
    </md-input-container>

    <md-input-container class="md-icon-float md-block">
        <label>Адрес</label>
        {!! Form::text('address', null, array('data-ng-address' => 'auth.email', 'required')); !!}
    </md-input-container>

    <md-input-container class="md-icon-float md-block">
        <label>Юридическое лицо</label>
        {!! Form::text('main_contractor', null, array('data-ng-main-contractor' => 'auth.email', 'required')); !!}
    </md-input-container>

    <md-input-container class="md-icon-float md-block">
        <label>Организация</label>
        {!! Form::text('organization', null, array('data-ng-organization' => 'auth.email', 'required')); !!}
    </md-input-container>

@endif
    </div>
<div class="clients-edit-btn">
    {!! Form::submit('Обновить', ['class' => 'btn btn-large btn-success']); !!}
</div>

{!! Form::close() !!}
</div>
            <script>  $(".limitedNumbChosen").chosen({
                    max_selected_options: 2,
                    placeholder_text_multiple: "Which are two of most productive days of your week"
                })
                    .bind("chosen:maxselected", function (){
                        window.alert("You reached your limited number of selections which is 2 selections!");
                    })
                $("input#phone_number").mask("+38(999) 999-99-99");
            </script>
</md-card-content>
</md-card>
</div>
@endsection
