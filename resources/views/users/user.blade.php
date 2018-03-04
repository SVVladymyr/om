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
            <a style="margin: 0px" class="btn btn-large btn-primary edit-togle edit-user-modal" data-id="{{ $user->id }}" href="#">Изменить</a>
        @endif
    @endcan
    </td>
</tr>
