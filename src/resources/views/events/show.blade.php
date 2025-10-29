@extends ('layouts.default')

@section ('page_title',  config('app.name') . ' - ' . $event->display_name)

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
		<div class="row">
			<div class="col-xs-12">
				<h3>
					<strong>{{ max($event->capacity - $event->eventParticipants->count(), 0) }}/{{ $event->capacity }}</strong> Tickets Available
				</h3>
			</div>
			@if ($event->capacity > 0)
				<div class="col-xs-12">
					<div class="progress">
						<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="{{ ($event->eventParticipants->count() / $event->capacity) * 100}}" aria-valuemin="0" aria-valuemax="100" style="width: {{ ($event->eventParticipants->count() / $event->capacity) * 100}}%;">
							Purchased
						</div>
						<div class="progress-bar progress-bar-success" style="width: {{ 100 - ($event->eventParticipants->count() / $event->capacity) * 100}}%;">
							<span class="sr-only">Available</span>
							Available
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
				<h3>Event Information</h3>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-5">
					<p class="bg-success  padding">Start: {{ date('H:i d-m-Y', strtotime($event->start)) }}</p>
					<p class="bg-danger  padding">End: {{ date('H:i d-m-Y', strtotime($event->end)) }}</p>
					<p class="bg-info  padding">@if ($event->getSeatingCapacity() == 0) Capacity: {{ $event->capacity }} @endif @if ($event->getSeatingCapacity() != 0) Seating Capacity: {{ $event->getSeatingCapacity() }} @endif</p>
				</div>
				<div class="col-xs-12 col-sm-7">
					<p>{!! $event->desc_long !!}</p>
				</div>
			</div>
		</div>

		<!-- TICKETS -->
		<div class="col-md-12">
			<!-- PURCHASE TICKETS -->
			@if (!$event->tickets->isEmpty())
				<div class="page-header">
					<a name="purchaseTickets"></a>
					<h3>Purchase Tickets</h3>
				</div>
				<div class="row">
					@foreach ($event->tickets as $ticket)
						<div class="col-xs-12 col-sm-4">
							<div class="well well-sm" disabled>
								<h3>{{$ticket->name}} @if ($event->capacity <= $event->eventParticipants->count()) - <strong>SOLD OUT!</strong> @endif</h3>
								@if ($ticket->quantity != 0)
									<small>
										Limited Availablity
									</small>
								@endif
								<div class="row" style="display: flex; align-items: center;">
									<div class="col-sm-12 col-xs-12">
										<h3>{{ config('app.currency_symbol') }}{{$ticket->price}}
											@if ($ticket->quantity != 0)
												<small>
													{{ $ticket->quantity - $ticket->participants()->count() }}/{{ $ticket->quantity }} Available
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
															<button class="btn btn-md btn-primary btn-block"  style="margin-top:25px" disabled >SOLD OUT!</button>
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
															<button class="btn btn-md btn-primary btn-block" style="margin-top:25px" >Buy</button>
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
			<h3>Seating Plans <small>- {{ $event->getSeatingCapacity() - $event->getSeatedCount() }} / {{ $event->getSeatingCapacity() }} Seats Remaining</small></h3>
		</div>
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
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
	@endif

	<!-- VENUE INFORMATION -->
	<div class="page-header">
		<a name="venue"></a>
		<h3>Venue Information</h3>
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
			@foreach ($event->venue->getMedia() as $image)
				<img class="img-responsive img-rounded" alt="{{ $event->venue->display_name }}" src="{{$image->getUrl()}}"/>
			@endforeach
		</div>
	</div>
	
	<!-- EVENT INFORMATION SECTIONS -->
	@if (!empty($event->information))
		<div class="page-header">
			<h3>And there's more...</h3>
		</div>
		@php($x = 0)
		@foreach ($event->information as $section)
			<div class="row">
				@if ($x % 2 == 0)
					@if ($section->hasMedia())
						<div class="col-sm-4 visible-xs">
							<h4>{{$section->title}}</h4>
							<center>
								<img class="img-responsive img-rounded" alt="{{ $section->title }}" src="{{ $section->getFirstMediaUrl() }}" />
							</center>
						</div>
						<div class="col-sm-8">
							<h4 class="hidden-xs">{{$section->title}}</h4>
							<p>{!! $section->text !!}</p>
						</div>
						<div class="col-sm-4 hidden-xs">
								<center>
									<img class="img-responsive img-rounded" alt="{{ $section->title }}" src="{{ $section->getFirstMediaUrl() }}" />
								</center>
						</div>
					@else
						<div class="col-sm-12">
							<h4>{{$section->title}}</h4>
							<p>{!! $section->text !!}</p>
						</div>
					@endif
				@else
					@if ($section->hasMedia())
						<div class="col-sm-4">
							<h4 class="visible-xs">{{$section->title}}</h4>
							<center>
								<img class="img-responsive img-rounded" alt="{{ $section->title }}" src="{{ $section->getFirstMediaUrl() }}" />
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
			<h3>Timetable</h3>
		</div>
		@foreach ($event->timetables as $timetable)
			@if (strtoupper($timetable->status) == 'DRAFT')
				<h4>DRAFT</h4>
			@endif
			<h4>{{ $timetable->name }}</h4>
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

	<!-- POLLS-->
	@if ($event->polls->count() > 0)
		<div class="page-header">
			<a name="polls"></a>
			<h3>Have Your Say...</h3>
		</div>
		@foreach ($event->polls as $poll)
			<h4>
				{{ $poll->name }}
				@if ($poll->status != 'PUBLISHED')
					<small> - {{ $poll->status }}</small>
				@endif
				@if ($poll->hasEnded())
					<small> - Ended</small>
				@endif
			</h4>
			@if (!empty($poll->description))
				<p>{{ $poll->description }}</p>
			@endif
			@include ('layouts._partials._polls.votes')
		@endforeach
	@endif

	<!-- TOURNAMENTS-->
	@if (!$event->tournaments->isEmpty())
		<div class="page-header">
			<a name="tournaments"></a>
			<h3>Tournaments</h3>
		</div>
		@foreach ($event->tournaments as $tournament)
			@include('layouts._partials._tournaments.index')
		@endforeach
	@endif

	<!-- MY TICKETS -->
	<div class="page-header">
		<a name="yourTickets"></a>
		<h3>My Tickets</h3>
	</div>
	@if (Auth::user())
		@if (!$user->eventParticipation->isEmpty())
			@foreach ($user->eventParticipation as $participant)
				@include('layouts._partials._tickets.index')
			@endforeach
		@else
			<div class="alert alert-info">Please Purchase a ticket to pick a seats</div>
		@endif
	@else
		<div class="alert alert-info">Please Log in to Purchase a ticket</div>
	@endif
	
	<!-- ATTENDEES -->
	<div class="page-header">
		<a name="attendees"></a>
		<h3>Attendees</h3>
	</div>
	<table class="table table-striped">
		<thead>
			<th width="15%">
			</th>
			<th>
				User
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
						Not Seated
					@endif
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>

@seo(['description' => strip_tags(substr($event->desc_long, 0, 1000))])

@endsection
