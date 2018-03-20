<!-- шапка модалки -->
<md-dialog aria-label="Test">
    {!! Form::open(['route' => 'users']) !!}
  <md-toolbar>
    <div class="md-toolbar-tools">
      <h2 ng-bind-html="title"></h2>
      <span flex></span>
      <md-button class="md-icon-button" ng-click="cancel()">
        <md-icon md-svg-src="img/icons/ic_close_24px.svg" aria-label="Close dialog"></md-icon>
      </md-button>
    </div>
  </md-toolbar>
  <md-dialog-content>

<!-- END -->
	<div class="user-edit" class="col-xs-12 col-md-3">
  <div style="padding-top: 25px;width: 115%;min-width: calc(50% + 40px);margin: 0 auto;float: none;display: block;" class="col-xs-12 col-md-3 create-edit user-edit">
			{!! Form::open(['route' => 'users']) !!}

				<div class="colum-left">
          <md-input-container class="md-icon-float md-block">
            <label>Имя</label>
            {!! Form::text('first_name', null, array('data-ng-first-name' => 'auth.email', 'required')); !!}
          </md-input-container>

          <md-input-container class="md-icon-float md-block">
            <label>Фамилия</label>
            {!! Form::text('last_name', null, array('data-ng-last-name' => 'auth.email', 'required')); !!}
          </md-input-container>

          <md-input-container class="md-icon-float md-block">
            <label>Номер телефона</label>
            {!! Form::text('phone_number', null, array('data-ng-phone-number' => 'auth.email', 'required')); !!}
          </md-input-container>

          <md-input-container class="md-icon-float md-block">
            <label>E-Mail</label>
            {!! Form::text('email', null, array('data-ng-email' => 'auth.email', 'required')); !!}
          </md-input-container>
				
          <md-input-container class="md-icon-float md-block">
            <label>Пароль</label>
            {!! Form::password('password', null, array('data-ng-password' => 'auth.email', 'required')); !!}
          </md-input-container>

          <md-input-container class="md-icon-float md-block">
            <label>Подтверждение пароля</label>
            {!! Form::password('password_confirmation', null, array('data-ng-password-confirmation' => 'auth.email', 'required')); !!}
          </md-input-container>

          <md-input-container style="display: block; margin-top: 20px">
            <label>Роль</label>
            <md-select ng-model="selectedVegetables"
            md-on-close="clearSearchTerm()"
            data-md-container-class="selectdemoSelectHeader">
            <md-optgroup label="Роль">
              @foreach($roles as $id=>$role_id)
              <md-option value="{{$id}}">{{$role_id}}</md-option >
                @endforeach
              </md-optgroup>
            </md-select>
          </md-input-container>
					@if(Auth::user()->isCompanyAdmin())
            <md-input-container style="display: block; margin-top: 40px">
              <label>Работодатель</label>
              <md-select ng-model="selectedVegetables"
              md-on-close="clearSearchTerm()"
              data-md-container-class="selectdemoSelectHeader">
              <md-optgroup label="Работодатель">
                @foreach($employers as $id=>$employer_id)
                <md-option value="{{$id}}">{{$employer_id}}</md-option >
                  @endforeach
                </md-optgroup>
              </md-select>
            </md-input-container>
					@endif

				
				{!! Form::submit('Создать', ['class'=>'btn btn-large btn-success']); !!}
</div>


    			{!! Form::label('show_price_status', 'Отображать цены'); !!}
    			{!! Form::checkbox('show_price_status', '1', true); !!}</br>


			<!-- {!! Form::close() !!} -->

	</div>
  </div>  

<script>
$.ajax({
		url : '/js/ru.json',
		type: "GET",
		success: function (data) {
				for(let i = 0 ; i < $('#role_id option').length; i++){
					$($('#role_id option')[i]).text(data[$($('#role_id option')[i]).text()])
				}
		}
})

</script>
<!-- футер модалки -->

</md-dialog-content>
<!-- <md-dialog-actions layout="row">
  <md-button type="submit" class="md-primary md-raised">
    Создать
  </md-button>
</md-dialog-actions> -->
    {!! Form::close() !!}
</md-dialog>

<!-- END -->
