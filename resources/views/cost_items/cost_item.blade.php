<div ng-controller="cost-item" class="main-body ng-scope flex" data-ui-view="" data-flex="">
    <md-card class="md-table ng-scope _md">
    <md-card-content>
      <div class="md-table-loader" data-ng-if="!loaded">
            <md-progress-circular md-mode="indeterminate"></md-progress-circular>
        </div>

          <tr>
{{--              <td>{{ $cost_item->id }}</td>--}}
              <td>{{ $cost_item->name }}
               <md-button class="md-primary md-raised" ng-click="EditsItemsCreates($event,'{{ $cost_item->id }}')" style="margin: 0; float: right; right: 0">
               Редактировать
               </md-button>
               </td>
          </tr>
</md-card-content>
</md-card>
</div>
