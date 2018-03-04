@extends('layouts.master')

@section('content')

    <style>
        .error {
            width: 100%;
            margin-top: 0px;
            margin-bottom: 0px;
        }
        .hamburger{
          display: none!important;
        }
    </style>
    <div class="main-auth">
      <md-card class="main-auth-container">
        <md-tabs md-dynamic-height md-border-bottom>
           <md-tab label="Вход">
             <md-content class="md-padding">
               {!! Form::open(['route' => 'login']) !!}
                   <md-card-content>
                           <md-input-container class="md-icon-float md-block">
                               <label>E-mail</label>
                               <md-icon class="md-ic">&#xE7FD;</md-icon>
                               {!! Form::text('email', null, array('data-ng-model' => 'auth.email', 'required')); !!}
                               <div ng-messages="authForm.login.$error">
                               </div>
                           </md-input-container>
                           <md-input-container class="md-icon-float md-block">
                             <label>Пароль</label>
                               <md-icon class="md-ic">&#xE897;</md-icon>
                                {!! Form::password('password', array('required', 'data-ng-model' => 'auth.password')); !!}
                               <div data-ng-messages="authForm.password.$error">
                                   <div data-ng-message="required" ng-show="authForm.password.$error.required">
                                   </div>
                               </div>

                           </md-input-container>
                   </md-card-content>
                   <md-card-actions layout="row" layout-align="end center">
                     <md-button class="md-raised md-accent" type="submit" ng-disabled="authForm.$invalid">Вход</md-button>
                 </md-card-actions>
               {!! Form::close() !!}
               </md-content>
           </md-tab>
           <md-tab label="Регистрация">
           <md-content class="md-padding">
             <form name="registerForm" novalidate data-ng-submit="register()">
                 <md-card-content>
                         <md-input-container class="md-icon-float md-block">
                             <label>E-mail</label>
                             <md-icon class="md-ic">&#xE7FD;</md-icon>
                              {!! Form::text('email', null, array('data-ng-model' => 'auth.email', 'required')) !!}
                         </md-input-container>
                         <md-input-container class="md-icon-float md-block">
                             <label>Телефон</label>
                             <md-icon class="md-ic">&#xE7FD;</md-icon>
                             {!! Form::text('phone_number',  null, array('data-ng-model' => 'auth.phone', 'required')) !!}
                         </md-input-container>
                         <md-input-container class="md-icon-float md-block">
                             <label>Имя</label>
                             <md-icon class="md-ic">&#xE7FD;</md-icon>
                             {!! Form::text('name',  null, array('data-ng-model' => 'auth.phone', 'required')) !!}
                         </md-input-container>
                 </md-card-content>
                 <md-card-actions layout="row" layout-align="end center">
                     <md-button class="md-raised md-accent" type="submit" ng-disabled="authForm.$invalid">Регистрации</md-button>
                 </md-card-actions>
             </form>
             </md-content>
         </md-tab>
         </md-tabs>
      </md-card>
  </div>

<!--
    <div class="authorization">
        <div class="container-fluid authorization-container">
            <div class="row">
                <h1 style="cursor: pointer" onclick="$('#login').hide();$('#register').show();"   class="col-xs-6 authorization-container-title">Регистрация</h1>
                <h1 style="cursor: pointer"  onclick="$('#login').show();$('#register').hide();" class="col-xs-6 authorization-container-title tabs-active">Войти в кабинет</h1>

                <div class="authorization-container-content">
                    <div class="col-xs-12 col-md-8">
                        <h2 class="text-center">
                            <b>Преимущества</b>
                        </h2>
                        <br><p>Зарегистрируйтесь в системе OM24.com.ua у менеджера компании и
                            получите возможность заказывать продукцию быстро и эффективно.</p>
                        <ul>
                            <li>Уникальная возможность совершить заказ в несколько кликов как индивидуальному, так и сетевому клиенту.</li>
                            <li>Контроль и корректива заказов на разных уровнях управления.</li>
                            <li>Формирование заказов в рамках установленных лимитов.</li>
                            <li>Развернутая аналитика.</li>
                            <li>Прием заказов 24/7 с компьютера и мобильного телефона.</li>
                        </ul>
                        <p>OM24.com.ua - автоматизированная on-line система обслуживания клиентов предприятия
                            <a href="https://officem.com.ua/" style="color: #000; text-decoration: underline;">OOO 'ОфисМенеджер'</a>
                        </p>
                    </div>
                    <div id="login" class="col-xs-12 col-md-4">
                        {!! Form::open(['route' => 'login']) !!}
                        <h6>Для входа требуется ввести email и пароль</h2>
                        {{--{!! Form::label('email', 'E-Mail'); !!}--}}
                            <div class="col-sm-12">
                                {!! Form::text('email', null, array('class'=>'form-control','placeholder' => 'E-Mail')); !!}</br>
                            </div>
                            <div class="col-sm-12">
                                {!! Form::password('password', array('class'=>'form-control', 'placeholder' => 'Пароль')); !!}</br>
                            </div>
                            <div class="col-sm-12">
                                <div class="checkbox authorization-container-checkbox">
                                    {!! Form::checkbox('remember', 'value'); !!}
                                    {!! Form::label('remember', 'Запомнить меня'); !!}
                                </div>
                            </div>
                            <div class="col-sm-12">
                                {!! Form::submit('Войти', array('class'=>'btn btn-primary authorization-container-submit')); !!}
                            </div>
                        {!! Form::close() !!}
                        <div class="col-sm-12" style="text-align: right; margin: 25px 0;">
                        <a href="{{ route('password_reset') }}">Забыли пароль?</a>
                        </div>
                        {{--<ul style="width: calc(100% - 45px);" class="list-group list-unstyled">--}}
                            {{--<li style="border:none" class="list-group-item text-danger">@include('layouts.errors')</li>--}}
                        {{--</ul>--}}
                    </div>
                    <div style="display: none;" id="register" class="col-xs-12 col-md-4">
                        {!! Form::open(['route' => 'request']) !!}
                        <h6>Для регистрации требуется ввести email, телефон и Ваше имя</h6>
                        <div style="margin-bottom: 24px;" class="col-sm-12">
                        {!! Form::text('email', null, array('class'=>'form-control','placeholder' => 'E-Mail')) !!}
                        </div>
                        <div style="margin-bottom: 24px;" class="col-sm-12">
                        {!! Form::text('phone_number',  null, array('class'=>'form-control','placeholder' => 'Телефон')) !!}
                        </div>
                        <div style="margin-bottom: 24px;" class="col-sm-12">
                        {!! Form::text('name',  null, array('class'=>'form-control','placeholder' => 'Имя')) !!}
                        </div>
                        {!! Form::checkbox('aks', 'value'); !!}
                        <a id="user-ask" href="#">Пользовательское соглашения</a>
                        <div style="margin-bottom: 24px;" class="col-sm-12">
                        {!! Form::submit('Зарегистрироваться', array('class'=>'btn btn-primary authorization-container-submit')) !!}
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal">
        <h2 class="modal-title"></h2>
        <div class="close" onclick="$('.modal').hide()">X</div>
        <div class="modal-content mobile-toogle">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </div>
    </div> -->
@endsection
