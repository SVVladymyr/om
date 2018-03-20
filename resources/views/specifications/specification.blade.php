<tr>
  <td>{{ $specification->name }}</td>
  <td style="text-align: right;">
  <md-button class="md-primary md-raised md-ic" style="right: 0" ng-click="OpenModalShow($event, {{$specification->id}})">
    Открыть
   </md-button>
</td>
</tr>



{{--/specifications/{{ $specification->id }}--}}
