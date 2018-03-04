order links: </br>
@foreach($affected_client->orders as $order)
<a href="{{ $order->link }}">{{ $order->id }}</a></br>
@endforeach