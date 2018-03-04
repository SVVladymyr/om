@extends('layouts.master')

@section('content')
<h1 class="center-h1">Спецификации</h1>
    @if($specifications->count())
    <div class="mobile-toogle">

    <table class="table table-bordered">
        <tr class="first-table-tr">
            {{--<th>ID</th>--}}
            <th><span>Имя<span data-text="Название спецификации"></span></span></th>
            <th><span>Открыть спецификацию<span data-text="Открыть всплывающие окно спецификации"></span></span></th>
        </tr>
    @foreach($specifications as $specification)

        @include('specifications.specification')

    @endforeach
    </table>
    </div>
    @endif
    @can('create', App\Specification::class)

        <a class="btn btn-large btn-success mob" href="/specifications/create">Создать</a></br>

    @endcan
    <div class="modal">
        <h2 class="modal-title"></h2>
        <div class="close" onclick="$('.modal').hide()">X</div>
        <div class="modal-content mobile-toogle">

        </div>
    </div>

@endsection
