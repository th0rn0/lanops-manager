@extends ('layouts.default')

@section ('page_title',  Settings::getOrgName() . ' - ' . $event->display_name)

@section ('content')

<div class="container">

	<div class="page-header">
		<h1>
			{{$event->display_name}}
			@if ($event->status != 'PUBLISHED')
				<small> - {{ $event->status }}</small>
			@endif
		</h1> 
		<h4>{!! $event->desc_short !!}</h4>
	</div>
	<div class="text-center">
		<nav class="navbar navbar-default navbar-events" style="z-index: 1;">
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
						<li style="font-size:15px; font-weight:bold;"><a href="#event">@lang('events.eventinfo')</a></li>
						<li style="font-size:15px; font-weight:bold;"><a href="#purchaseTickets">@lang('events.tickets')</a></li>
						@if (!$event->sponsors->isEmpty())
							<li style="font-size:15px; font-weight:bold;"><a href="#sponsors">@lang('events.sponsors')</a></li>
						@endif
						@if (!$event->seatingPlans->isEmpty() && (in_array('PUBLISHED', $event->seatingPlans->pluck('status')->toArray()) || in_array('PREVIEW', $event->seatingPlans->pluck('status')->toArray()))	)
							<li style="font-size:15px; font-weight:bold;"><a href="#seating">@lang('events.seating')</a></li>
						@endif
						<li style="font-size:15px; font-weight:bold;"><a href="#attendees">@lang('events.attendees')</a></li>
						@if (!$event->tournaments->isEmpty() && config('challonge.api_key') != null)
							<li style="font-size:15px; font-weight:bold;"><a href="#tournaments">@lang('events.tournaments')</a></li>
						@endif
						@if (!$event->timetables->isEmpty())
							<li style="font-size:15px; font-weight:bold;"><a href="#timetable">@lang('events.timetable')</a></li>
						@endif
						<li style="font-size:15px; font-weight:bold;"><a href="#yourTickets">@lang('events.yourtickets')</a></li>
						@if (!$event->polls->isEmpty())
							<li style="font-size:15px; font-weight:bold;"><a href="#polls">@lang('events.haveyoursay')</a></li>
						@endif
						
					</ul>
				</div><!--/.nav-collapse -->
			</div><!--/.container-fluid -->
		</nav>
		<div class="row">
			<div class="col-xs-12">
				<h3>
					<strong>{{ max($event->capacity - $event->eventParticipants->count(), 0) }}/{{ $event->capacity }}</strong>@lang('events.ticketsavailable')
				</h3>
			</div>
			@if ($event->capacity > 0)
				<div class="col-xs-12">
					<div class="progress">
						<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="{{ ($event->eventParticipants->count() / $event->capacity) * 100}}" aria-valuemin="0" aria-valuemax="100" style="width: {{ ($event->eventParticipants->count() / $event->capacity) * 100}}%;">
							@lang('events.purchased')
						</div>
						<div class="progress-bar progress-bar-success" style="width: {{ 100 - ($event->eventParticipants->count() / $event->capacity) * 100}}%;">
							<span class="sr-only">@lang('events.available')</span>
							@lang('events.available')
						</div>
					</div>
				</div>
			@endif
		</div>

	</div>

	<div class="row">
		<!-- EVENT INFORMATION -->
		<div class="col-md-12">
			<div class="page-header">
				<a name="event"></a>
				<h3>@lang('events.eventinfo')</h3>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-5">
					<p class="bg-success  padding">@lang('events.start'): {{ date('H:i d-m-Y', strtotime($event->start)) }}</p>
					<p class="bg-danger  padding">@lang('events.end'): {{ date('H:i d-m-Y', strtotime($event->end)) }}</p>
					<p class="bg-info  padding">@if ($event->getSeatingCapacity() == 0) @lang('events.capacity'): {{ $event->capacity }} @endif @if ($event->getSeatingCapacity() != 0) @lang('events.seatingcapacity'): {{ $event->getSeatingCapacity() }} @endif</p>
				</div>
				<div class="col-xs-12 col-sm-7">
					<p>{!! $event->desc_long !!}</p>
				
					@if (Auth::user() && $event->getEventParticipant())

						@if (Settings::getTeamspeakLink())			
							<div>
								<a href="ts3server://{{ Settings::getTeamspeakLink() }}?nickname={{ $user->username }}" ><i class="fab fa-teamspeak fa-3x margin"></i><span>TeamSpeak Server</span></a>
							</div>
						@endif
						
						@if (Settings::getMumbleLink())			
							<div>
								<a href="mumble://{{ $user->username }}{{ chr(64) }}{{ Settings::getMumbleLink() }}" width="100%" ><img class="margin" src="https://www.mumble.info/css/mumble.svg" alt="Mumble Logo" width="48" height="48"> Mumble Server</a>
							</div>
						@endif

					@endif
				</div>
			</div>
		</div>

		<!-- TICKETS -->
		<div class="col-md-12">
			<!-- PURCHASE TICKETS -->
			@if (!$event->tickets->isEmpty())
				<div class="page-header">
					<a name="purchaseTickets"></a>
					<h3>@lang('events.purchasetickets')</h3>
				</div>
				<div class="row">
					@foreach ($event->tickets as $ticket)
						<div class="col-xs-12 col-sm-4">
							<div class="well well-sm" disabled>
								<h3>{{$ticket->name}} @if ($event->capacity <= $event->eventParticipants->count()) - <strong>@lang('events.soldout')</strong> @endif</h3>
								@if ($ticket->quantity != 0)
									<small>
										@lang('events.limitedavailability')
									</small>
								@endif
								<div class="row" style="display: flex; align-items: center;">
									<div class="col-sm-12 col-xs-12">
										<h3>{{ Settings::getCurrencySymbol() }}{{$ticket->price}}
											@if ($ticket->quantity != 0)
												<small>
													{{ $ticket->quantity - $ticket->participants()->count() }}/{{ $ticket->quantity }} @lang('events.available')
												</small>
											@endif
										</h3>
										@if ($user)
											{{ Form::open(array('url'=>'/tickets/purchase/' . $ticket->id)) }}
												@if (
													$event->capacity <= $event->eventParticipants->count() 
													|| ($ticket->participants()->count() >= $ticket->quantity && $ticket->quantity != 0)
													)
													<div class="row">
														<div class="form-group col-sm-6 col-xs-12">
															{{ Form::label('quantity','Quantity',array('id'=>'','class'=>'')) }}
															{{ Form::select('quantity', array(1 => 1), null, array('id'=>'quantity','class'=>'form-control', 'disabled' => true)) }}
														</div>
														<div class="form-group col-sm-6 col-xs-12">
															<button class="btn btn-md btn-primary btn-block"  style="margin-top:25px" disabled >@lang('events.soldout')</button>
														</div>
													</div>
												@elseif($ticket->sale_start && $ticket->sale_start >= date('Y-m-d H:i:s'))
													<h5>
														@lang('events.availablefrom', ['time' => date('H:i', strtotime($ticket->sale_start)), 'date'=> date ('d-m-Y', strtotime($ticket->sale_start))])
													</h5>
												@elseif(
													$ticket->sale_end && $ticket->sale_end <= date('Y-m-d H:i:s')
													|| date('Y-m-d H:i:s') >= $event->end 
												)
													<h5>
														@lang('events.ticketnolongavailable')
													</h5>
												@else
													<div class="row">
														<div class="form-group col-sm-6 col-xs-12">
															{{ Form::label('quantity','Quantity',array('id'=>'','class'=>'')) }}
															{{ Form::select('quantity', Helpers::getTicketQuantitySelection($ticket, $ticket->quantity - $ticket->participants()->count()), null, array('id'=>'quantity','class'=>'form-control')) }}
														</div>
														<div class="form-group col-sm-6 col-xs-12">
															{{ Form::hidden('user_id', $user->id, array('id'=>'user_id','class'=>'form-control')) }}
															<button class="btn btn-md btn-primary btn-block" style="margin-top:25px" >@lang('events.buy')</button>
														</div>
													</div>
												@endif
											{{ Form::close() }}
										@else
											<div class="alert alert-info">
												<h5>@lang('events.plslogintopurchaseticket')</h5>
											</div>
										@endif
									</div>
								</div>
							</div>
						</div>
					@endforeach
				</div>
			@endif
		</div>
	</div>

	<!-- SEATING -->
	@if (
		!$event->seatingPlans->isEmpty() && 
		(
			in_array('PUBLISHED', $event->seatingPlans->pluck('status')->toArray()) ||
			in_array('PREVIEW', $event->seatingPlans->pluck('status')->toArray())
		)
	)
		<div class="page-header">
			<a name="seating"></a>
			<h3>@lang('events.seatingplans') <small>- {{ $event->getSeatingCapacity() - $event->getSeatedCount() }} / {{ $event->getSeatingCapacity() }} @lang('events.seatsremaining')</small></h3>
		</div>
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			@foreach ($event->seatingPlans as $seatingPlan)
				@if ($seatingPlan->status != 'DRAFT')
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
							<h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_{{ $seatingPlan->slug }}" aria-expanded="true" aria-controls="collapse_{{ $seatingPlan->slug }}">
									{{ $seatingPlan->name }} <small>- {{ ($seatingPlan->columns * $seatingPlan->rows) - $seatingPlan->seats->count() }} / {{ $seatingPlan->columns * $seatingPlan->rows }} @lang('events.available')</small>
									@if ($seatingPlan->status != 'PUBLISHED')
										<small> - {{ $seatingPlan->status }}</small>
									@endif
								</a>
							</h4>
						</div>
						<div id="collapse_{{ $seatingPlan->slug }}" class="panel-collapse collapse @if ($loop->first) in @endif" role="tabpanel" aria-labelledby="collaspe_{{ $seatingPlan->slug }}">
							<div class="panel-body">
								<div class="table-responsive text-center">
									<table class="table">
										<thead>
											<tr>
											<?php
												$headers = explode(',', $seatingPlan->headers);
												$headers = array_combine(range(1, count($headers)), $headers);
											?>
											@for ($column = 1; $column <= $seatingPlan->columns; $column++)
												<th class="text-center"><h4><strong>@lang('events.row') {{ucwords($headers[$column])}}</strong></h4></th>
											@endfor
											</tr>
										 </thead>
										<tbody>
											@for ($row = $seatingPlan->rows; $row > 0; $row--)
												<tr>
													@for ($column = 1; $column <= $seatingPlan->columns; $column++)
														<td style="padding-top:14px;">
															@if ($event->getSeat($seatingPlan->id, ucwords($headers[$column]) . $row))
																@if ($seatingPlan->locked)
																	<button class="btn btn-success btn-sm" disabled>
																		{{ ucwords($headers[$column]) . $row }} - {{ $event->getSeat($seatingPlan->id, ucwords($headers[$column] . $row))->eventParticipant->user->username }}
																	</button>
																@else
																	<button class="btn btn-success btn-sm">
																		{{ ucwords($headers[$column]) . $row }} - {{ $event->getSeat($seatingPlan->id, ucwords($headers[$column] . $row))->eventParticipant->user->username }}
																	</button>
																@endif
															@else
																@if ($seatingPlan->locked)
																	<button class="btn btn-primary btn-sm" disabled>
																		{{ ucwords($headers[$column]) . $row }} - @lang('events.empty')
																	</button>
																@else
																	@if (Auth::user() && $event->getEventParticipant())
																		<button 
																			class="btn btn-primary btn-sm"
																			onclick="pickSeat(
																				'{{ $seatingPlan->slug }}',
																				'{{ ucwords($headers[$column]) . $row }}'
																			)"
																			data-toggle="modal"
																			data-target="#pickSeatModal"
																		>
																			{{ ucwords($headers[$column]) . $row }} - @lang('events.empty')
																		</button>
																	@else
																		<button class="btn btn-primary btn-sm">
																			{{ ucwords($headers[$column]) . $row }} - @lang('events.empty')
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
									@if ($seatingPlan->locked)
										<p class="text-center"><strong> @lang('events.seatingplanlocked')</strong></p>
									@endif
								</div>
								<hr>
								<div class="row" style="display: flex; align-items: center;">
									<div class="col-xs-12 col-md-8">
										<img class="img-responsive" alt="{{ $seatingPlan->name }}" src="{{$seatingPlan->image_path}}"/>
									</div>
									<div class="col-xs-12 col-md-4">
										<h5>@lang('events.yourseats')</h5>
										@if ($user && !$user->eventParticipation->isEmpty())
											@foreach ($user->eventParticipation as $participant) 
												@if ($participant->seat && $participant->seat->event_seating_plan_id == $seatingPlan->id) 
													{{ Form::open(array('url'=>'/events/' . $event->slug . '/seating/' . $seatingPlan->slug)) }}
														{{ Form::hidden('_method', 'DELETE') }}
														{{ Form::hidden('user_id', $user->id, array('id'=>'user_id','class'=>'form-control')) }} 
														{{ Form::hidden('participant_id', $participant->id, array('id'=>'participant_id','class'=>'form-control')) }} 
														{{ Form::hidden('seat_number', $participant->seat->seat, array('id'=>'seat_number','class'=>'form-control')) }} 
														<h5>
															<button class="btn btn-success btn-block"> 
															{{ $participant->seat->seat }} - @lang('events.remove')
															</button>
														</h5>
													{{ Form::close() }} 
												@endif
											@endforeach
										@elseif(Auth::user())
											<div class="alert alert-info">
												<h5>@lang('events.plspurchaseticket')</h5>
											</div>
										@else
											<div class="alert alert-info">
												<h5>@lang('events.plslogintopurchaseticket')</h5>
											</div>
										@endif
									</div>
								</div>
							</div>
						</div>
					</div>
				@endif
			@endforeach
		</div>
	@endif

	<!-- VENUE INFORMATION -->
	<div class="page-header">
		<a name="venue"></a>
		<h3>@lang('events.venueinformation')</h3>
	</div>
	<div class="row">
		<div class="col-lg-7">
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
		<div class="col-lg-5">
			@foreach ($event->venue->images as $image)
				<img class="img-responsive img-rounded" alt="{{ $event->venue->display_name }}" src="{{$image->path}}"/>
			@endforeach
		</div>
	</div>
	
	<!-- EVENT SPONSORS -->
	@if (!$event->sponsors->isEmpty())
		<div class="page-header">
			<a name="sponsors"></a>
			<h3>>@lang('events.eventsponsoredby', ['event', $event->display_name]</h3>
		</div>
		@foreach ($event->sponsors as $sponsor)
			<a href="{{ $sponsor->website }}">
				<img class="img-responsive img-rounded" src="{{ $sponsor->image_path }}" alt="{{ $sponsor->website}}" />
			</a>
		@endforeach
	@endif
	
	<!-- EVENT INFORMATION SECTIONS -->
	@if (!empty($event->information))
		<div class="page-header">
			<h3>@lang('events.therismore')</h3>
		</div>
		@php($x = 0)
		@foreach ($event->information as $section)
			<div class="row">
				@if ($x % 2 == 0)
					@if (isset($section->image_path))
						<div class="col-sm-4 visible-xs">
							<h4>{{$section->title}}</h4>
							<center>
								<img class="img-responsive img-rounded" alt="{{ $section->title }}" src="{{ $section->image_path }}" />
							</center>
						</div>
						<div class="col-sm-8">
							<h4 class="hidden-xs">{{$section->title}}</h4>
							<p>{!! $section->text !!}</p>
						</div>
						<div class="col-sm-4 hidden-xs">
							@if (isset($section->image_path))
								<center>
									<img class="img-responsive img-rounded" alt="{{ $section->title }}" src="{{ $section->image_path }}" />
								</center>
							@endif
						</div>
					@else
						<div class="col-sm-12">
							<h4>{{$section->title}}</h4>
							<p>{!! $section->text !!}</p>
						</div>
					@endif
				@else
					@if (isset($section->image_path))
						<div class="col-sm-4">
							<h4 class="visible-xs">{{$section->title}}</h4>
							<center>
								<img class="img-responsive img-rounded" alt="{{ $section->title }}" src="{{ $section->image_path }}" />
							</center>
						</div>
						<div class="col-sm-8">
							<h4 class="hidden-xs">{{$section->title}}</h4>
							<p>{!! $section->text !!}</p>
						</div>
					@else
						<div class="col-sm-12">
							<h4>{{$section->title}}</h4>
							<p>{!! $section->text !!}</p>
						</div>
					@endif
				@endif
			</div>
			<hr>
			@php($x++)
		@endforeach
	@endif
	
	<!-- TIMETABLE -->
	@if (!$event->timetables->isEmpty())
		<div class="page-header">
			<a name="timetable"></a>
			<h3>@lang('events.timetable')</h3>
		</div>
		@foreach ($event->timetables as $timetable)
			@if (strtoupper($timetable->status) == 'DRAFT')
				<h4>DRAFT</h4>
			@endif
			<h4>{{ $timetable->name }}</h4>
			<table class="table table-striped">
				<thead>
					<th>
						@lang('events.time')
					</th>
					<th>
						@lang('events.game')
					</th>
					<th>
						@lang('events.description')
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

	<!-- POLLS-->
	@if ($event->polls->count() > 0)
		<div class="page-header">
			<a name="polls"></a>
			<h3>@lang('events.haveyoursay')</h3>
		</div>
		@foreach ($event->polls as $poll)
			<h4>
				{{ $poll->name }}
				@if ($poll->status != 'PUBLISHED')
					<small> - {{ $poll->status }}</small>
				@endif
				@if ($poll->hasEnded())
					<small> - @lang('events.ended')</small>
				@endif
			</h4>
			@if (!empty($poll->description))
				<p>{{ $poll->description }}</p>
			@endif
			@include ('layouts._partials._polls.votes')
		@endforeach
	@endif

	<!-- MY TICKETS -->
	<div class="page-header">
		<a name="yourTickets"></a>
		<h3>@lang('events.mytickets')</h3>
	</div>
	@if (Auth::user())
		@if (!$user->eventParticipation->isEmpty())
			@foreach ($user->eventParticipation as $participant)
				@include('layouts._partials._tickets.index')
			@endforeach
		@else
			<div class="alert alert-info">@lang('events.purchaseticketopickseat')</div>
		@endif
	@else
		<div class="alert alert-info">@lang('events.plslogintopurchaseticket')</div>
	@endif
	
	<!-- TOURNAMENTS -->
	@if (!$event->tournaments->isEmpty() && config('challonge.api_key') != null)
		<div class="page-header">
			<a name="tournaments"></a>
			<h3>@lang('events.tournaments')</h3>
		</div>
		<div class="row">
			@foreach ($event->tournaments as $tournament)
				@if ($tournament->status != 'DRAFT')
					<div class="col-xs-12 col-sm-6 col-md-3">
						<div class="thumbnail">
							@if ($tournament->game && $tournament->game->image_thumbnail_path)
								<img class="img img-responsive img-rounded" src="{{ $tournament->game->image_thumbnail_path }}" alt="{{ $tournament->game->name }}">
							@endif
							<div class="caption">
								<a href="/events/{{ $event->slug }}/tournaments/{{ $tournament->slug }}"><h3>{{ $tournament->name }}</h3></a>
								@if ($tournament->only_signedin == false)
									<span class="small">
										@if ($tournament->status == 'COMPLETE')
											<span class="label label-success">@lang('events.ended')</span>
										@endif
										@if ($tournament->status == 'LIVE')
											<span class="label label-success">@lang('events.live')</span>
										@endif
										@if ($tournament->status != 'COMPLETE' && !$tournament->getParticipant($user->eventParticipation->id))
											<span class="label label-danger">@lang('events.notsignedup')</span>
										@endif
										@if ($tournament->status != 'COMPLETE' && $tournament->getParticipant($user->eventParticipation->id))
											<span class="label label-success">@lang('events.signedup')</span>
										@endif
									</span>
								@else
									<span class="small">	
										<span class="label label-info">@lang('events.signuponlywhenlive')</span>
									</span>										
								@endif

								<hr>
								@if ($tournament->status != 'COMPLETE')
									<dl>
										<dt>
											@lang('events.teamsizes'):
										</dt>
										<dd>
											{{ $tournament->team_size }}
										</dd>
										@if ($tournament->game)
											 <dt>
											 	@lang('events.game'):
											</dt>
											<dd>
												{{ $tournament->game->name }}
											</dd>
										@endif
										<dt>
											@lang('events.format'):
										</dt>
										<dd>
											{{ $tournament->format }}
										</dd>
									</dl>
								@endif
								<!-- // TODO - refactor & add order on rank-->
								@if ($tournament->status == 'COMPLETE' && $tournament->format != 'list')
									@if ($tournament->team_size != '1v1')
										@foreach ($tournament->tournamentTeams->sortBy('final_rank') as $tournamentParticipant)
											@if ($tournamentParticipant->final_rank == 1)
												@if ($tournament->team_size == '1v1')
													<h2>{{ Helpers::getChallongeRankFormat($tournamentParticipant->final_rank) }} - {{ $tournamentParticipant->eventParticipant->user->username }}</h2>
												@else
													<h2>{{ Helpers::getChallongeRankFormat($tournamentParticipant->final_rank) }} - {{ $tournamentParticipant->name }}</h2>
												@endif
											@endif
											@if ($tournamentParticipant->final_rank == 2)
												@if ($tournament->team_size == '1v1')
													<h3>{{ Helpers::getChallongeRankFormat($tournamentParticipant->final_rank) }} - {{ $tournamentParticipant->eventParticipant->user->username }}</h3>
												@else
													<h3>{{ Helpers::getChallongeRankFormat($tournamentParticipant->final_rank) }} - {{ $tournamentParticipant->name }}</h3>
												@endif
											@endif
											@if ($tournamentParticipant->final_rank != 2 && $tournamentParticipant->final_rank != 1)
												@if ($tournament->team_size == '1v1')
													<h4>{{ Helpers::getChallongeRankFormat($tournamentParticipant->final_rank) }} - {{ $tournamentParticipant->eventParticipant->user->username }}</h4>
												@else
													<h4>{{ Helpers::getChallongeRankFormat($tournamentParticipant->final_rank) }} - {{ $tournamentParticipant->name }}</h4>
												@endif
											@endif
										@endforeach
									@endif
									@if ($tournament->team_size == '1v1')
										@foreach ($tournament->tournamentParticipants->sortBy('final_rank') as $tournamentParticipant)
											@if ($tournamentParticipant->final_rank == 1)
												@if ($tournament->team_size == '1v1')
													<h2>{{ Helpers::getChallongeRankFormat($tournamentParticipant->final_rank) }} - {{ $tournamentParticipant->eventParticipant->user->username }}</h2>
												@else
													<h2>{{ Helpers::getChallongeRankFormat($tournamentParticipant->final_rank) }} - {{ $tournamentParticipant->name }}</h2>
												@endif
											@endif
											@if ($tournamentParticipant->final_rank == 2)
												@if ($tournament->team_size == '1v1')
													<h3>{{ Helpers::getChallongeRankFormat($tournamentParticipant->final_rank) }} - {{ $tournamentParticipant->eventParticipant->user->username }}</h3>
												@else
													<h3>{{ Helpers::getChallongeRankFormat($tournamentParticipant->final_rank) }} - {{ $tournamentParticipant->name }}</h3>
												@endif
											@endif
											@if ($tournamentParticipant->final_rank != 2 && $tournamentParticipant->final_rank != 1)
												@if ($tournament->team_size == '1v1')
													<h4>{{ Helpers::getChallongeRankFormat($tournamentParticipant->final_rank) }} - {{ $tournamentParticipant->eventParticipant->user->username }}</h4>
												@else
													<h4>{{ Helpers::getChallongeRankFormat($tournamentParticipant->final_rank) }} - {{ $tournamentParticipant->name }}</h4>
												@endif
											@endif
										@endforeach
									@endif
									
								@endif
								<strong>
									{{ $tournament->tournamentParticipants->count() }} @lang('events.signups')
								</strong>
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
		<h3>@lang('events.attendees')</h3>
	</div>
	<table class="table table-striped">
		<thead>
			<th width="15%">
			</th>
			<th>
				@lang('events.user')
			</th>
			<th>
				@lang('events.name')
			</th>
			<th>
				@lang('events.seat')
			</th>
		</thead>
		<tbody>
			@foreach ($event->eventParticipants as $participant)
			<tr>
				<td>
					<img class="img-responsive img-rounded img-small" style="max-width: 30%;" alt="{{ $participant->user->username}}'s Avatar" src="{{ $participant->user->avatar }}">
				</td>
				<td style="vertical-align: middle;">
					{{ $participant->user->username }}
					@if ($participant->user->steamid)
						- <span class="text-muted"><small>Steam: {{ $participant->user->steamname }}</small></span>
					@endif
				</td>
				<td style="vertical-align: middle;">
					{{$participant->user->firstname}}
				</td>
				<td style="vertical-align: middle;">
					@if ($participant->seat)
						{{ $participant->seat->seatingPlan->getShortName() }} | {{ $participant->seat->seat }}
					@else
						@lang('events.notseated')
					@endif
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>

<!-- Seat Modal -->
<div class="modal fade" id="pickSeatModal" tabindex="-1" role="dialog" aria-labelledby="editSeatingModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="pickSeatModalLabel"></h4>
			</div>
			@if (Auth::user())
				{{ Form::open(array('url'=>'/events/' . $event->slug . '/seating/', 'id'=>'pickSeatFormModal')) }}
					<div class="modal-body">
						<div class="form-group">
							<h4>@lang('events.wichtickettoseat')</h4>
							{{
								Form::select(
									'participant_id',
									$user->getTickets($event->id),             
									null, 
									array(
										'id'    => 'format',
										'class' => 'form-control'
									)
								)
							}}
							<p>>@lang('events.wantthisseat')</p>
							<p>@lang('events.removeitanytime')</p>
						</div>
					</div>
					{{ Form::hidden('user_id', $user->id, array('id'=>'user_id','class'=>'form-control')) }}
					{{ Form::hidden('seat', NULL, array('id'=>'seat_modal','class'=>'form-control')) }}
					<div class="modal-footer">
						<button type="submit" class="btn btn-success">@lang('events.yes')</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">@lang('events.no')</button>
					</div>
				{{ Form::close() }}
			@endif
		</div>
	</div>
</div>

<script>
	function pickSeat(seating_plan_slug, seat)
	{
		$("#seat_number_modal").val(seat);
		$("#seat_modal").val(seat);
		$("#pickSeatModalLabel").html('Do you what to choose seat ' + seat);
		$("#pickSeatFormModal").prop('action', '/events/{{ $event->slug }}/seating/' + seating_plan_slug);
	}
</script>

@endsection