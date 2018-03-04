@extends('layouts.master')

@section('content')
<h1 class="center-h1">Просмотр статьи затрат</h1>


        <h2 style="display: inline-block; margin-right: 15px">{{ $cost_item->name }}</h2>



        @can('update', $cost_item)

            <a  class="btn btn-large btn-warning" href="/cost_items/edit/{{ $cost_item->id }}">Изменить</a>

        @endcan

    	@can('delete', $cost_item)

            <a  class="btn btn-large btn-danger" onclick="deleteItem('/cost_items/delete/{{ $cost_item->id }}')" href="#">Удалить</a>

        @endcan

@endsection
