@extends ('layouts.default')

@section ('page_title',  config('app.name') . ' - ' . $event->display_name)

@section ('content')

@php
	$ticketsRemaining = max($event->capacity - $event->eventParticipants->count(), 0);
	$ticketsPct = $event->capacity > 0 ? round(($event->eventParticipants->count() / $event->capacity) * 100) : 0;
@endphp

{{-- Event Header --}}
<div class="event-detail-header">
	<div class="container">
		<a href="{{ url('/events') }}" class="event-detail-back">&larr; All Events</a>
		@if ($event->type)
			<span class="event-detail-badge {{ $event->type === 'TABLETOP' ? 'event-detail-badge--alt' : '' }}">{{ $event->type }}</span>
		@endif
		<h1 class="event-detail-title">
			{{$event->display_name}}
			@if ($event->status != 'PUBLISHED')
				<span class="event-detail-status">{{ $event->status }}</span>
			@endif
		</h1>
		<p class="event-detail-summary">{!! $event->desc_short !!}</p>
		<div class="event-detail-meta">
			<span>{{ date('jS F Y', strtotime($event->start)) }} &ndash; {{ date('jS F Y', strtotime($event->end)) }}</span>
			@if ($event->venue)
				<span class="event-detail-meta-sep">&middot;</span>
				<span>{{ $event->venue->display_name }}</span>
			@endif
		</div>
		@if ($event->capacity > 0)
			<div class="event-detail-capacity">
				<div class="event-detail-capacity-bar">
					<div class="event-detail-capacity-fill" style="width: {{ $ticketsPct }}%"></div>
				</div>
				<span class="event-detail-capacity-text"><strong>{{ $ticketsRemaining }}</strong>/{{ $event->capacity }} tickets remaining</span>
			</div>
		@endif
	</div>
</div>

{{-- In-page Navigation --}}
<div class="event-detail-nav-wrap text-center">
	<nav class="navbar navbar-default navbar-events" style="z-index: 1; margin-bottom: 0;">
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
					<li style="font-size:15px; font-weight:bold;"><a href="#event">Event Information</a></li>
					@if (!$event->seatingPlans->isEmpty())
						<li style="font-size:15px; font-weight:bold;"><a href="#seating">Seating</a></li>
					@endif
					<li style="font-size:15px; font-weight:bold;"><a href="#attendees">Attendees</a></li>
					@if (!$event->timetables->isEmpty())
						<li style="font-size:15px; font-weight:bold;"><a href="#timetable">Timetable</a></li>
					@endif
					<li style="font-size:15px; font-weight:bold;"><a href="#yourTickets">Your Tickets</a></li>
					@if (!$event->polls->isEmpty())
						<li style="font-size:15px; font-weight:bold;"><a href="#polls">Have Your Say</a></li>
					@endif

				</ul>
			</div><!--/.nav-collapse -->
		</div><!--/.container-fluid -->
	</nav>
</div>

