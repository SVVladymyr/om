<!-- шапка модалки -->
<md-dialog aria-label="Test">
     {!! Form::model($cost_item, ['url' => ['cost_items/update', $cost_item->id]]); !!}
  <md-toolbar>
    <div class="md-toolbar-tools">
      <h2 class="center-h1">Редактирование статьи затрат</h2>
      <span flex></span>
      <md-button class="md-icon-button" ng-click="cancel()">
        <md-icon md-svg-src="img/icons/ic_close_24px.svg" aria-label="Close dialog"></md-icon>
      </md-button>
    </div>
  </md-toolbar>
  <md-dialog-content>

<!-- END -->

    <div class="col-xs-12 col-md-3 create-edit" style="padding-top: 25px;  width: 100%; min-width: 100%;">

          {{--{!! Form::label('name', 'name'); !!}--}}
          {!! Form::text('name', null, ['placeholder'=>'Имя', 'class'=>'form-control']); !!}</br>

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

