<div class="row">

	<div class="col-lg-2 col-md-6 col-12">
		@if ($event->status == 'PUBLISHED')
			@php
				$eventStatusColor = 'green';
				$eventStatusIcon = 'fa-check-circle-o';
			@endphp
		@elseif ($event->status == 'DRAFT')
			@php
				$eventStatusColor = 'red';
				$eventStatusIcon = 'fa-times-circle-o';
			@endphp
		@elseif ($event->status == 'PREVIEW' || $event->status == 'PRIVATE')
			@php
				$eventStatusColor = 'yellow';
				$eventStatusIcon = 'fa-ban';
			@endphp
		@else
			@php
				$eventStatusColor = 'green';
				$eventStatusIcon = 'fa-question-circle ';
			@endphp
		@endif
		<div class="card panel-{{ $eventStatusColor }} mb-3">
			<div class="card-header">
				<div class="row">
					<div class="col-auto icon-large pr-0">
						<i class="fa {{ $eventStatusIcon }} fa-5x"></i>
					</div>
					<div class="col ml-auto text-right">
						<div class="huge ml-auto">
							@if ($event->status == 'PUBLISHED')
								<small>Live</small>
							@elseif ($event->status == 'DRAFT')
								<small>Draft</small>
							@elseif ($event->status == 'PREVIEW')
								<small>Preview</small>
							@elseif ($event->status == 'PRIVATE')
								<small>Private</small>
							@else
								<small>{{ $event->status }}</small>
							@endif
						</div>
						<div>Event Status</div>
					</div>
				</div>
			</div>
			<a href="/admin/events/{{ $event->slug }}">
				<div class="card-footer">
					<span class="float-left">View Event Page</span>
					<span class="float-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-2 col-md-6 col-12">
		<div class="card mb-3">
			<div class="card-header">
				<div class="row">
					<div class="col-auto icon-large pr-0">
						<i class="fa fa-wheelchair fa-5x"></i>
					</div>
					<div class="col-auto ml-auto text-right pl-0">
						<div class="huge ml-auto">{{ $event->getSeatedCount() }}</div>
						<div>Seated Participants</div>
					</div>
				</div>
			</div>
			<a href="/admin/events/{{ $event->slug }}/seating">
				<div class="card-footer">
					<span class="float-left">View Seating Plans</span>
					<span class="float-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-2 col-md-6 col-12">
		<div class="card mb-3">
			<div class="card-header">
				<div class="row">
					<div class="col-auto icon-large pr-0">
						<i class="fa fa-list-ol fa-5x"></i>
					</div>
					<div class="col-auto ml-auto text-right pl-0">
						<div class="huge ml-auto">{{ $event->tournaments->count() }}</div>
						<div>Tournaments</div>
					</div>
				</div>
			</div>
			<a href="/admin/events/{{ $event->slug }}/tournaments">
				<div class="card-footer">
					<span class="float-left">View Tournaments</span>
					<span class="float-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-2 col-md-6 col-12">
		<div class="card mb-3">
			<div class="card-header">
				<div class="row">
					<div class="col-auto icon-large pr-0">
						<i class="fa fa-user fa-5x"></i>
					</div>
					<div class="col-auto ml-auto text-right pl-0">
						<div class="huge ml-auto">{{ $event->eventParticipants->count() }}</div>
						<div>Attendees</div>
					</div>
				</div>
			</div>
			<a href="/admin/events/{{ $event->slug }}/participants">
				<div class="card-footer">
					<span class="float-left">View Attendees</span>
					<span class="float-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-2 col-md-6 col-12">
		<div class="card mb-3">
			<div class="card-header">
				<div class="row">
					<div class="col-auto icon-large pr-0">
						<i class="fa fa-ticket fa-5x"></i>
					</div>
					<div class="col-auto ml-auto text-right pl-0">
						<div class="huge ml-auto">{{ Settings::getCurrencySymbol() }}{{ $event->getTicketSalesCount() }}</div>
						<div>Ticket Sales</div>
					</div>
				</div>
			</div>
			<a href="/admin/events/{{ $event->slug }}/tickets">
				<div class="card-footer">
					<span class="float-left">View Tickets</span>
					<span class="float-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-2 col-md-6 col-12">
		<div class="card mb-3">
			<div class="card-header">
				<div class="row">
					<div class="col-auto icon-large pr-0">
						<i class="fa fa-calendar fa-5x"></i>
					</div>
					<div class="col-auto ml-auto text-right pl-0">
						<div class="huge ml-auto">{{ $event->getTimetableDataCount() }}</div>
						<div>Scheduled Slots</div>
					</div>
				</div>
			</div>
			<a href="/admin/events/{{ $event->slug }}/timetables">
				<div class="card-footer">
					<span class="float-left">View Timetables</span>
					<span class="float-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>

</div>