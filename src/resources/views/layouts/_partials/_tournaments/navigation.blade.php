@if (isset($event) && isset($user->eventParticipants) && isset($signed_in))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My Tournaments <span class="badge">{{ Helpers::getUserActiveTournaments($event->id) }}</span> <span class="caret"></span></a>
		<ul class="dropdown-menu">
			@if ( count($user->eventParticipants) > 0 && Helpers::getUserActiveTournaments($event->id) != 0)
				<li class="hidden"><a href="/events/{{$event->slug}}/tournaments">All Tournaments</a></li>
				<li role="separator" class="divider hidden"></li>

				@foreach ( $user->eventParticipants as $eventParticipant )
					@foreach ( $eventParticipant->tournamentParticipants as $tournamentParticipant)
						@if ($tournamentParticipant->eventTournament->event_id == $event->id)
							@if ($tournamentParticipant->eventTournament->status == 'COMPLETE')
								<li><a href="/events/{{$event->slug}}/tournaments/{{$tournamentParticipant->eventTournament->slug}}"><del>{{$tournamentParticipant->eventTournament->name}}</del></a></li>
							@else
								<li><a href="/events/{{$event->slug}}/tournaments/{{$tournamentParticipant->eventTournament->slug}}">{{$tournamentParticipant->eventTournament->name}}</a></li>
							@endif
						@endif
					@endforeach
				@endforeach
			@else
				<li><a href="#">No Tournaments</a></li>
			@endif
		</ul>
	</li>
@endif