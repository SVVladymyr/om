@extends('layouts.master')

@section('content')
    <div ng-controller="user" class="main-body ng-scope flex" data-ui-view="" data-flex="">
    <md-card class="md-table ng-scope _md">
    <md-card-content>
      <div class="md-table-loader" data-ng-if="!loaded">
            <md-progress-circular md-mode="indeterminate"></md-progress-circular>
        </div>
                <section class="md-table-header">
            <div class="md-table-header-title">
                            <span ng-click="toggleRight()" >Пользователи</span>
                        </div>
        </section>
        <section class="md-table-body">
        <div class="mobile-toogle">
        <table class="table table-bordered">
                <tr class="first-table-tr">
                        <th><span>E-mail</span>
                        <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
                    <md-icon class="md-ic">&#xE887;</md-icon>
                    <md-tooltip>
                        E-mail пользователя
                    </md-tooltip>
                </md-button></th>
                        <th><span>Имя</span>
                        <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
                    <md-icon class="md-ic">&#xE887;</md-icon>
                    <md-tooltip>
                        Имя пользователя
                    </md-tooltip>
                </md-button></th>
                        <th><span>Фамилия</span>
                        <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
                    <md-icon class="md-ic">&#xE887;</md-icon>
                    <md-tooltip>
                        Фамилия пользователя
                    </md-tooltip>
                </md-button>
                        </th>
                        <th><span>Номер телефона</span>
                        <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
                    <md-icon class="md-ic">&#xE887;</md-icon>
                    <md-tooltip>
                        Номер телефона пользователя
                    </md-tooltip>
                </md-button>
                        </th>
                        <th><span>Главный</span>
                        <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
                    <md-icon class="md-ic">&#xE887;</md-icon>
                    <md-tooltip>
                        Главный адрес подразделения
                    </md-tooltip>
                </md-button>
                        </th>
                        <th><span>Роль</span>
                        <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
                    <md-icon class="md-ic">&#xE887;</md-icon>
                    <md-tooltip>
                        Роль выставленная у данного пользователя
                    </md-tooltip>
                </md-button>
                        </th>
                        <th><span>Подразделение</span>
                        <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
                    <md-icon class="md-ic">&#xE887;</md-icon>
                    <md-tooltip>
                        Подразделение главного пользователя
                    </md-tooltip>
                </md-button>
                    </th>
                        <th></th>
                </tr>
        @foreach($users as $user)

        @include('users.user')

        @endforeach
        </table>
        </div>
        {{ $users->links() }}

        @can('create', App\User::class)

      <md-button class="md-primary md-raised" ng-click="OpenModalUserCreate($event)">
          Создать пользователя
      </md-button>

        @endcan

        <div class="modal">
            <h2 class="modal-title"></h2>
            <div class="close" onclick="$('.modal').hide()">X</div>
            <div class="modal-content mobile-toogle">

            </div>
        </div>
</section>
</md-card-content>
</md-card>
</div>
@endsection
