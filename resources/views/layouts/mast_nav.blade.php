<section ng-controller="leftSideBar" class="main" data-layout="column" data-layout-align="space-between stretch">
<md-toolbar class="main-toolbar" data-layout="row">
        <div class="md-toolbar-tools">
          <section class="md-toolbar-tools-left">
					@if(Auth::check())
            <md-button class="md-button md-icon-button md-ink-ripple main-toolbar-btn" data-ng-click="navStatus = !navStatus">
                <md-icon class="md-ic">&#xE5D2;</md-icon>
            </md-button>
						@endif
            <span><img  src="{{ url('images/om24-white.png') }}" alt="om-24"></span>
            </section>
            <section class="md-toolbar-tools-right">
            @if (!Auth::guest() )
                  <span data-ng-init="roleName('{{ Auth::user()->role->name }}')" ng-bind-html="role"></span>
                  <span style="margin-right: 10px; margin-left: 5px;">  ({{ Auth::user()->email }})</span>
                  <md-button style="padding: 5px 0!important;" class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-href="{{ url('logout') }}">
    									<md-icon class="md-ic">&#xE879;</md-icon>
    									<md-tooltip>
    										Выход из панели
    									</md-tooltip>
    							</md-button>
            @endif
            </section>
        </div>
</md-toolbar>

<div class="main-nav-layout" data-ng-class="{'opened': navStatus}" data-ng-click="navStatus = !navStatus"></div>
@if(Auth::check())
<nav class="main-nav" data-ng-class="{'opened': navStatus}">
      <md-list>
				@if(Auth::user()->isCompanyAdmin() || Auth::user()->isManager() || Auth::user()->isClientAdmin() || Auth::user()->isSublevel() || Auth::user()->isConsumer())
				<div>
						<md-divider data-ng-if="item.divider"></md-divider>
						<a href="/orders">
						<md-list-item class="md-2-line" ng-click="null" >
							<md-icon class="md-ic" data-ng-bind-html="item.icon"></md-icon>
								<div class="md-list-item-text" data-layout="column">
									<h3>

											Заказы

									</h3>
								</div>
						</md-list-item>
						</a>
				</div>
				<div>
						<md-divider data-ng-if="item.divider"></md-divider><a href="/clients">
						<md-list-item class="md-2-line" ng-click="null" >
								<md-icon class="md-ic" data-ng-bind-html="item.icon"></md-icon>
								<div class="md-list-item-text" data-layout="column">
									<h3>

											Клиенты

									</h3>
								</div>
						</md-list-item></a>
				</div>
				@endif
				@if(Auth::user()->isCompanyAdmin())
					<div>
              <md-divider data-ng-if="item.divider"></md-divider>
							<a href="/users">
              <md-list-item class="md-2-line" ng-click="null" >
                  <md-icon class="md-ic" data-ng-bind-html="item.icon"></md-icon>
                  <div class="md-list-item-text" data-layout="column"><h3>Пользователи</h3></div>
              </md-list-item>
							</a>
          </div>
				@elseif(Auth::user()->isManager())
					<div>
              <md-divider data-ng-if="item.divider"></md-divider>
							<a href="/specifications">
              <md-list-item class="md-2-line" ng-click="null" >
                  <md-icon class="md-ic" data-ng-bind-html="item.icon"></md-icon>
                  <div class="md-list-item-text" data-layout="column"><h3>Спецификации</h3></div>
              </md-list-item>
							</a>
          </div>
				@elseif(Auth::user()->isClientAdmin())
					<div>
              <md-divider data-ng-if="item.divider"></md-divider>
							<a href="/users">
              <md-list-item class="md-2-line" ng-click="null" >
                  <md-icon class="md-ic" data-ng-bind-html="item.icon"></md-icon>
                  <div class="md-list-item-text" data-layout="column"><h3>Пользователи</h3></div>
              </md-list-item>
							</a>
          </div>
					<div>
							<md-divider data-ng-if="item.divider"></md-divider>
							<a href="/specifications">
							<md-list-item class="md-2-line" ng-click="null" >
									<md-icon class="md-ic" data-ng-bind-html="item.icon"></md-icon>
									<div class="md-list-item-text" data-layout="column"><h3>Спецификации</h3></div>
							</md-list-item>
							</a>
					</div>
					<div>
							<md-divider data-ng-if="item.divider"></md-divider>
							<a href="/cost_items">
							<md-list-item class="md-2-line" ng-click="null" >
									<md-icon class="md-ic" data-ng-bind-html="item.icon"></md-icon>
									<div class="md-list-item-text" data-layout="column"><h3>Статьи затрат</h3></div>
							</md-list-item>
							</a>
					</div>
				@elseif(Auth::user()->isConsumer())
				@if(!!Auth::user()->subject)
				<div>
						<md-divider data-ng-if="item.divider"></md-divider><a href="/clients/{{ Auth::user()->subject->id}}/orders/create">
						<md-list-item class="md-2-line" ng-click="null" >
								<md-icon class="md-ic" data-ng-bind-html="item.icon"></md-icon>
								<div class="md-list-item-text" data-layout="column"><h3>Создать заказ</h3></div>
						</md-list-item></a>
				</div>
				@endif
				@endif
      </md-list>
