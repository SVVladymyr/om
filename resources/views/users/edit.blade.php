

  <!-- шапка модалки -->
      <md-dialog aria-label="Test">
          {!! Form::model($user, ['url' => ['users/update', $user->id]]); !!}
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
                              {!! Form::text('phone_number', null, array('data-ng-phone-number' => 'auth.email', 'required', 'id'=>'phone_number')); !!}
                          </md-input-container>
                      </div>
                  <div class="colum-right">
                          <md-input-container class="md-icon-float md-block">
                              <label>E-Mail</label>
                              {!! Form::text('email', null, array('data-ng-email' => 'auth.email', 'required')); !!}
                          </md-input-container>

                            <div style="margin-top: -18px; margin-bottom: 18px;">
                                {!! Form::label('role_id', 'Роль'); !!}
                                {!! Form::select('role_id', $roles, null, ['class'=>'form-control']); !!}
                            </div>


                          @if(Auth::user()->isCompanyAdmin())
                              <div style="margin-top: -18px; margin-bottom: 18px;">
                          {!! Form::label('employer_id', 'Работодатель'); !!}
                          {!! Form::select('employer_id', $employers, null, ['placeholder' => 'free', 'class'=>'form-control']); !!}</br>
                              </div>
                                  @endif
                                  {!! Form::label('show_price_status', 'Отображать цены'); !!}
                                  {!! Form::checkbox('show_price_status', '1', true); !!}</br>


                      </div>
                          {!! Form::submit('Обновить', ['class'=>'btn btn-large btn-success']); !!}

              </div>

              <script>
                  $("input#phone_number").mask("+38(999) 999-99-99");
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
