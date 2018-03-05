<div ng-controller="cost-item" class="main-body ng-scope flex" data-ui-view="" data-flex="">
    <md-card class="md-table ng-scope _md">
    <md-card-content>
      <div class="md-table-loader" data-ng-if="!loaded">
            <md-progress-circular md-mode="indeterminate"></md-progress-circular>
        </div>

          <tr>
{{--              <td>{{ $cost_item->id }}</td>--}}
              <td>{{ $cost_item->name }}
               <a data-id="{{ $cost_item->id }}" class="btn btn-large btn-primary edit-togle open-modal-cost-item-edit" style="margin: 0; float: right;" href="#"></a>
               <md-button class="md-primary md-raised" ng-click="EditsItemsCreates($event,'{{ $cost_item->id }}')" style="margin: 0; float: right;">
               Создать
               </md-button>
               </td>
          </tr>
</md-card-content>
</md-card>
</div>
