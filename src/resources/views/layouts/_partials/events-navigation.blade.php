<li class="nav-item dropdown">
	<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">@lang('messages.next_event'): {{ Helpers::getNextEventName() }} </a>
	<div class="dropdown-menu">
		<a class="dropdown-item" href="/events/{{ Helpers::getNextEventSlug() }}">@lang('layouts.events_navi_information')</a>
		<a class="dropdown-item" href="/events/{{ Helpers::getNextEventSlug() }}#purchaseTickets">@lang('layouts.events_navi_tickets')</a>
		@if (isset($event) && !$event->timetables->isEmpty())
			<a class="dropdown-item" href="/events/{{ Helpers::getNextEventSlug() }}#timetable">@lang('layouts.events_navi_timetable')</a>
		@endif
		@if (isset($event) && (!$event->private_participants || (Auth::user() && !Auth::user()->getAllTickets($event->id)->isEmpty())))
		<a class="dropdown-item" href="/events/{{ Helpers::getNextEventSlug() }}#attendees">@lang('layouts.events_navi_attendees')</a>
		@endif
		@if (isset($event) && !$event->tournaments->isEmpty() && config('challonge.api_key') != null)
			<a class="dropdown-item" href="/events/{{ Helpers::getNextEventSlug() }}#tournaments">@lang('layouts.events_navi_tournaments')</a>
		@endif
		@if (isset($event) && !$event->seatingPlans->isEmpty() && (in_array('PUBLISHED', $event->seatingPlans->pluck('status')->toArray()) || in_array('PREVIEW', $event->seatingPlans->pluck('status')->toArray()))	)
			<a class="dropdown-item" href="/events/{{ Helpers::getNextEventSlug() }}#seating">@lang('layouts.events_navi_seating')</a>
		@endif
		@if(Auth::user())
			<a class="dropdown-item" href="/events/{{ Helpers::getNextEventSlug() }}#yourTickets">@lang('layouts.events_navi_your_tickets')</a>
		@endif
	</div>
</li>
<li class="nav-item dropdown">
	<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">@lang('layouts.events_navi_future_events') </a>
	<div class="dropdown-menu">
		@if ( count($events) > 0 )
			@foreach ( $events->reverse() as $event )
				@if ($event->start > \Carbon\Carbon::today() )
					<a class="dropdown-item" href="/events/{{ $event->slug }}">
						{{ $event->display_name }}
						@if ($event->status != 'PUBLISHED')
							- {{ $event->status }}
						@endif
					</a>
				@endif
			@endforeach
		@else
			<li><a href="#">@lang('layouts.events_navi_no_future_events')</a></li>
		@endif
	</div>
</li>
<li class="nav-item dropdown">
	<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">@lang('layouts.events_all_events') </a>
	<div class="dropdown-menu">
		@if ( count($events) > 0 )
			@foreach ( $events as $event )
				<a class="dropdown-item" href="/events/{{ $event->slug }}">
					{{ $event->display_name }}
					@if ($event->status != 'PUBLISHED')
						- {{ $event->status }}
					@endif
				</a>
			@endforeach
		@else
			<a class="dropdown-item" href="#">@lang('layouts.events_navi_no_future_events')</a>
		@endif
	</div>
</li>