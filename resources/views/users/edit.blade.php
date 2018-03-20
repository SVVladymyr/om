<!-- шапка модалки -->
<md-dialog aria-label="Test">
  <md-toolbar>
    <div class="md-toolbar-tools">
      <h2 ng-bind-html="title"></h2>
      <span flex></span>
      <md-button class="md-icon-button" ng-click="cancel()">
        <md-icon md-svg-src="img/icons/ic_close_24px.svg" aria-label="Close dialog"></md-icon>
      </md-button>
    </div>
  </md-toolbar>
  <md-dialog-content style="text-align: center;">

<!-- END -->

{!! Form::model($user, ['url' => ['users/update', $user->id]]); !!}

<div  class="colum-left">
    			{!! Form::label('first_name', 'Имя'); !!}
    			{!! Form::text('first_name', null, ['class'=>'form-control', 'placeholder'=>'Имя']); !!}</br>

    			{!! Form::label('last_name', 'Фамилия'); !!}
    			{!! Form::text('last_name', null, ['class'=>'form-control', 'placeholder'=>'Фамилия']); !!}</br>

    			{!! Form::label('phone_number', 'Номер телефона'); !!}
    			{!! Form::text('phone_number', null, ['class'=>'form-control', 'placeholder'=>'Номер телефона']); !!}</br>

    			{!! Form::label('email', 'E-Mail'); !!}
    			{!! Form::text('email', null, ['class'=>'form-control', 'placeholder'=>'E-Mail']); !!}</br>

    			{!! Form::label('role_id', 'Роль'); !!}
                {!! Form::select('role_id', $roles, null, ['class'=>'form-control', 'placeholder'=>'Роль']); !!}</br>

                @if(Auth::user()->isCompanyAdmin())

                    {!! Form::label('employer_id', 'Работодатель'); !!}
                    {!! Form::select('employer_id', $employers, null, ['placeholder' => 'free', 'class'=>'form-control']); !!}</br>
                @endif

				{!! Form::label('show_price_status', 'Отображать цены'); !!}
    			{!! Form::checkbox('show_price_status', '1', true); !!}</br>
</div>
    			{!! Form::submit('Обновить', ['class'=>'btn btn-large btn-success']); !!}

			{!! Form::close() !!}

	</div>
  <end-table-specification />
<!-- футер модалки -->

</md-dialog-content>
<md-dialog-actions layout="row">
</md-dialog-actions>
</md-dialog>

<!-- END -->
