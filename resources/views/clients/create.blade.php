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
<div style="padding-top: 25px;width: 115%;min-width: calc(50% + 40px);margin: 0 auto;float: none;display: block;" class="col-xs-12 col-md-3 create-edit user-edit">
			{!! Form::open(['route' => 'clients']) !!}
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
										<label>GUID</label>
										{!! Form::text('guid', null, array('data-ng-model-guid' => 'auth.email', 'required')); !!}

								</md-input-container>
								<md-input-container class="md-icon-float md-block">
										<label>Имя</label>
										{!! Form::text('name', null, array('data-ng-model-name' => 'auth.email', 'required')); !!}

								</md-input-container>
								<md-input-container class="md-icon-float md-block">
										<label>Код</label>
										{!! Form::text('code', null, array('data-ng-model-code' => 'auth.email', 'required')); !!}

								</md-input-container>
								@if(Auth::user()->isCompanyAdmin())
								<md-input-container class="md-icon-float md-block">
										<label>Менеджер подразделения</label>
										{!! Form::select('manager_id', $managers, null, array('data-ng-model' => 'auth.email', 'required')); !!}

								</md-input-container>
								@endif
				</md-card-content>
				<md-card-content flex-gt-md="100">
				<md-input-container>
					<label>Начальник подразделения</label>
					<md-select ng-model="selectedVegetables"
									 md-on-close="clearSearchTerm()"
									 data-md-container-class="selectdemoSelectHeader">
					<md-optgroup label="Начальник подразделения">
						@foreach($masters as $id=>$master)
						<md-option value="{{$id}}">{{$master}}</md-option >
						@endforeach
					</md-optgroup>
					</md-select>
				</md-input-container>
</md-card-content>
				<!-- </div> -->
				<md-input-container>
					<label>Вышестоящее подразделение</label>
					<md-select ng-model="selectedVegetables"
					md-on-close="clearSearchTerm()"
					data-md-container-class="selectdemoSelectHeader">
					<md-optgroup label="Вышестоящее подразделение">
						@foreach($ancestors as $id=>$ancestor)
						<md-option value="{{$id}}">{{$ancestor}}</md-option >
							@endforeach
						</md-optgroup>
					</md-select>
				</md-input-container>

    			@if(Auth::user()->isClientAdmin())
	    			<md-input-container>
	    				<label>ID спецификации</label>
	    				<md-select ng-model="selectedVegetables"
	    				md-on-close="clearSearchTerm()"
	    				data-md-container-class="selectdemoSelectHeader">
	    				<md-optgroup label="ID спецификации">
	    					@foreach($specifications as $id=>$specification_id)
	    					<md-option value="{{$id}}">{{$specification_id}}</md-option >
	    						@endforeach
	    					</md-optgroup>
	    				</md-select>
	    			</md-input-container>
              <!-- {!! Form::label('specification_id', 'ID спецификации    '); !!}
        			{!! Form::select('specification_id', $specifications, null, ['class'=>'form-control', 'placeholder' => 'ID спецификации']); !!}</br> -->
                @endif
                <md-input-container class="md-icon-float md-block">
                	<label>Номер телефона</label>
                	{!! Form::text('phone_number', null, array('data-ng-phone-number' => 'auth.email', 'required')); !!}
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
                	<label>Оргинизация</label>
                	{!! Form::text('organization', null, array('data-ng-organization' => 'auth.email', 'required')); !!}
                </md-input-container>
</div>
<md-card-actions layout="row" layout-align="end center" style="justify-content: center; margin-bottom: 15px;">
	<md-button class="md-primary md-raised" type="submit" ng-disabled="authForm.$invalid">Создать </md-button>
</md-card-actions>


</div>
<!-- футер модалки -->

</md-dialog-content>
<md-dialog-actions layout="row">
  <md-button type="submit" class="md-primary md-raised">
    Создать
  </md-button>
</md-dialog-actions>
    {!! Form::close() !!}
</md-dialog>

<!-- END -->
