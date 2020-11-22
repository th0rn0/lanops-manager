<div class="card-deck">

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
	<div class="card event-card panel-{{ $eventStatusColor }} mb-3 d-flex">
		<div class="card-header d-flex flex-wrap flex-grow-1">
			<div class="icon-large pr-0">
				<i class="fa {{ $eventStatusIcon }} fa-5x"></i>
			</div>
			<div class="ml-auto text-right">
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
		<a href="/admin/events/{{ $event->slug }}" class="card-footer d-flex flex-nowrap">
			<span class="float-lext text-nowrap">View Event Page</span>
			<span class="float-right ml-auto"><i class="fa fa-arrow-circle-right"></i></span>
			<div class="clearfix"></div>
		</a>
	</div>
	<div class="card event-card mb-3">
		<div class="card-header d-flex flex-wrap flex-grow-1">
			<div class="icon-large pr-0">
				<i class="fa fa-wheelchair fa-5x"></i>
			</div>
			<div class="col-auto ml-auto ml-auto text-right pl-0">
				<div class="huge ml-auto">{{ $event->getSeatedCount() }}</div>
				<div>Seated Participants</div>
			</div>
		</div>
		<a href="/admin/events/{{ $event->slug }}/seating" class="card-footer d-flex flex-nowrap">
			<span class="float-lext text-nowrap text-nowrap">View Seating Plans</span>
			<span class="float-right ml-auto"><i class="fa fa-arrow-circle-right"></i></span>
			<div class="clearfix"></div>
		</a>
	</div>
	<div class="card event-card mb-3">
		<div class="card-header d-flex flex-wrap flex-grow-1">
			<div class="icon-large pr-0">
				<i class="fa fa-list-ol fa-5x"></i>
			</div>
			<div class="col-auto ml-auto ml-auto text-right pl-0">
				<div class="huge ml-auto">{{ $event->tournaments->count() }}</div>
				<div>Tournaments</div>
			</div>
		</div>
		<a href="/admin/events/{{ $event->slug }}/tournaments" class="card-footer d-flex flex-nowrap">
			<span class="float-lext text-nowrap">View Tournaments</span>
			<span class="float-right ml-auto"><i class="fa fa-arrow-circle-right"></i></span>
			<div class="clearfix"></div>
		</a>
	</div>
	<div class="card event-card mb-3">
		<div class="card-header d-flex flex-wrap flex-grow-1">
			<div class="icon-large pr-0">
				<i class="fa fa-user fa-5x"></i>
			</div>
			<div class="col-auto ml-auto ml-auto text-right pl-0">
				<div class="huge ml-auto">{{ $event->eventParticipants->count() }}</div>
				<div>Attendees</div>
			</div>
		</div>
		<a href="/admin/events/{{ $event->slug }}/participants" class="card-footer d-flex flex-nowrap">
			<span class="float-lext text-nowrap">View Attendees</span>
			<span class="float-right ml-auto"><i class="fa fa-arrow-circle-right"></i></span>
			<div class="clearfix"></div>
		</a>
	</div>
	<div class="card event-card mb-3">
		<div class="card-header d-flex flex-wrap flex-grow-1">
			<div class="icon-large pr-0">
				<i class="fa fa-ticket fa-5x"></i>
			</div>
			<div class="col-auto ml-auto ml-auto text-right pl-0">
				<div class="huge ml-auto">{{ Settings::getCurrencySymbol() }}{{ $event->getTicketSalesCount() }}</div>
				<div>Ticket Sales</div>
			</div>
		</div>
		<a href="/admin/events/{{ $event->slug }}/tickets" class="card-footer d-flex flex-nowrap">
			<span class="float-lext text-nowrap">View Tickets</span>
			<span class="float-right ml-auto"><i class="fa fa-arrow-circle-right"></i></span>
			<div class="clearfix"></div>
		</a>
	</div>
	<div class="card event-card mb-3">
		<div class="card-header d-flex flex-wrap flex-grow-1">
			<div class="icon-large pr-0">
				<i class="fa fa-calendar fa-5x"></i>
			</div>
			<div class="col-auto ml-auto ml-auto text-right pl-0">
				<div class="huge ml-auto">{{ $event->getTimetableDataCount() }}</div>
				<div>Scheduled Slots</div>
			</div>
		</div>
		<a href="/admin/events/{{ $event->slug }}/timetables" class="card-footer d-flex flex-nowrap">
			<span class="float-lext text-nowrap">View Timetables</span>
			<span class="float-right ml-auto"><i class="fa fa-arrow-circle-right"></i></span>
			<div class="clearfix"></div>
		</a>
	</div>
</div>