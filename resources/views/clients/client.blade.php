



    <tr>
{{--        <td>{{ $client->id }}</td>--}}
        <td><a href="/clients/{{ $client->id }}/">{{ $client->name }}</a></td>
        <td>{{ $client->phone_number }}</td>
        <td>{{ $client->address }}</td>
        @if(Auth::user()->isClientAdmin())
          <td style="text-align: right;">
            <a  class="btn btn-large btn-primary" style="margin: 0" href="/clients/{{ $client->id }}/network">Показать структуру сети</a></td>
        @else
          <td style="text-align: right;"><a  class="btn btn-large btn-primary" style="margin: 0" href="/clients/{{ $client->id }}/network">Перейти</a></td>
        @endif
    </tr>
