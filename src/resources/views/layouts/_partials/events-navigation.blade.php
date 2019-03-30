<li class="dropdown">
	<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Next Event: {{ Helpers::getNextEventName() }} <span class="caret"></span></a>
	<ul class="dropdown-menu">
		<li><a href="/events/{{ Helpers::getNextEventSlug() }}">Information</a></li>
		<li><a href="/events/{{ Helpers::getNextEventSlug() }}#purchaseTickets">Tickets</a></li>
		<li><a href="/events/{{ Helpers::getNextEventSlug() }}#timetable">Timetable</a></li>
		<li><a href="/events/{{ Helpers::getNextEventSlug() }}#attendees">Attendees</a></li>
		<li><a href="/events/{{ Helpers::getNextEventSlug() }}#seating">Seating</a></li>
		@if(Auth::user())
			<li><a href="/events/{{ Helpers::getNextEventSlug() }}#yourTickets">Your Tickets</a></li>
		@endif
	</ul>
</li>
<li class="dropdown">
	<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Future Events <span class="caret"></span></a>
	<ul class="dropdown-menu">
		@if ( count($events) > 0 )
			@foreach ( $events->reverse() as $event )
				@if ($event->start > \Carbon\Carbon::today() )
					<li>
						<a href="/events/{{ $event->slug }}">
							{{ $event->display_name }}
							@if ($event->status != 'PUBLISHED')
								- {{ $event->status }}
							@endif
						</a>
					</li>
				@endif
			@endforeach
		@else
			<li><a href="#">No Future events</a></li>
		@endif
	</ul>
</li>
<li class="dropdown">
	<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">All Events <span class="caret"></span></a>
	<ul class="dropdown-menu">
		@if ( count($events) > 0 )
			@foreach ( $events as $event )
				<li>
					<a href="/events/{{ $event->slug }}">
						{{ $event->display_name }}
						@if ($event->status != 'PUBLISHED')
							- {{ $event->status }}
						@endif
					</a>
				</li>
			@endforeach
		@else
			<li><a href="#">No events</a></li>
		@endif
	</ul>
</li>