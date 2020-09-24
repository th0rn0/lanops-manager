<li class="dropdown">
	<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">@lang('messages.next_event'): {{ Helpers::getNextEventName() }} <span class="caret"></span></a>
	<ul class="dropdown-menu">
		<li><a href="/events/{{ Helpers::getNextEventSlug() }}">@lang('layouts.events_navi_information')</a></li>
		<li><a href="/events/{{ Helpers::getNextEventSlug() }}#purchaseTickets">@lang('layouts.events_navi_tickets')</a></li>
		@if (isset($event) && !$event->timetables->isEmpty())
			<li><a href="/events/{{ Helpers::getNextEventSlug() }}#timetable">@lang('layouts.events_navi_timetable')</a></li>
		@endif
		<li><a href="/events/{{ Helpers::getNextEventSlug() }}#attendees">@lang('layouts.events_navi_attendees')</a></li>
		@if (isset($event) && !$event->tournaments->isEmpty() && config('challonge.api_key') != null)
			<li><a href="/events/{{ Helpers::getNextEventSlug() }}#tournaments">@lang('layouts.events_navi_tournaments')</a></li>
		@endif
		@if (isset($event) && !$event->seatingPlans->isEmpty() && (in_array('PUBLISHED', $event->seatingPlans->pluck('status')->toArray()) || in_array('PREVIEW', $event->seatingPlans->pluck('status')->toArray()))	)
			<li><a href="/events/{{ Helpers::getNextEventSlug() }}#seating">@lang('layouts.events_navi_seating')</a></li>
		@endif
		@if(Auth::user())
			<li><a href="/events/{{ Helpers::getNextEventSlug() }}#yourTickets">@lang('layouts.events_navi_your_tickets')</a></li>
		@endif
	</ul>
</li>
<li class="dropdown">
	<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">@lang('layouts.events_navi_future_events') <span class="caret"></span></a>
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
			<li><a href="#">@lang('layouts.events_navi_no_future_events')</a></li>
		@endif
	</ul>
</li>
<li class="dropdown">
	<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">@lang('layouts.events_all_events') <span class="caret"></span></a>
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
			<li><a href="#">@lang('layouts.events_navi_no_future_events')</a></li>
		@endif
	</ul>
</li>