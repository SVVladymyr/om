@extends('layouts.master')
{{--{{dump(session('status'))}}--}}

@section('content')
    <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="right">
        <md-toolbar class="md-theme-light">
            <h1 class="md-toolbar-tools">Поиск по пользователям</h1>
        </md-toolbar>
        <md-content ng-controller="RightUserCtrl" layout-padding="10">
            <form id="filter_users" action="" method="get">
            <md-card-content flex-gt-md="100">
                <md-input-container class="md-icon-float md-block">
                    <label>Email</label>
                    <input type="text"  data-ng-first-name="email" name="email" value="{{Request::get('email')}}">
                </md-input-container>
                <md-input-container class="md-icon-float md-block">
                    <label>Главный</label>
                    <input type="text"   data-ng-first-name="main" name="main" value="{{Request::get('main')}}">
                </md-input-container>
                @if(!empty($_GET['role']))
                @if($_GET['role']=='company_admin')
                    <input ng-init="Roles[0].check = true" hidden type="text">
                @elseif($_GET['role']=='client_admin')
                    <input ng-init="Roles[1].check = true" hidden type="text">
                @elseif($_GET['role']=='sublevel')
                    <input ng-init="Roles[2].check = true" hidden type="text">
                @elseif($_GET['role']=='manager')
                    <input ng-init="Roles[3].check = true" hidden type="text">
                @elseif($_GET['role']=='consumer')
                    <input ng-init="Roles[4].check = true" hidden type="text">
                @endif
                @endif
                <md-input-container style="width: 100%;">
                    <label>Роль</label>
                    <md-select ng-model="selectedRoles"
                                md-on-close="clearSearchTerm()"
                                data-md-container-class="selectdemoSelectHeader">
                        <md-option ng-repeat="item in Roles"  ng-selected="'@{{ item.check }}'"  ng-value="'@{{ item.val }}'">@{{ item.name }}</md-option>

                    </md-select>
                </md-input-container>
                <input type="checkbox" style="display: none;" checked name="role" value="@{{ selectedRoles }}">
                <md-input-container class="md-icon-float md-block">
                    <label>Подразделение</label>
                    <input type="text"  data-ng-first-name="subdivision" name="subdivision" value="{{Request::get('subdivision')}}">
                </md-input-container>

                    <!--<input type="text" placeholder="role" name="role" value="{{Request::get('role')}}"> -->

            <md-button class="md-primary md-raised" type="submit" ng-click="changedate()">
                Поиск
            </md-button>
                <md-button class="md-primary md-raised" type="button" ng-click="clearUsersFilter()">
                    Очистить
                </md-button>
            </form>
        </md-content>
    </md-sidenav>
    <div ng-controller="user" class="main-body ng-scope flex" data-ui-view="" data-flex="">
    <md-card class="md-table ng-scope _md">
    <md-card-content>
      <div class="md-table-loader" data-ng-if="!loaded">
            <md-progress-circular md-mode="indeterminate"></md-progress-circular>
        </div>
                <section class="md-table-header">
            <div class="md-table-header-title">
                            <span ng-click="toggleRight()" >Пользователи</span>
                <md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" ng-click="toggleRight()">
                    <md-icon class="md-ic">&#xE152;</md-icon>
                    <md-tooltip>
                        Открыть фильтра
                    </md-tooltip>
                </md-button>
                        </div>
        </section>
        <section class="md-table-body">
            @if(!empty($_GET))
                <p>Активные фильтра</p>
            @endif
            <ul class="list-inline">
                @if(!empty($_GET['email']))
                    <div class="list-wrap"><span>Email:</span><li class="list-inline-item">{{$_GET['email']}}</li></div>
                @endif
                @if(!empty($_GET['main']))
                    <div class="list-wrap"><span>Главный:</span><li class="list-inline-item">{{$_GET['main']}}</li></div>
                @endif
                @if(!empty($_GET['subdivision']))
                    <div class="list-wrap"><span>Подразделение:</span><li class="list-inline-item">{{$_GET['subdivision']}}</li></div>
                @endif
                @if(!empty($_GET['role']))
                    <div class="list-wrap"><span>Роль:</span>
                        <li class="list-inline-item">
                            @if($_GET['role']=='company_admin')
                                Администратор компании
                            @elseif($_GET['role']=='client_admin')
                                Администратор клиента
                            @elseif($_GET['role']=='sublevel')
                                Подуровень
                            @elseif($_GET['role']=='manager')
                                Менеджер
                            @elseif($_GET['role']=='consumer')
                                Заказчик
                            @endif
                        </li></div>
                @endif
            </ul>
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
