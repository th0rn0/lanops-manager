@if (isset($event) && isset($user->eventParticipants) && isset($signed_in))
	<li class="nav-item dropdown">
		<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My Tournaments <span class="badge badge-pill">{{ Helpers::getUserActiveTournaments($event->id) }}</span></a>
		<div class="dropdown-menu">
			@if ( count($user->eventParticipants) > 0 && Helpers::getUserActiveTournaments($event->id) != 0)
				<a class="dropdown-item d-none" href="/events/{{$event->slug}}/tournaments">All Tournaments</a>
				<div role="separator" class="dropdown-divider d-none"></div>

				@foreach ( $user->eventParticipants as $eventParticipant )
					@foreach ( $eventParticipant->tournamentParticipants as $tournamentParticipant)
						@if ($tournamentParticipant->eventTournament->event_id == $event->id)
							@if ($tournamentParticipant->eventTournament->status == 'COMPLETE')
								<a class="dropdown-item" href="/events/{{$event->slug}}/tournaments/{{$tournamentParticipant->eventTournament->slug}}"><del>{{$tournamentParticipant->eventTournament->name}}</del></a>
							@else
								<a class="dropdown-item" href="/events/{{$event->slug}}/tournaments/{{$tournamentParticipant->eventTournament->slug}}">{{$tournamentParticipant->eventTournament->name}}</a>
							@endif
						@endif
					@endforeach
				@endforeach
			@else
				<a class="dropdown-item" href="#">No Tournaments</a>
			@endif
		</div>
	</li>
@endif