<div class="event-detail-section section-padding">
<div class="container">

	<div class="event-detail-block" id="event">
		<a name="event"></a>
		<h2 class="section-heading">Event Information</h2>
		<div class="row">
			<div class="col-xs-12 col-sm-5">
				<dl class="event-detail-meta-list">
					<dt>Start</dt>
					<dd>{{ date('H:i, d M Y', strtotime($event->start)) }}</dd>
					<dt>End</dt>
					<dd>{{ date('H:i, d M Y', strtotime($event->end)) }}</dd>
				</dl>
			</div>
			<div class="col-xs-12 col-sm-7">
				<div class="event-detail-prose">{!! $event->desc_long !!}</div>
			</div>
		</div>
	</div>

	@if (!$event->tickets->isEmpty())
		<div class="event-detail-block" id="purchaseTickets">
			<a name="purchaseTickets"></a>
			<h2 class="section-heading">Purchase Tickets</h2>
			<div class="row">
				@foreach ($event->tickets as $ticket)
					<div class="col-xs-12 col-sm-4">
						<div class="ticket-card">
							<h3 class="ticket-card-title">
								{{$ticket->name}}
								@if ($event->capacity <= $event->eventParticipants->count())
									<span class="ticket-card-soldout">SOLD OUT!</span>
								@endif
							</h3>
							@if ($ticket->quantity != 0)
								<small class="ticket-card-limited">Limited Availablity</small>
							@endif
							<div class="ticket-card-price">
								{{ config('app.currency_symbol') }}{{number_format((float)$ticket->price, 2, '.', '')}}
								@if ($ticket->quantity != 0)
									<span class="ticket-card-avail">{{ $ticket->quantity - $ticket->participants()->count() }}/{{ $ticket->quantity }} Available</span>
								@endif
							</div>
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
												<button class="btn btn-md btn-default btn-block"  style="margin-top:25px" disabled >SOLD OUT!</button>
											</div>
										</div>
									@elseif($ticket->sale_start && $ticket->sale_start >= date('Y-m-d H:i:s'))
										<h5>
											This Ticket will be available for purchase at {{ date('H:i', strtotime($ticket->sale_start)) }} on {{ date ('d-m-Y', strtotime($ticket->sale_start)) }}
										</h5>
									@elseif(
										$ticket->sale_end && $ticket->sale_end <= date('Y-m-d H:i:s')
										|| date('Y-m-d H:i:s') >= $event->end
									)
										<h5>
											This Ticket is no longer available for purchase
										</h5>
									@else
										<div class="row">
											<div class="form-group col-sm-6 col-xs-12">
												{{ Form::label('quantity','Quantity',array('id'=>'','class'=>'')) }}
												{{ Form::select('quantity', array(1 => 1, 2 => 2), null, array('id'=>'quantity','class'=>'form-control')) }}
											</div>
											<div class="form-group col-sm-6 col-xs-12">
												{{ Form::hidden('user_id', $user->id, array('id'=>'user_id','class'=>'form-control')) }}
												<button class="btn btn-md btn-orange btn-block" style="margin-top:25px" >Buy</button>
											</div>
										</div>
									@endif
								{{ Form::close() }}
							@else
								<div class="alert alert-info">
									<h5>Please Log in to Purchase a ticket</h5>
								</div>
							@endif
						</div>
					</div>
				@endforeach
			</div>
		</div>
	@endif

	@if (
		!$event->seatingPlans->isEmpty() &&
		(
			in_array('PUBLISHED', $event->seatingPlans->pluck('status')->toArray()) ||
			in_array('PREVIEW', $event->seatingPlans->pluck('status')->toArray())
		)
	)
		<div class="event-detail-block" id="seating">
			<a name="seating"></a>
			<h2 class="section-heading">Seating Plans <small>- {{ $event->getSeatingCapacity() - $event->getSeatedCount() }} / {{ $event->getSeatingCapacity() }} Seats Remaining</small></h2>
			<div class="panel-group event-seating" id="accordion" role="tablist" aria-multiselectable="true">
				@foreach ($event->seatingPlans as $seatingPlan)
					@if ($seatingPlan->status != 'DRAFT')
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingOne">
								<h4 class="panel-title">
									<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_{{ $seatingPlan->slug }}" aria-expanded="true" aria-controls="collapse_{{ $seatingPlan->slug }}">
										{{ $seatingPlan->name }} <small>- {{ $seatingPlan->getCapacity() - $seatingPlan->getSeatedCount() }} / {{ $seatingPlan->getCapacity() }} Seats Remaining</small>
										@if ($seatingPlan->status != 'PUBLISHED')
											<small> - {{ $seatingPlan->status }}</small>
										@endif
									</a>
								</h4>
							</div>
							<div id="collapse_{{ $seatingPlan->slug }}" class="panel-collapse collapse @if ($loop->first) in @endif" role="tabpanel" aria-labelledby="collaspe_{{ $seatingPlan->slug }}">
								<div class="panel-body">
									@include ('layouts._partials._seating.plan', ['horizontal' => false])
									<hr>
									<div class="row" style="display: flex; align-items: center;">
										<div class="col-xs-12 col-md-8">
											@if ($seatingPlan->hasMedia())
												<img class="img-responsive" alt="{{ $seatingPlan->name }}" src="{{$seatingPlan->getFirstMediaUrl()}}"/>
											@endif
										</div>
										<div class="col-xs-12 col-md-4">
											<h5>Your Seats</h5>
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
					@endif
				@endforeach
			</div>
		</div>
	@endif

	<div class="event-detail-block" id="venue">
		<a name="venue"></a>
		<h2 class="section-heading">Venue Information</h2>
		<div class="row">
			<div class="col-lg-7">
				<address class="event-detail-address">
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
				<div class="event-venue-photos">
					@foreach ($event->venue->getMedia() as $image)
						<div class="event-venue-photo">
							<img alt="{{ $event->venue->display_name }}" src="{{$image->getUrl()}}"/>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>

	@if (!empty($event->information))
		<div class="event-detail-block">
			<h2 class="section-heading">And there's more...</h2>
			@php($x = 0)
			@foreach ($event->information as $section)
				<div class="row event-info-row">
					@if ($x % 2 == 0)
						@if ($section->hasMedia())
							<div class="col-sm-4 visible-xs">
								<h4 class="event-detail-subheading">{{$section->title}}</h4>
								<center>
									<img class="img-responsive img-rounded" alt="{{ $section->title }}" src="{{ $section->getFirstMediaUrl() }}" />
								</center>
							</div>
							<div class="col-sm-8">
								<h4 class="event-detail-subheading hidden-xs">{{$section->title}}</h4>
								<div class="event-detail-prose">{!! $section->text !!}</div>
							</div>
							<div class="col-sm-4 hidden-xs">
									<center>
										<img class="img-responsive img-rounded" alt="{{ $section->title }}" src="{{ $section->getFirstMediaUrl() }}" />
									</center>
							</div>
						@else
							<div class="col-sm-12">
								<h4 class="event-detail-subheading">{{$section->title}}</h4>
								<div class="event-detail-prose">{!! $section->text !!}</div>
							</div>
						@endif
					@else
						@if ($section->hasMedia())
							<div class="col-sm-4">
								<h4 class="event-detail-subheading visible-xs">{{$section->title}}</h4>
								<center>
									<img class="img-responsive img-rounded" alt="{{ $section->title }}" src="{{ $section->getFirstMediaUrl() }}" />
								</center>
							</div>
							<div class="col-sm-8">
								<h4 class="event-detail-subheading hidden-xs">{{$section->title}}</h4>
								<div class="event-detail-prose">{!! $section->text !!}</div>
							</div>
						@else
							<div class="col-sm-12">
								<h4 class="event-detail-subheading">{{$section->title}}</h4>
								<div class="event-detail-prose">{!! $section->text !!}</div>
							</div>
						@endif
					@endif
				</div>
				@if (!$loop->last)
					<hr>
				@endif
				@php($x++)
			@endforeach
		</div>
	@endif

	@if (!$event->timetables->isEmpty())
		<div class="event-detail-block" id="timetable">
			<a name="timetable"></a>
			<h2 class="section-heading">Timetable</h2>
			@foreach ($event->timetables as $timetable)
				<h4 class="event-detail-subheading">
					{{ $timetable->name }}
					@if (strtoupper($timetable->status) == 'DRAFT')
						<span class="event-detail-draft">DRAFT</span>
					@endif
				</h4>
				<div class="table-responsive">
					<table class="table event-detail-table">
						<thead>
							<tr>
								<th>Time</th>
								<th>Game</th>
								<th>Description</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($timetable->data as $slot)
								@if ($slot->name != NULL && $slot->desc != NULL)
									<tr>
										<td>{{ date("D", strtotime($slot->start_time)) }} - {{ date("H:i", strtotime($slot->start_time)) }}</td>
										<td>{{ $slot->name }}</td>
										<td>{{ $slot->desc }}</td>
									</tr>
								@endif
							@endforeach
						</tbody>
					</table>
				</div>
			@endforeach
		</div>
	@endif

	@if ($event->polls->count() > 0)
		<div class="event-detail-block" id="polls">
			<a name="polls"></a>
			<h2 class="section-heading">Have Your Say...</h2>
			@foreach ($event->polls as $poll)
				<h4 class="event-detail-subheading">
					{{ $poll->name }}
					@if ($poll->status != 'PUBLISHED')
						<small> - {{ $poll->status }}</small>
					@endif
					@if ($poll->hasEnded())
						<small> - Ended</small>
					@endif
				</h4>
				@if (!empty($poll->description))
					<p class="event-detail-prose">{{ $poll->description }}</p>
				@endif
				@include ('layouts._partials._polls.votes')
			@endforeach
		</div>
	@endif

	@if (!$event->tournaments->isEmpty())
		<div class="event-detail-block" id="tournaments">
			<a name="tournaments"></a>
			<h2 class="section-heading">Tournaments</h2>
			<div class="event-detail-panels">
				@foreach ($event->tournaments as $tournament)
					@include('layouts._partials._tournaments.index')
				@endforeach
			</div>
		</div>
	@endif

	<div class="event-detail-block" id="yourTickets">
		<a name="yourTickets"></a>
		<h2 class="section-heading">My Tickets</h2>
		@if (Auth::user())
			@if (!$user->eventParticipation->isEmpty())
				<div class="event-detail-panels">
					@foreach ($user->eventParticipation as $participant)
						@include('layouts._partials._tickets.index')
					@endforeach
				</div>
			@else
				<div class="alert alert-info">Please Purchase a ticket to pick a seats</div>
			@endif
		@else
			<div class="alert alert-info">Please Log in to Purchase a ticket</div>
		@endif
	</div>

	<div class="event-detail-block" id="attendees">
		<a name="attendees"></a>
		<h2 class="section-heading">Attendees</h2>
		<div class="table-responsive">
			<table class="table event-detail-table">
				<thead>
					<tr>
						<th width="15%"></th>
						<th>User</th>
						<th>Name</th>
						<th>Seat</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($event->eventParticipants as $participant)
					<tr>
						<td>
							<img class="img-responsive img-rounded img-small" style="max-width: 30%;" alt="{{ $participant->user->username}}'s Avatar" src="{{ $participant->user->avatar }}">
						</td>
						<td>
							{{ $participant->user->username }}
							@if ($steamProfile = Helpers::getSteamProfile($participant->user->steamid))
								- <span class="text-muted"><small>Steam: {{ $steamProfile["name"] }}</small></span>
							@endif
						</td>
						<td>
							{{$participant->user->firstname}}
						</td>
						<td>
							@if ($participant->seat)
								{{ $participant->seat->seatingPlan->getShortName() }} | {{ $participant->seat->seat }}
							@else
								Not Seated
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

</div>
</div>

@endsection
