<tr>
  <td>{{ $specification->name }}</td>
  <td style="text-align: right;">
  <a  class="btn btn-large btn-warning open-modal-specification" data-id="" href="#">
  	Открыть
  </a>
  <md-button class="md-primary md-raised" ng-click="OpenModalShow($event, {{$specification->id}})">
    Открыть
   </md-button>
</td>
</tr>



{{--/specifications/{{ $specification->id }}--}}
