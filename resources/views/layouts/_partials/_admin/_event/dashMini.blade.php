<div class="row">

	<div class="col-lg-2 col-md-6 col-xs-12">
		@if ($event->status == 'PUBLISHED')
			@php 
				$event_status_color = 'green'; 
				$event_status_icon = 'fa-check-circle-o';
			@endphp
		@elseif ($event->status == 'DRAFT')
			@php 
				$event_status_color = 'red';
				$event_status_icon = 'fa-times-circle-o';
			@endphp
		@elseif ($event->status == 'PREVIEW' || $event->status == 'PRIVATE')
			@php 
				$event_status_color = 'yellow'; 
				$event_status_icon = 'fa-ban';
			@endphp
		@else
			@php
				$event_status_color = 'green';
				$event_status_icon = 'fa-question-circle ';
			@endphp
		@endif
		<div class="panel panel-{{ $event_status_color }}">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa {{ $event_status_icon }} fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge">
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
				<div class="panel-footer">
					<span class="pull-left">View Event Page</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-2 col-md-6 col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-wheelchair fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge">{{ $event->getSeatedCount() }}</div>
						<div>Seated Participants</div>
					</div>
				</div>
			</div>
			<a href="/admin/events/{{ $event->slug }}/seating">
				<div class="panel-footer">
					<span class="pull-left">View Seating Plans</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-2 col-md-6 col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-list-ol fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge">{{ $event->tournaments->count() }}</div>
						<div>Tournaments</div>
					</div>
				</div>
			</div>
			<a href="/admin/events/{{ $event->slug }}/tournaments">
				<div class="panel-footer">
					<span class="pull-left">View Tournaments</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-2 col-md-6 col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-user fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge">{{ $event->eventParticipants->count() }}</div>
						<div>Attendees</div>
					</div>
				</div>
			</div>
			<a href="/admin/events/{{ $event->slug }}/participants">
				<div class="panel-footer">
					<span class="pull-left">View Attendees</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-2 col-md-6 col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-ticket fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge">£{{ $event->getTotalTicketSales() }}</div>
						<div>Ticket Sales</div>
					</div>
				</div>
			</div>
			<a href="/admin/events/{{ $event->slug }}/tickets">
				<div class="panel-footer">
					<span class="pull-left">View Tickets</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-2 col-md-6 col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-calendar fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge">{{ $event->timetables()->count() }}</div>
						<div>Scheduled Slots</div>
					</div>
				</div>
			</div>
			<a href="/admin/events/{{ $event->slug }}/timetables">
				<div class="panel-footer">
					<span class="pull-left">View Timetables</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>

</div>