</nav>
@endif
</section>
<!--	<header>
		<nav class="navbar navbar-default main-menu">
			<a class="navigation" href="{{ url('/home') }}">
				<img src="{{ url('images/om24-white.png') }}" alt="om-24">
			</a>
		</nav>
		 @if(Auth::check())
		@if( Route::getCurrentRoute()->getActionName()  == 'App\Http\Controllers\OrderController@create' || Route::getCurrentRoute()->getActionName()  == 'App\Http\Controllers\OrderController@edit' )
		<div class="create-orders-info">
				<span class="create-orders-info-money-limit-info">Доступный лимит:</span>
				<span class="create-orders-info-money-limit"></span> ||
				Сумма заказа:
				<span class="create-orders-info-money-total">0.00 грн</span>
		</div>
		@endif
		@endif
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-right">
				@if (!Auth::guest() )
					<li class="">
						<span href="#" id="role-nav" class="dropdown-toggle">
							<span></span>
							<span style="margin-right: 10px;">({{ Auth::user()->email }})</span>
							<a class="btn btn-large btn-primary mob-xl logout-btn" style="color: #fff;" href="{{ url('logout') }}">Выход</a>
						</span>
					</li>
				@endif
			</ul>
		</div>
	</header>
    @if(Auth::check())
		<script>
		$.ajax({
				url : '/js/ru.json',
				type: "GET",
				success: function (data) {
						$('#role-nav span:first-child').text(data['{{ Auth::user()->role->name }}'])
				}
		})
		</script>
		<div class="content-left">
			<div class="panel-group left-menu-main" id="accordion">
				@if(Auth::user()->isCompanyAdmin() || Auth::user()->isManager() || Auth::user()->isClientAdmin() || Auth::user()->isSublevel() || Auth::user()->isConsumer())
					<div class="left-menu-item-main">
						<div class="panel-heading">
						<span class="left-menu-text">
							<a href="/orders">
								<i class="icon-menu fa fa-info-circle" aria-hidden="true"></i>
								<p>Заказы</p>
							</a>
						</span>
						</div>
					</div>
					<div class="left-menu-item-main">
						<div class="panel-heading">
						<span class="left-menu-text">
							<a href="/clients">
								<i class="icon-menu fa fa-star" aria-hidden="true"></i>
								<p>Клиенты</p>
							</a>
						</span>
						</div>
					</div>
				@endif
				@if(Auth::user()->isCompanyAdmin())
					<div class="left-menu-item-main">
						<div class="panel-heading">
						<span class="left-menu-text">
							<a href="/users">
								<i class="icon-menu fa fa-user" aria-hidden="true"></i>
								<p>Пользователи</p>
							</a>
						</span>
						</div>
					</div>
				@elseif(Auth::user()->isManager())
					<div class="left-menu-item-main">
						<div class="panel-heading">
							<span class="left-menu-text">
								<a href="/specifications">
									<i class="icon-menu fa fa-files-o" aria-hidden="true"></i>
									<p>Спецификации</p>
								</a>
							</span>
						</div>
					</div>
				@elseif(Auth::user()->isClientAdmin())
					<div class="left-menu-item-main">
						<div class="panel-heading">
							<span class="left-menu-text">
								<a href="/users">
									<i class="icon-menu fa fa-user" aria-hidden="true"></i>
									<p>Пользователи</p>
								</a>
							</span>
						</div>
					</div>
					<div class="left-menu-item-main">
						<div class="panel-heading">
							<span class="left-menu-text">
								<a href="/specifications">
									<i class="icon-menu fa fa-files-o" aria-hidden="true"></i>
									<p>Спецификации</p>
								</a>
							</span>
						</div>
					</div>
					<div class="left-menu-item-main">
						<div class="panel-heading">
							<span class="left-menu-text">
								<a href="/cost_items">
									<i class="icon-menu fa fa-file-text-o" aria-hidden="true"></i>
									<p>Статьи затрат</p>
								</a>
							</span>
						</div>
					</div>
				@elseif(Auth::user()->isConsumer())
				@if(!!Auth::user()->subject)
				<div class="left-menu-item-main">
					<div class="panel-heading">
						<span class="left-menu-text">
								<a href="/clients/{{ Auth::user()->subject->id}}/orders/create">
								<i class="icon-menu fa fa-file-text-o" aria-hidden="true"></i>
								<p>Создать заказ</p>
							</a>
						</span>
					</div>
				</div>
				@endif
				@endif
			</div>
		</div>
    @endif
    @if($flash = session('message'))
		<script>
            $.ajax({
				url : '/js/ru.json',
				type: "GET",
        success: function (data) {
					let translateResponse = data["{{$flash}}"];
					translateResponse != undefined ?  $('.error').text(translateResponse) : $('.error').text("{{$flash}}")
					$('.modal').show()
				}
			})
		</script>
		<div class="modal">
				<h2 class="modal-title"></h2>
				<div class="close" onclick="$('.modal').hide()">X</div>
				<div style="color: #000" class="error modal-content mobile-toogle">

				</div>
		</div>
	@endif
@include('layouts.errors')-->
