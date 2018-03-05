@extends('layouts.master')

@section('content')
<div ng-controller="cost-item" class="main-body ng-scope flex" data-ui-view="" data-flex="">
    <md-card class="md-table ng-scope _md">
        <md-card-content>
          <div class="md-table-loader" data-ng-if="!loaded">
            <md-progress-circular md-mode="indeterminate"></md-progress-circular>
        </div>
<h1 class="center-h1">Создание статьи затрат</h1>
<div class="col-xs-12 col-md-3 create-edit" style="padding-top: 25px; width: 100%; min-width: 100%; ">
	{!! Form::open(['route' => 'cost_items']) !!}
        {{--{!! Form::label('name', 'name'); !!}--}}
        {!! Form::text('name', null, ['placeholder'=>'Имя', 'class'=>'form-control']); !!}</br>

        {!! Form::submit('Создать', ['class'=>'btn btn-large btn-primary']); !!}
    {!! Form::close() !!}
</div>
<end-table-cost_items />
</md-card-content>
</md-card>
</div>
@endsection
