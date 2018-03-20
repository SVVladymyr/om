
@extends('layouts.master')

@section('content')
    <div class="home-toogle">
        <p>Домашний каталог</p>


    @if(Auth::user()->isClientAdmin())

        {!! Form::open(['route' => 'reports']) !!}

			{!! Form::date('from', \Carbon\Carbon::now()->subMonths(6)); !!}
			{!! Form::date('to', \Carbon\Carbon::now()); !!}

		{!! Form::submit('Создать отчёт'); !!}

		{!! Form::close() !!}

	@endif

    </div>
  @if(Auth::user()->isConsumer())
    <p>Здравствуйте!</p>
    <p>Для создания заказа кликните по значку <i class="icon-menu fa fa-file-text-o" aria-hidden="true"></i></p>
    <p>
    Вы находитесь на стартовой странице для "Заказчика" системы управления заказами ОМ24. Вернуться на эту страницу вы всегда можете кликнув по логотипу. <span class="img-home-text"><img src="/om24-white.png" alt="om-24"></span>
    </p>
    <p>
      Для перехода к истории заказов кликните на значок <i class="icon-menu fa fa-info-circle" aria-hidden="true"></i> - левого меню.
    </p>
    <p>Для того что бы посмотреть учетную информацию о своем подразделении кликните на значок <i class="icon-menu fa fa-star" aria-hidden="true"></i> - левого меню.</p>
@else
	@if(Auth::user()->subject)

		@can('create_order', Auth::user()->subject)

        	<a href="/clients/{{ Auth::user()->subject->id }}/orders/create">Создать заказ от имени подразделения</a></br>

    @endcan

    @endif
@endif
@endsection
