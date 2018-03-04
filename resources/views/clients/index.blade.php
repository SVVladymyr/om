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
        <section class="md-table-body">
<div class="mobile-toogle">
    @if(count($clients))
<section class="md-table-body">
    <table class="table table-bordered">

          <thead>
            <tr class="first-table-tr">
            <th>Имя
              <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
                <md-icon class="md-ic">&#xE887;</md-icon>
                <md-tooltip>
                Название подразделения
                </md-tooltip>
            </md-button>
            </th>
            <th>
                Номер телефона
                <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
                  <md-icon class="md-ic">&#xE887;</md-icon>
                  <md-tooltip>
                  Номер телефона подразделения
                  </md-tooltip>
              </md-button>
            </th>
            <th>
                Адрес
                <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
                  <md-icon class="md-ic">&#xE887;</md-icon>
                  <md-tooltip>
                  Юридический адрес подразделения
                  </md-tooltip>
              </md-button>
            </th>
            <th></td>
        </tr>
          </thead>

        @foreach($clients as $client)

            @include('clients.client')

        @endforeach

    </table>
  </section>
    @endif

</div>

    @can('create', App\Client::class)
        <md-button class="md-raised md-accent" ng-href="/clients/create" type="submit" >Создать</md-button>
    @endcan
  </section>
  </md-card-content>
</md-card>
</div>
@endsection
