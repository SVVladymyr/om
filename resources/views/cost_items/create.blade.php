<!-- шапка модалки -->
<md-dialog aria-label="Test">
    {!! Form::open(['route' => 'cost_items']) !!}
  <md-toolbar>
    <div class="md-toolbar-tools">
      <h2 class="center-h1">Создание статьи затрат</h2>
      <span flex></span>
      <md-button class="md-icon-button" ng-click="cancel()">
        <md-icon md-svg-src="img/icons/ic_close_24px.svg" aria-label="Close dialog"></md-icon>
      </md-button>
    </div>
  </md-toolbar>
  <md-dialog-content>

<!-- END -->
<div class="col-xs-12 col-md-3 create-edit" style="padding-top: 10px; width: 100%; min-width: 100%; ">
	     <md-input-container flex="">
          <label>Name</label>
          <input name="name" type="text">
        </md-input-container>
</div>


<!-- футер модалки -->

</md-dialog-content>
<md-dialog-actions layout="row" style="padding: 0 15px;">
  <md-button type="submit" class="md-primary md-raised">
    Создать
  </md-button>
</md-dialog-actions>
    {!! Form::close() !!}
</md-dialog>

<!-- END -->

