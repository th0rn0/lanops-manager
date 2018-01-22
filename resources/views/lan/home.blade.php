@extends ('layouts.default')

@section ('page_title', $event->display_name . ' - Lans in South Yorkshire')

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h1>Welcome to {{ $event->display_name }}!</h1> 
	</div>
	<div class="text-center">
		<nav class="navbar navbar-default" style="z-index: 1;">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div id="navbar" class="navbar-collapse collapse" style="text-align:center;">
					<ul class="nav navbar-nav" style="display: inline-block; float: none;">
						<!--<li style="font-size:15px; font-weight:bold;"><a href="#food">Food Orders</a></li>-->
						<li style="font-size:15px; font-weight:bold;"><a href="#event">What's on</a></li>
						<li style="font-size:15px; font-weight:bold;"><a href="#seating">Seating</a></li>
						<li style="font-size:15px; font-weight:bold;"><a href="#attendees">Attendees</a></li>
						@if (!$event->tournaments->isEmpty())
							<li style="font-size:15px; font-weight:bold;"><a href="#tournaments">Tournaments</a></li>
						@endif
						<li style="font-size:15px; font-weight:bold;"><a href="#information">Essential Information</a></li>
					</ul>
				</div>
			</div>
		</nav>
	</div>
	<!-- SIGN IN TO EVENT -->
	@if (!$signed_in)
		Please Sign in at the main desk
	@endif
	<!-- FOOD ORDER -->
	<div class="page-header">
		<a name="food"></a>
		<h3>Order some food...</h3>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<h4>Dominos</h4>
			<p>LanOps Recieves a 50% discount on all items (desserts and drinks excluded)</p>
		</div>
	</div>

	<!-- EVENT SPONSORS -->
	@if (!$event->sponsors->isEmpty())
		<div class="page-header">
			<a name="sponsors"></a>
			<h3>{{ $event->display_name }} is sponsored by</h3>
		</div>
		@foreach ($event->sponsors as $sponsor)
			<a href="{{$sponsor->website}}">
				<img class="img-responsive img-rounded" src="{{ $sponsor->image_path }}"/>
			</a>
		@endforeach
	@endif

	<!-- ESSENTIAL INFORMATION -->
	<div class="page-header">
		<a name="information"></a>
		<h3>Essential Information</h3>
	</div>
	<div class="row">
		<div class="col-lg-6 col-md-6 col-xs-12">
			<h4>Venue Address</h4>
			<p>{{ $event->venue->display_name }}</p>
			<address>
				<strong>{{ $event->venue->display_name }}</strong><br>
				@if (trim($event->venue->address_1) != '' || $event->venue->address_1 != null) {{ $event->venue->address_1 }}<br> @endif
				@if (trim($event->venue->address_2) != '' || $event->venue->address_2 != null) {{ $event->venue->address_2 }}<br> @endif
				@if (trim($event->venue->address_street) != '' || $event->venue->address_street != null) {{ $event->venue->address_street }}<br> @endif
				@if (trim($event->venue->address_city) != '' || $event->venue->address_city != null) {{ $event->venue->address_city }}<br> @endif
				@if (trim($event->venue->address_postcode) != '' || $event->venue->address_postcode != null) {{ $event->venue->address_postcode }}<br> @endif
				@if (trim($event->venue->address_country) != '' || $event->venue->address_country != null) {{ $event->venue->address_country }}<br> @endif
			</address>
		</div>
		<div class="col-lg-6 col-md-6 col-xs-12">
			<p>Doors will be locked at 12pm If you need to get in please ring <strong>Storm Seeker</strong></p>
			<p>Dominoes orders will happen throughout the day. 50% off <strong>pizzas and sides ONLY</strong></p>
			<p>Smokers please stay <strong>AWAY</strong> from the doors</p>
			<p><strong><a href="https://www.lanfuel.co.uk">Lan Fuel</a></strong> will be here in the morning to take breakfast orders <strong>last orders 11.30AM</strong></p>
			<p>Server issues speak to <strong>Kayomani</strong></p>
			<p>Website issues speak to <strong>Th0rn0</strong></p>
			<p>Network issues speak to <strong>Rebel</strong></p>
		</div>
	</div>
	
	<!-- TIMETABLE -->
	<div class="page-header">
		<a name="event"></a>
		<h3>Timetable</h3>
	</div>
	@if (!$event->timetables->isEmpty())
		@foreach ($event->timetables as $timetable)
			<table class="table table-striped">
				<thead>
					<th>
						Time
					</th>
					<th>
						Game
					</th>
					<th>
						Description
					</th>
				</thead>
				<tbody>
					@foreach ($timetable->data as $slot)
						@if ($slot->name != NULL && $slot->desc != NULL)
							<tr>
								<td>
									{{ date("D", strtotime($slot->start_time)) }} - {{ date("H:i", strtotime($slot->start_time)) }}
								</td>
								<td>
									{{ $slot->name }}
								</td>
								<td>
									{{ $slot->desc }}
								</td>
							</tr>
						@endif
					@endforeach
				</tbody>
			</table>
		@endforeach
	@endif

	<!-- TOURNAMENTS -->
	@if (!$event->tournaments->isEmpty())
		<div class="row">
			<div class="page-header">
				<a name="tournaments"></a>
				<h3>Tournaments</h3>
			</div>
			@foreach ($event->tournaments as $tournament)
				@if ($tournament->status != 'DRAFT')
					<div class="col-sm-6 col-xs-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4>
									<a href="/events/{{ $event->slug }}/tournaments/{{ $tournament->slug }}">
										{{ $tournament->name }}
									</a>
									<span class="pull-right">
										@if (!$tournament->getParticipant($user->active_event_participant->id))
											<span class="label label-danger">Not Signed up</span>
										@endif
										@if ($tournament->getParticipant($user->active_event_participant->id))
											<span class="label label-success">Signed up</span>
										@endif
									</span>
								</h4>
							</div>
							<div class="panel-body">
								<div class="col-sm-4 col-xs-12">
									@if (isset($tournament->game_cover_image_path))
										<a href="/events/{{ $event->slug }}/tournaments/{{ $tournament->slug }}">
											<img class="img-responsive img-rounded" src="{{ $tournament->game_cover_image_path }}">
										</a>
										<hr>
									@endif
									<a href="/events/{{ $event->slug }}/tournaments/{{ $tournament->slug }}">
										@if (!$tournament->getParticipant($user->active_event_participant->id) && $tournament->status == 'OPEN')
											<button class="btn btn-lg btn-primary">Sign up now</button>
										@else
											<button class="btn btn-lg btn-primary">View Brackets</button>
										@endif
									</a>
								</div>
								<div class="col-sm-8 col-xs-12">
									<h4>
										<strong>
											{{ $tournament->tournamentParticipants->count() }} Signups
										</strong>
									</h4>
									@if ($tournament->status != 'COMPLETE')
										<dl>
											<dt>
												Team Sizes:
											</dt>
											<dd>
												{{ $tournament->team_size }}
											</dd>
											 <dt>
												Game:
											</dt>
											<dd>
												{{ $tournament->game }}
											</dd>
											<dt>
												Format:
											</dt>
											<dd>
												{{ $tournament->format }}
											</dd>
										</dl>
									@endif
									@if ($tournament->status == 'COMPLETE' && isset($tournament->challonge_participants))
										@foreach ($tournament->challonge_participants as $challonge_participant)
											@if ($challonge_participant->final_rank == 1)
												<h2>{{ Helpers::getChallongeRankFormat($challonge_participant->final_rank) }} - {{ $challonge_participant->name }}</h2>
											@endif
											@if ($challonge_participant->final_rank == 2)
												<h3>{{ Helpers::getChallongeRankFormat($challonge_participant->final_rank) }} - {{ $challonge_participant->name }}</h3>
											@endif
											@if ($challonge_participant->final_rank != 2 && $challonge_participant->final_rank != 1)
												<h4>{{ Helpers::getChallongeRankFormat($challonge_participant->final_rank) }} - {{ $challonge_participant->name }}</h4>
											@endif
										@endforeach
										<h4>Signups Closed</h4>
									@endif
								</div>
							</div>
						</div>
					</div>
				@endif
			@endforeach
		</div>
	@endif

	<!-- ATTENDEES -->
	<div class="page-header">
		<a name="attendees"></a>
		<h3>Attendees</h3>
	</div>
	<table class="table table-striped">
		<thead>
			<th width="7%">
			</th>
			<th>
				Steam Name
			</th>
			<th>
				Name
			</th>
			<th>
				Seat
			</th>
		</thead>
		<tbody>
			@foreach ($event->eventParticipants as $participant)
			<tr>
				<td>
					<img class="img-responsive img-rounded" style="max-width: 70%;" src="{{ $participant->user->avatar }}">
				</td>
				<td style="vertical-align: middle;">
					{{ $participant->user->steamname }}
				</td>
				<td style="vertical-align: middle;">
					{{ $participant->user->firstname }}
				</td>
				<td style="vertical-align: middle;">
					@if ($participant->seat)
						{{ $participant->seat->seat }}
					@else
						Not Seated
					@endif 
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	
	<!-- SEATING -->
	@if (!$event->seatingPlans->isEmpty())
		<div class="page-header">
			<a name="seating"></a>
			<h3>Seating Plans <small>- unseatedtickets / total seatable tickets remaining</small></h3>
		</div>
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			@foreach ($event->seatingPlans as $seating_plan)
				<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="headingOne">
						<h4 class="panel-title">
							<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_{{ $seating_plan->slug }}" aria-expanded="true" aria-controls="collapse_{{ $seating_plan->slug }}">
								{{ $seating_plan->name }} <small>- Number of seated seats here</small>
							</a>
						</h4>
					</div>
					<div id="collapse_{{ $seating_plan->slug }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collaspe_{{ $seating_plan->slug }}">
						<div class="panel-body">
							<div class="table-responsive text-center">
								<table class="table">
									<thead>
										<tr>
										<?php
											$headers = explode(',', $seating_plan->headers);
											$headers = array_combine(range(1, count($headers)), $headers);
										?>
										@for ($column = 1; $column <= $seating_plan->columns; $column++)
											<th class="text-center"><h4><strong>ROW {{ucwords($headers[$column])}}</strong></h4></th>
										@endfor
										</tr>
									 </thead>
									<tbody>
										@for ($row = $seating_plan->rows; $row > 0; $row--)
											<tr>
												@for ($column = 1; $column <= $seating_plan->columns; $column++)
													<td style="padding-top:14px;">
														@if ($event->getSeat($seating_plan->id, ucwords($headers[$column]) . $row))
															@if ($seating_plan->locked)
																<button class="btn btn-success btn-sm" disabled>
																	{{ ucwords($headers[$column]) . $row }} - {{ $event->getSeat($seating_plan->id, ucwords($headers[$column] . $row))->eventParticipant->user->steamname }}
																</button>
															@else
																<button class="btn btn-success btn-sm">
																	{{ ucwords($headers[$column]) . $row }} - {{ $event->getSeat($seating_plan->id, ucwords($headers[$column] . $row))->eventParticipant->user->steamname }}
																</button>
															@endif
														@else
															@if ($seating_plan->locked)
																<button class="btn btn-primary btn-sm" disabled>
																	{{ ucwords($headers[$column]) . $row }} - Empty
																</button>
															@else
																@if (Auth::user() && $event->getUser())
																	<button 
																		class="btn btn-primary btn-sm"
																		onclick="pickSeat(
																			'{{ $seating_plan->id }}',
																			'{{ ucwords($headers[$column]) . $row }}'
																		)"
																		data-toggle="modal"
																		data-target="#pickSeatModal"
																	>
																		{{ ucwords($headers[$column]) . $row }} - Empty
																	</button>
																@else
																	<button class="btn btn-primary btn-sm">
																		{{ ucwords($headers[$column]) . $row }} - Empty
																	</button>
																@endif
															@endif
														@endif
													</td>
												@endfor
											</tr>
										@endfor
									</tbody>
								</table>
								@if ($seating_plan->locked)
									<p class="text-center"><strong>NOTE: Seating Plan is current locked!</strong></p>
								@endif
							</div>
							<hr>
							<div class="row" style="display: flex; align-items: center;">
								<div class="col-xs-12 col-md-8">
									<img class="img-responsive" src="{{$seating_plan->image_path}}"/>
								</div>
								<div class="col-xs-12 col-md-4">
									<h5>Your Seats</h5>
									@if ($ticket_flag)
										@foreach ($user->eventParticipation as $participant) 
											@if ($participant->seat && $participant->seat->event_seating_plan_id == $seating_plan->id) 
												{{ Form::open(array('url'=>'/events/' . $event->slug . '/seating/' . $seating_plan->slug)) }}
													{{ Form::hidden('_method', 'DELETE') }}
													{{ Form::hidden('user_id', $user->id, array('id'=>'user_id','class'=>'form-control')) }} 
													{{ Form::hidden('participant_id', $participant->id, array('id'=>'participant_id','class'=>'form-control')) }} 
													{{ Form::hidden('seat_number', $participant->seat->seat, array('id'=>'seat_number','class'=>'form-control')) }} 
													<h5>
														<button class="btn btn-success btn-block"> 
														{{ $participant->seat->seat }} - Remove
														</button>
													</h5>
												{{ Form::close() }} 
											@endif
										@endforeach
									@elseif(Auth::user())
										<div class="alert alert-info">
											<h5>Please Purchase a ticket</h5>
										</div>
									@else
										<div class="alert alert-info">
											<h5>Please Log in to Purchase a ticket</h5>
										</div>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	@endif

	<!-- Image Uploader -->
	<div class="page-header" hidden>
		<a name="image_uploader"></a>
		<h3>Image Uploader</h3>
	</div>
	<div class="row" hidden>
	</div>
	
</div>

@endsection