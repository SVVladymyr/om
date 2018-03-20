<tr>
    <td>{{ $user->email }}</td>
    <td>{{ $user->first_name }}</td>
    <td>{{ $user->last_name }}</td>
    <td>{{ $user->phone_number }}</td>
    @if($user->employer)
        <td><a href="/clients/{{ $user->employer->id }}">{{ $user->employer->name }}</a></td>
    @else
        <td></td>
    @endif
        <td>{{ $user->role->name }}</th>
    @if($user->subject)
        <td>{{ $user->subject->name }}</th>
    @else
        <td></td>
    @endif
    <td style="text-align: center;">
    @can('update', $user)
        @if(Auth::user()->id != $user->id)
        <md-button class="md-primary md-raised" style="right: 0" ng-click="OpenModalShowUser($event, {{ $user->id }})">
          <md-icon class="md-ic">&#xE254;</md-icon>
         </md-button>
        @endif
    @endcan
    </td>
</tr>
