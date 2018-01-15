@if(isset($event) && isset($user->eventParticipants) && isset($signed_in))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My Tournaments({{Helpers::getUserActiveTournaments($event->id)}}) <span class="caret"></span></a>
		<ul class="dropdown-menu">
			@if ( count($user->eventParticipants) > 0 )
				<li class="hidden"><a href="/events/{{$event->slug}}/tournaments">All Tournaments</a></li>
				<li role="separator" class="divider hidden"></li>

				@foreach ( $user->eventParticipants as $eventParticipant )
					@foreach ( $eventParticipant->tournamentParticipants as $tournament_participant)
						@if ($tournament_participant->eventTournament->event_id == $event->id)
							@if ($tournament_participant->eventTournament->status == 'COMPLETE')
								<li><a href="/events/{{$event->slug}}/tournaments/{{$tournament_participant->eventTournament->slug}}"><del>{{$tournament_participant->eventTournament->name}}</del></a></li>
							@else
								<li><a href="/events/{{$event->slug}}/tournaments/{{$tournament_participant->eventTournament->slug}}">{{$tournament_participant->eventTournament->name}}</a></li>
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