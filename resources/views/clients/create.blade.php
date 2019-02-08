<!-- шапка модалки -->
<md-dialog aria-label="Test">
	{!! Form::open(['route' => 'clients']) !!}
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
<div  class="user-edit">
	<div class="colum-left">
			<!-- <div class="colum-left"> -->
				<md-card-content>
								@if(Auth::user()->isCompanyAdmin())
								<md-input-container class="md-icon-float md-block">
										<label>Идентификатор</label>
										{!! Form::number('one_c_id', null, array('data-ng-model-id' => 'auth.email', 'required')); !!}
										<!-- </div> -->
								</md-input-container>
								<md-input-container class="md-icon-float md-block">
										<label>GUID</label>
										{!! Form::text('guid', null, array('data-ng-model-guid' => 'auth.email', 'required')); !!}
								</md-input-container>

								@endif
								<md-input-container class="md-icon-float md-block">
										<label>Имя</label>
										{!! Form::text('name', null, array('data-ng-model-name' => 'auth.email', 'required')); !!}

								</md-input-container>
								<md-input-container class="md-icon-float md-block">
										<label>Код</label>
										{!! Form::text('code', null, array('data-ng-model-code' => 'auth.email', 'required')); !!}

								</md-input-container>
								@if(Auth::user()->isCompanyAdmin())
										<div style="margin-top: 0px; margin-bottom: 27px;">
										{!! Form::label('manager_id', 'Менеджер подразделения'); !!}
										{!! Form::select('manager_id', $managers, null, ['class'=>'form-control', 'placeholder' => 'Пусто']); !!}</br>
										</div>
									@endif

									<div style="margin-top: 0px; margin-bottom: 27px;">
									{!! Form::label('master_id', 'Начальник подразделения'); !!}
									<!--  {!! Form::select('master_id', $masters, null, array('class'=>'form-control','placeholder' => 'Начальник подразделения', 'keydown'=>'filterClientCreate()')); !!}</br>-->

										<select class="limitedNumbChosen form-control" name="master_id" id="master_id">
											<option selected="selected" value="">Начальник подразделения</option>
											@foreach($masters as $id=>$master)
												<option value="{{$id}}">{{$master}}</option>
											@endforeach
										</select>

									</div>
									<div style="margin-top: 0px; margin-bottom: 27px;">
									{!! Form::label('ancestor_id', 'Вышестоящее подразделение'); !!}
									<!--{!! Form::select('ancestor_id', $ancestors, null, array('class'=>'form-control','placeholder' => 'Вышестоящее подразделение')); !!}</br>-->
										<select class="limitedNumbChosen form-control" name="ancestor_id" id="ancestor_id">
											<option selected="selected" value="">Вышестоящее подразделение</option>
											@foreach($ancestors as $id=>$ancestor)
												<option value="{{$id}}">{{$ancestor}}</option>
											@endforeach
										</select>

									</div>

				</md-card-content>
	</div>
	<div class="colum-right">
    			@if(Auth::user()->isClientAdmin())
					<div style="margin-top: 0px; margin-bottom: 27px;">
						{!! Form::label('specification_id', 'ID спецификации    '); !!}
						{!! Form::select('specification_id', $specifications, null, ['class'=>'form-control', 'placeholder' => 'ID спецификации']); !!}

					</div>
					@endif
                <md-input-container class="md-icon-float md-block">
                	<label>Номер телефона</label>
                	{!! Form::text('phone_number', null, array('data-ng-phone-number' => 'auth.email', 'required', 'id'=>'phone_number')); !!}
                </md-input-container>

                <md-input-container class="md-icon-float md-block">
                	<label>Адрес</label>
                	{!! Form::text('address', null, array('data-ng-address' => 'auth.email', 'required')); !!}
                </md-input-container>

                <md-input-container class="md-icon-float md-block">
                	<label>Юридическое лицо</label>
                	{!! Form::text('main_contractor', null, array('data-ng-main-contractor' => 'auth.email', 'required')); !!}
                </md-input-container>

               	<md-input-container class="md-icon-float md-block">
                	<label>Организация</label>
                	{!! Form::text('organization', null, array('data-ng-organization' => 'auth.email', 'required')); !!}
                </md-input-container>
</div>
</div>
<!-- футер модалки -->
</md-dialog-content>
<md-dialog-actions layout="row">
	<md-card-actions layout="row" layout-align="end center" style="justify-content: center; margin-bottom: 15px;">
		<md-button class="md-primary md-raised" type="submit" ng-disabled="authForm.$invalid">Создать </md-button>
	</md-card-actions>
</md-dialog-actions>
	<script>
        $(".limitedNumbChosen").chosen({
            max_selected_options: 2,
            placeholder_text_multiple: "Which are two of most productive days of your week"
        })
            .bind("chosen:maxselected", function (){
                window.alert("You reached your limited number of selections which is 2 selections!");
            })
        $("input#phone_number").mask("+38(999) 999-99-99");
	</script>
    {!! Form::close() !!}
</md-dialog>

<!-- END -->
