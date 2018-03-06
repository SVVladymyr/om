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
            <th><span>Имя</span>
            <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
                <md-icon class="md-ic">&#xE887;</md-icon>
                <md-tooltip>
                    Название спецификации
                </md-tooltip>
            </md-button>
            </th>
            <th><span>Открыть спецификацию</span>
            <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
                <md-icon class="md-ic">&#xE887;</md-icon>
                <md-tooltip>
                    Открыть всплывающие окно спецификации
                </md-tooltip>
            </md-button>
            </th>
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
    @endcan

</section>
</md-card-content>
</md-card>
</div>
@endsection
