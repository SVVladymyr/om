@extends('layouts.master')

@section('content')
<div ng-controller="specification" class="main-body ng-scope flex" data-ui-view="" data-flex="">
    <md-card class="md-table ng-scope _md">
    <md-card-content>
      <div class="md-table-loader" data-ng-if="!loaded">
            <md-progress-circular md-mode="indeterminate"></md-progress-circular>
        </div>
                <section class="md-table-header">
            <div class="md-table-header-title">
                            <span ng-click="toggleRight()" >Спецификации</span>
                        </div>
        </section>
        <section class="md-table-body">
    @if($specifications->count())
    <div class="mobile-toogle">

    <table class="table table-bordered">
        <tr class="first-table-tr">
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
      <md-button class="md-primary md-raised" ng-click="OpenModalCreate($event)">
          Создать
      </md-button>
        <a class="btn btn-large btn-success mob" href="/specifications/create">Создать</a></br>

    @endcan

</section>
</md-card-content>
</md-card>
</div>
@endsection
