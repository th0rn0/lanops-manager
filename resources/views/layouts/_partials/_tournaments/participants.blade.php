@if ($tournament->team_size == '1v1')
	<div class="table-responsive">
		<table class="table">
			 <thead>
				<tr>
					<th>
						Name
					</th>
					<th>
						Seat
					</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($tournament->tournamentParticipants as $tournament_participant)
					<tr>
						<td>
							<p style="padding-top:7px;"><img class="img-rounded" style="max-width: 4%;" src="{{$tournament_participant->eventParticipant->user->avatar}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $tournament_participant->eventParticipant->user->steamname }}</p>
						</td>
						<td>
							<p style="padding-top:15px;">
								@if ($tournament_participant->eventParticipant->seat)
									{{ $tournament_participant->eventParticipant->seat->seat }}
								@else
									Not Seated
								@endif 
							</p>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endif
@if ($tournament->team_size != '1v1')
	<div class="col-sm-6 col-xs-12">
		<h3>Pugs</h3>
		<div class="table-responsive">
			<table class="table">
				 <thead>
					<tr>
						<th>
							Player name
						</th>
						<th>
							Seat
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($tournament->tournamentParticipants as $tournament_participant)
						@if ($tournament_participant->pug)
							<tr>
								<td>
									<p>
										<img class="img-rounded" style="max-width: 6%;" src="{{ $tournament_participant->eventParticipant->user->avatar }}">
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $tournament_participant->eventParticipant->user->steamname }}
									</p>
								</td>
								<td>
									<p>
										@if ($tournament_participant->eventParticipant->seat)
											{{ $tournament_participant->eventParticipant->seat->seat }}
										@else
											Not Seated
										@endif 
									</p>
								</td>
							</tr>
						@endif
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-sm-6 col-xs-12">
		<h3>Teams</h3>
		<div class="table-responsive">
			<table class="table">
				 <thead>
					<tr>
						<th>
							Team Name
						</th>
						<th>
							
						</th>
						<th>
							
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($tournament->tournamentTeams as $tournament_team)
						<tr>
							<td>
								<h4>{{ $tournament_team->name }}</h4>
							</td>
							<td>
								<table class="table">
									@foreach ($tournament_team->tournamentParticipants as $participant)
										<tr>
											<td>  
												<img class="img-rounded" style="max-width: 8%;" src="{{ $participant->eventParticipant->user->avatar }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $participant->eventParticipant->user->steamname }}
											</td>
											<td>
												@if ($participant->eventParticipant->seat)
													{{ $participant->eventParticipant->seat->seat }}
												@else
													Not Seated
												@endif 
											</td>
										</tr>
									@endforeach
								</table>
							</td>
							<td>
								@if (!$tournament->getParticipant($user->active_event_participant->id))
									{{ Form::open(array('url'=>'/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/register', 'files' => true )) }}
										<input type="hidden" name="event_participant_id" value="{{ $user->active_event_participant->id }}">
										<input type="hidden" name="event_tournament_team_id" value="{{ $tournament_team->id }}">
										<button type="submit" name="action" value="sign_up" class="btn btn-default">Join Team</button>
									{{ Form::close() }}
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endif