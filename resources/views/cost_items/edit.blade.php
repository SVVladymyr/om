@extends('layouts.master')

@section('content')
<h1 class="center-h1">Редактирование статьи затрат</h1>

    <div class="col-xs-12 col-md-3 create-edit" style="padding-top: 25px;  width: 100%; min-width: 100%;">

	{!! Form::model($cost_item, ['url' => ['cost_items/update', $cost_item->id]]); !!}

    			{{--{!! Form::label('name', 'name'); !!}--}}
    			{!! Form::text('name', null, ['placeholder'=>'Имя', 'class'=>'form-control']); !!}</br>

    			{!! Form::submit('Обновить', ['class'=>'btn btn-large btn-primary']); !!}

			{!! Form::close() !!}

    </div>
<end-table-cost_items />
@endsection
