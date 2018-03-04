



			@extends('layouts.master')

			@section('content')

				@if(count($limits))
					<table class="table table-bordered">

					<tr class="first-table-tr">
							<th><span>Название<span data-text="Название статьи затрат"></span></span></th>
							<th><span>Доступный остаток<span data-text="Доступный остаток лимита"></span></span></th>
							<th><span>Лимит<span data-text="Общий лимит статьи затрат"></span></span></th>
							<th><span>Период<span data-text="Период указаный на обноваления лимита"></span></span></th>
						</tr>
						@foreach($limits as $limit)
							<tr>
								@if($limit->limitable_type == 'Money')
									<td>Денежный лимит</td>
									<td data-sum>{{ $limit->current_value }}</td>
									<td data-sum>{{ $limit->limit }}</td>
									<td>{{ $limit->period }}</td>
								@elseif($limit->limitable_type == 'App\CostItem')
									<td>{{ $limit->limitable->name }}</td>
									<td data-sum>{{ $limit->current_value }}</td>
									<td data-sum>{{ $limit->limit }}</td>
									<td >{{ $limit->period }}</td>
								@elseif($limit->limitable_type == 'App\Product')
									<td>{{ $limit->product_name }}</td>
									<td data-sum>{{ $limit->current_value }}</td>
									<td data-sum>{{ $limit->limit }}</td>
									<td>{{ $limit->period }}</td>
								@endif

							</tr>
						@endforeach

					</table>
				@endif


				@can('limits', $client)

						<a class="btn btn-large btn-primary" href="/clients/{{ $client->id }}/limits/set">Задать лимит</a>


		@endcan

		<script>
		for(let i = 0 ; i < $("[data-sum]").length; i++){
				$($("[data-sum]")[i]).text((+$($("[data-sum]")[i]).text()).toFixed(2))
		}
		</script>
		@endsection
