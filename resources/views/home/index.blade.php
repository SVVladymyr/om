
@extends('layouts.master')

@section('content')
    <div ng-controller="homeCtrl" class="main-body ng-scope flex" data-ui-view="" data-flex="">
        <md-card class="md-table ng-scope _md">
            <md-card-content>
                <div class="md-table-loader" data-ng-if="!loaded">
                    <md-progress-circular md-mode="indeterminate"></md-progress-circular>
                </div>
                <section class="md-table-header">
                    <div class="md-table-header-title">
                        <span >Домашний каталог</span>
                    </div>
                </section>
                <section class="md-table-body">


    @if(Auth::user()->isClientAdmin())

        {!! Form::open(['route' => 'reports']) !!}
                        <md-datepicker  ng-model="from" md-current-view="year" md-placeholder="Введите дату"></md-datepicker>
                        {!! Form::date('from', \Carbon\Carbon::now()->subMonths(6), ['ng-value' => 'fromL', 'class'=>'hidden']); !!}
                        <md-datepicker  ng-model="to" md-current-view="year" md-placeholder="Введите дату"></md-datepicker>
                        {!! Form::date('to', \Carbon\Carbon::now(), ['ng-value' => 'toL', 'class'=>'hidden']); !!}

                        <md-button style="margin-left: 15px!important;" class="md-primary md-raised" type="submit" ng-click="changedate()">
                            Создать отчёт
                        </md-button>
		{!! Form::close() !!}

	@endif

  @if(Auth::user()->isConsumer())
    <p>Здравствуйте!</p>
    <p>Для создания заказа кликните по значку <md-icon class="md-ic" >&#xE2CC;</md-icon></p>
    <p>
    Вы находитесь на стартовой странице для "Заказчика" системы управления заказами ОМ24. Вернуться на эту страницу вы всегда можете кликнув по логотипу. <span class="img-home-text"><img src="{{ url('images/om24-white.png') }}" alt="om-24"></span>
    </p>
    <p>
      Для перехода к истории заказов кликните на значок <md-icon class="md-ic" >&#xE89B;</md-icon> - левого меню.
    </p>
    <p>Для того что бы посмотреть учетную информацию о своем подразделении кликните на значок <md-icon class="md-ic" >&#xE853;</md-icon> - левого меню.</p>
@else
	@if(Auth::user()->subject)

		@can('create_order', Auth::user()->subject)

        	<a style="margin-top: 15px!important; display:inline-block;" href="/clients/{{ Auth::user()->subject->id }}/orders/create">Создать заказ от имени подразделения</a></br>

    @endcan

    @endif
@endif
                </section>
</md-card-content>
    </md-card >
    </div>
@endsection
