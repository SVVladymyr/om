@extends('layouts.master')

@section('content')
<div ng-controller="client" class="main-body ng-scope flex" data-ui-view="" data-flex="">
	<md-card class="md-table ng-scope _md">
		<md-card-content>
			<div class="md-table-loader" data-ng-if="!loaded">
				<md-progress-circular md-mode="indeterminate"></md-progress-circular>
			</div>
			<section class="md-table-header">
				<div class="md-table-header-title">
					<span ng-click="toggleRight()" >Сеть клиента</span>
				</div>
			</section>
			<section class="md-table-body clients-network">
				<div class="mobile-toogle">

					<div class="table main-table-specification">
						<div class="main-colum">
							<div class="main-colum-item">
								Название
							</div>
							<div class="main-colum-item">
								Лимит
							</div>
							<div class="main-colum-item">
								Спецификация
							</div>
						</div>
						@foreach($clients as $client)
						<?php // var_dump($client) ?>
						@if($client->ancestor_id == null ? true : $clients[0]->ancestor_id != null && $client->ancestor_id == $client->root_id)
						<div class="items-colum-specification">
							<div class="items-colum-specification-item">
								<span data-open-id="{{$client->id}}" class="open-list">+</span>
								<a  href="/clients/{{ $client->id }}">{{ $client->name }}</a>
							</div>
							<div class="items-colum-specification-item">
								@can('limits', $client)
								<md-button class="md-primary md-raised" ng-click="SetLimits($event, {{ $client->id }})">
									Установить лимит
								</md-button>
								@endcan
							</div>
							@if($client->specification)
							<div class="items-colum-specification-item">
								@can('view', $client->specification)
								<a href="/specifications/{{ $client->specification->id }}">{{ $client->specification->name }}</a>
								@endcan
							</div>
							<div class="items-colum-specification-item">
								@can('update', $client)
								<a class="btn btn-large btn-primary" style="margin: 0;" href="/clients/edit/{{$client->id}}">Редактировать</a>
								@endcan
							</div>
							<div class="items-colum-specification-item">
								@can('view', $client->specification)
								<a class="btn btn-large btn-primary" style="margin: 0;" href="/specifications/edit/{{ $client->specification->id }}">Редактировать спецификацию</a>
								@endcan
							</div>
							@else
							<div class="items-colum-specification-item">
								Не установленно
							</div>
							<div class="items-colum-specification-item">
								@can('update', $client)
								<a class="btn btn-large btn-primary" style="margin: 0;" href="/clients/edit/{{$client->id}}">Редактировать</a>

								@endcan
							</div>
							<div class="items-colum-specification-item">
							</div>
							@endif
							<ul>
								@foreach($clients as $clientTwo)
								@if($client->id == $clientTwo->ancestor_id && $client->name != $clientTwo->name)
								<li>
									<div class="items-colum-specification">
										<div class="items-colum-specification-item">
											<span data-open-id="{{$clientTwo->id}}" class="open-list">+</span>
											<a  href="/clients/{{ $clientTwo->id }}">{{ $clientTwo->name }}</a>
										</div>
										<div class="items-colum-specification-item">
											@can('limits', $clientTwo)
											<md-button class="md-primary md-raised" ng-click="SetLimits($event, {{ $clientTwo->id }})">
								          Установить лимит
								      </md-button>
											@endcan
										</div>
										@if($clientTwo->specification)
										<div class="items-colum-specification-item">
											@can('view', $clientTwo->specification)
											<a href="/specifications/{{ $clientTwo->specification->id }}">{{ $clientTwo->specification->name }}</a>
											@endcan
										</div>
										<div class="items-colum-specification-item">
											@can('update', $clientTwo)
											<a class="btn btn-large btn-primary" style="margin: 0;" href="/clients/edit/{{$clientTwo->id}}">Редактировать</a>
											@endcan
										</div>
										<div class="items-colum-specification-item">
											@can('view', $clientTwo->specification)
											<a class="btn btn-large btn-primary" style="margin: 0;" href="/specifications/edit/{{ $clientTwo->specification->id }}">Редактировать спецификацию</a>
											@endcan
										</div>
										@else
										<div class="items-colum-specification-item">
											Не установленно
										</div>
										<div class="items-colum-specification-item">
											@can('update', $clientTwo)
											<a class="btn btn-large btn-primary" style="margin: 0;" href="/clients/edit/{{$clientTwo->id}}">Редактировать</a>
											@endcan
										</div>
										<div class="items-colum-specification-item">
										</div>
										@endif
										<ul>
											@foreach($clients as $clientThree)
											@if($clientTwo->id == $clientThree->ancestor_id)
											<li>
												<div class="items-colum-specification">
													<div class="items-colum-specification-item">
														<span  data-open-id="{{$clientThree->id}}" class="open-list">+</span>
														<a  href="/clients/{{ $clientThree->id }}">{{ $clientThree->name }}</a>
													</div>
													<div class="items-colum-specification-item">
														@can('limits', $clientThree)
														<md-button class="md-primary md-raised" ng-click="SetLimits($event, {{ $clientThree->id }})">
															Установить лимит
														</md-button>
														@endcan
													</div>
													@if($clientThree->specification)
													<div class="items-colum-specification-item">

														@can('view', $clientThree->specification)
														<a href="/specifications/{{ $clientThree->specification->id }}">{{ $clientThree->specification->name }}</a>
														@endcan

													</div>
													<div class="items-colum-specification-item">
														@can('update', $clientThree)
														<a class="btn btn-large btn-primary" style="margin: 0;" href="/clients/edit/{{$clientThree->id}}">Редактировать</a>
														@endcan
													</div>
													<div class="items-colum-specification-item">
														@can('view', $clientThree->specification)
														<a class="btn btn-large btn-primary" style="margin: 0;" href="/specifications/edit/{{ $clientThree->specification->id }}">Редактировать спецификацию</a>
														@endcan

													</div>
													@else
													<div class="items-colum-specification-item">
														Не установленно
													</div>
													<div class="items-colum-specification-item">
														@can('update', $clientThree)
														<a class="btn btn-large btn-primary" style="margin: 0;" href="/clients/edit/{{$clientThree->id}}">Редактировать</a>
														@endcan
													</div>
													<div class="items-colum-specification-item">
													</div>
													@endif
													<ul>
														@foreach($clients as $clientFour)
														@if($clientThree->id == $clientFour->ancestor_id)
														<li>
															<div class="items-colum-specification">
																<div class="items-colum-specification-item">
																	<span  data-open-id="{{$clientFour->id}}" class="open-list">+</span>
																	<a  href="/clients/{{ $clientFour->id }}">{{ $clientFour->name }}</a>
																</div>
																<div class="items-colum-specification-item">
																	@can('limits', $clientFour)
																	<md-button class="md-primary md-raised" ng-click="SetLimits($event, {{ $clientFour->id }})">
																		Установить лимит
																	</md-button>
																	@endcan
																</div>
																@if($clientFour->specification)
																<div class="items-colum-specification-item">

																	@can('view', $clientFour->specification)
																	<a href="/specifications/{{ $clientFour->specification->id }}">{{ $clientFour->specification->name }}</a>
																	@endcan

																</div>
																<div class="items-colum-specification-item">
																	@can('update', $clientFour)
																	<a class="btn btn-large btn-primary" style="margin: 0;" href="/clients/edit/{{$clientFour->id}}">Редактировать</a>
																	@endcan
																</div>
																<div class="items-colum-specification-item">
																	@can('view', $clientFour->specification)
																	<a class="btn btn-large btn-primary" style="margin: 0;" href="/specifications/edit/{{ $clientFour->specification->id }}">Редактировать спецификацию</a>
																	@endcan

																</div>
																@else
																<div class="items-colum-specification-item">
																	Не установленно
																</div>
																<div class="items-colum-specification-item">
																	@can('update', $clientFour)
																	<a class="btn btn-large btn-primary" style="margin: 0;" href="/clients/edit/{{$clientFour->id}}">Редактировать</a>
																	@endcan
																</div>
																<div class="items-colum-specification-item">
																</div>
																@endif
																<ul>
																	@foreach($clients as $clientFive)
																	@if($clientFour->id == $clientFive->ancestor_id)
																	<li>
																		<div class="items-colum-specification">
																			<div class="items-colum-specification-item">
																				<span  data-open-id="{{$clientFive->id}}" class="open-list">+</span>
																				<a  href="/clients/{{ $clientFive->id }}">{{ $clientFive->name }}</a>
																			</div>
																			<div class="items-colum-specification-item">
																				@can('limits', $client)
																				<md-button class="md-primary md-raised" ng-click="SetLimits($event, {{ $clientFive->id }})">
																					Установить лимит
																				</md-button>
																				@endcan
																			</div>
																			@if($client->specification)
																			<div class="items-colum-specification-item">

																				@can('view', $clientFive->specification)
																				<a href="/specifications/{{ $clientFive->specification->id }}">{{ $clientFive->specification->name }}</a>
																				@endcan

																			</div>
																			<div class="items-colum-specification-item">
																				@can('update', $clientFive)
																				<a class="btn btn-large btn-primary" style="margin: 0;" href="/clients/edit/{{$clientFive->id}}">Редактировать</a>
																				@endcan
																			</div>
																			<div class="items-colum-specification-item">
																				@can('view', $clientFive->specification)
																				<a class="btn btn-large btn-primary" style="margin: 0;" href="/specifications/edit/{{ $clientFive->specification->id }}">Редактировать спецификацию</a>
																				@endcan

																			</div>
																			@else
																			<div class="items-colum-specification-item">
																				Не установленно
																			</div>
																			<div class="items-colum-specification-item">
																				@can('update', $clientFive)
																				<a class="btn btn-large btn-primary" style="margin: 0;" href="/clients/edit/{{$clientFive->id}}">Редактировать</a>
																				@endcan
																			</div>
																			<div class="items-colum-specification-item">
																			</div>
																			@endif
																			<ul>
																				@foreach($clients as $clientSix)
																				@if($clientFive->id == $clientSix->ancestor_id)
																				<li>
																					<div class="items-colum-specification">
																						<div class="items-colum-specification-item">
																							<span  data-open-id="{{$clientSix->id}}" class="open-list">+</span>
																							<a  href="/clients/{{ $clientSix->id }}">{{ $clientSix->name }}</a>
																						</div>
																						<div class="items-colum-specification-item">
																							@can('limits', $clientSix)
																							<md-button class="md-primary md-raised" ng-click="SetLimits($event, {{ $clientSix->id }})">
																								Установить лимит
																							</md-button>
																							@endcan
																						</div>
																						@if($client->specification)
																						<div class="items-colum-specification-item">
																							@can('view', $clientSix->specification)
																							<a href="/specifications/{{ $clientSix->specification->id }}">{{ $clientSix->specification->name }}</a>
																							@endcan
																						</div>
																						<div class="items-colum-specification-item">
																							@can('update', $clientSix)
																							<a class="btn btn-large btn-primary" style="margin: 0;" href="/clients/edit/{{$clientSix->id}}">Редактировать</a>
																							@endcan
																						</div>
																						<div class="items-colum-specification-item">
																							@can('view', $clientSix->specification)
																							<a class="btn btn-large btn-primary" style="margin: 0;" href="/specifications/edit/{{ $clientSix->specification->id }}">Редактировать спецификацию</a>
																							@endcan
																						</div>
																						@else
																						<div class="items-colum-specification-item">
																							Не установленно
																						</div>
																						<div class="items-colum-specification-item">
																							@can('update', $clientSix)
																							<a class="btn btn-large btn-primary" style="margin: 0;" href="/clients/edit/{{$clientSix->id}}">Редактировать</a>
																							@endcan
																						</div>
																						<div class="items-colum-specification-item">
																						</div>
																						@endif
																					</div>
																				</li>
																				@endif
																				@endforeach
																			</ul>
																		</div>
																	</li>
																	@endif
																	@endforeach
																</ul>
															</div>
														</li>
														@endif
														@endforeach
													</ul>
												</div>
											</li>
											@endif
											@endforeach
										</ul>
									</div>
								</li>
								@endif
								@endforeach
							</ul>
						</div>
						@endif
						@endforeach
					</div>
				</div>
				<div class="modal">
					<h2 class="modal-title"></h2>
					<div class="close" onclick="$('.modal').hide()">X</div>
					<div class="modal-content mobile-toogle">

					</div>
				</div>
			</section>
		</md-card-content>
	</md-card>
</div>
@endsection
