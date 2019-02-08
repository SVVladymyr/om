@extends('layouts.master')

@section('content')

@if(count($limits))
<div ng-controller="limits" class="main-body ng-scope flex" data-ui-view="" data-flex="">
	<md-card class="md-table ng-scope _md">
		<md-card-content>
			<div class="md-table-loader" data-ng-if="!loaded">
				<md-progress-circular md-mode="indeterminate"></md-progress-circular>
			</div>
			<section class="md-table-header">
				<div class="md-table-header-title">
					<span ng-click="toggleRight()" >Лимиты</span>
				</div>
			</section>

			<table class="table table-bordered">

				<tr class="first-table-tr">
					<th><span>Название</span><md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
					<md-icon class="md-ic">&#xE887;</md-icon>
					<md-tooltip>
						Название статьи затрат
					</md-tooltip>
				</md-button></th>
					<th><span>Доступный остаток</span><md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
					<md-icon class="md-ic">&#xE887;</md-icon>
					<md-tooltip>
						Доступный остаток лимита
					</md-tooltip>
				</md-button></th>
					<th><span>Лимит</span>
					<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
					<md-icon class="md-ic">&#xE887;</md-icon>
					<md-tooltip>
						Общий лимит статьи затрат
					</md-tooltip>
				</md-button></th>
					<th><span>Период</span>
					<md-button class="md-button md-icon-button md-ink-ripple md-table-header-filter-btn" data-ng-click="searchUserDialog()">
					<md-icon class="md-ic">&#xE887;</md-icon>
					<md-tooltip>
						Период указаный на обноваления лимита
					</md-tooltip>
				</md-button></th>
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

			<!--<a class="btn btn-large btn-primary" href="/clients/{{ $client->id }}/limits/set">Задать лимит</a> -->
				<md-button class="md-primary md-raised" ng-click="SetLimits($event, {{ $client->id }})">
					Задать лимит
				</md-button>
			@endcan

		</md-card-content>
	</md-card>
</div>

<script>
	for(let i = 0 ; i < $("[data-sum]").length; i++){
		$($("[data-sum]")[i]).text((+$($("[data-sum]")[i]).text()).toFixed(2))
	}
</script>
@endsection
