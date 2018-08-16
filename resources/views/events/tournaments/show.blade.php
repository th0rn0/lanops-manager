@extends ('layouts.default')

@section ('page_title', $event->display_name . ' - ' . $tournament->display_name)

@section ('content')
	<div class="container">

		<!-- HEADER -->

		<div class="row">
			<div class="page-header">
				<h1>
					{{ $tournament->name }}
					<span class="pull-right">
						<small>
							<span class="label label-success">{{ $tournament->status }}</span>
							@if (!$tournament->getParticipant($user->active_event_participant->id) && $tournament->status != 'COMPLETE')
								<span class="label label-danger">Not Signed up</span>
							@endif
							@if ($tournament->getParticipant($user->active_event_participant->id) && $tournament->status != 'COMPLETE')
								<span class="label label-success">Signed up</span>
							@endif
						</small>
					</span>
				</h1>
				<h4>
					{{ $tournament->description }}
				</h4>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-md-3">
					<dl>
						<dt>
							Game
						</dt>
						<dd>
							{{ $tournament->game }}
						</dd>
						<dt>
							Team Sizes
						</dt>
						<dd>
							{{ $tournament->team_size }}
						</dd>
						<dt>
							Format:
						</dt>
						<dd>
							{{ $tournament->format }}
						</dd>
					</dl>
				</div>
				<div class="col-xs-12 col-sm-8 col-md-9">
					@if ($tournament->status == 'COMPLETE' && isset($tournament->challonge_participants))
						<div class="row">
							<div class="alert alert-success text-center">
								@foreach ($tournament->challonge_participants as $challonge_participant)
									<h2>{{ Helpers::getChallongeRankFormat($challonge_participant->final_rank) }} - {{ $challonge_participant->name }}</h2>
								@endforeach
							</div>
						</div>
					@endif

					<!-- PROGRESS -->
					@if ($tournament->status == 'LIVE')
						<h4 class="page-header">
							Next Match
						</h4>
						<div class="row">
							@foreach ($tournament->getNextMatches(2) as $match)
								<div class="col-xs-12 col-sm-4">
									<table class="table table-bordered table-condensed">
										<tbody>
											@php
												$scores[0] = 0;
												$scores[1] = 0;
												if ($match->scores_csv != "") {
													$scores = explode("-", $match->scores_csv, 2);
												}
												$context[0] = 'active';
												$context[1] = 'active';
												if ($scores[0] > $scores[1]) {
													$context[0] = 'success';
													$context[1] = 'danger';
												}
												if ($scores[0] < $scores[1]) {
													$context[0] = 'danger';
													$context[1] = 'success';
												}
												if ($scores[0] == $scores[1]) {
													$context[0] = 'warning';
													$context[1] = 'warning';
												}
											@endphp
											<tr>
												<td class="text-center " width="10%">
													1
												</td>
												<td class="{{ $context[0] }}">
													@if ($match->player1_id)
														{{ ($tournament->getParticipantByChallongeId($match->player1_id))->eventParticipant->user->steamname }}
													@endif
												</td>
											</tr>
											<tr>
												<td class="text-center " width="10%">
													2
												</td>
												<td class="{{ $context[1] }}">
													@if ($match->player2_id)
														{{ ($tournament->getParticipantByChallongeId($match->player2_id))->eventParticipant->user->steamname }}
													@endif
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							@endforeach
						</div>
					@endif

					@if ($tournament->status == 'OPEN' && !$tournament->getParticipant($user->active_event_participant->id) && $tournament->team_size == '1v1')
						{{ Form::open(array('url'=>'/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/register', 'files' => true )) }}
							<input type="hidden" name="event_participant_id" value="{{ $user->active_event_participant->id }}">
							<button type="submit" class="btn btn-default">Signup</button>
						{{ Form::close() }}
					@endif
					@if ($tournament->status == 'OPEN' && !$tournament->getParticipant($user->active_event_participant->id) && $tournament->team_size != '1v1')
						{{ Form::open(array('url'=>'/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/register/pug', 'files' => true )) }}
							<input type="hidden" name="event_participant_id" value="{{ $user->active_event_participant->id }}">
							<button type="submit" class="btn btn-default">PUG</button>
						{{ Form::close() }}
						{{ Form::open(array('url'=>'/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/register/team', 'files' => true )) }}
							<div class="form-group">
								{{ Form::label('team_name','Team Name',array('id'=>'','class'=>'')) }}
								{{ Form::text('team_name', '',array('id'=>'team_name','class'=>'form-control', 'required' => 'required')) }}
							</div>
							<input type="hidden" name="event_participant_id" value="{{ $user->active_event_participant->id }}">
							<button type="submit" class="btn btn-default">Create Team</button>
						{{ Form::close() }}
					@endif
					@if ($tournament->status == 'OPEN' && $tournament->getParticipant($user->active_event_participant->id))
						{{ Form::open(array('url'=>'/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/register/remove', 'files' => true )) }}
							<input type="hidden" name="event_participant_id" value="{{ $user->active_event_participant->id }}">
							<button type="submit" class="btn btn-default">Remove Signup</button>
						{{ Form::close() }}
					@endif
				</div>
			</div>
		</div>

		<!-- BRACKETS & STANDINGS -->

		@if (($tournament->status == 'LIVE' || $tournament->status == 'COMPLETE'))
			<div class="row">
				<div class="page-header">
					<h3>Brackets</h3>
				</div>
				<div class="progress">
					<div class="progress-bar" role="progressbar" aria-valuenow="{{ $tournament_standings['progress'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $tournament_standings['progress'] }}%;">
						{{ $tournament_standings['progress'] }}%
					</div>
				</div>
				@php
					$match_counter = 1;
				@endphp
				<div class="row">
					@foreach ($tournament->getMatches() as $round_number => $round)
							<div class="col-xs-12 col-sm-6 col-md-3">
								<h4 class="page-header">
									@if ($round_number == count($tournament->getMatches()) - 1)
										Finals
									@elseif ($round_number == count($tournament->getMatches()) - 2)
										Semi-Finals
									@elseif (substr($round_number, 0, 1) == '-')
										Losers Round {{ substr($round_number, 1, 1) }}
									@else
										Round {{ $round_number }}
									@endif
								</h4>
								@foreach ($round as $match)
									<table class="table table-bordered table-condensed">
										<tbody>
											@php
												$scores[0] = 0;
												$scores[1] = 0;
												if ($match->scores_csv != "") {
													$scores = explode("-", $match->scores_csv, 2);
												}
												$context[0] = 'active';
												$context[1] = 'active';
												if ($scores[0] > $scores[1]) {
													$context[0] = 'success';
													$context[1] = 'danger';
												}
												if ($scores[0] < $scores[1]) {
													$context[0] = 'danger';
													$context[1] = 'success';
												}
												if ($scores[0] == $scores[1]) {
													$context[0] = 'warning';
													$context[1] = 'warning';
												}
											@endphp
											<tr>
												<td rowspan="2" class="text-center" width="10%">
													{{ $match_counter }}
												</td>
												<td class="text-center " width="10%">
													1
												</td>
												<td class="{{ $context[0] }}">
													@if ($match->player1_id)
														{{ ($tournament->getParticipantByChallongeId($match->player1_id))->eventParticipant->user->steamname }}
														<span class="badge pull-right">{{ $scores[0] }}</span>
													@endif
													@if ($match->player1_is_prereq_match_loser && !$match->player1_id)
														<small><i>Loser of {{ ($match_counter - 1) }}</i></small>
													@endif
												</td>
											</tr>
											<tr>
												<td class="text-center " width="10%">
													2
												</td>
												<td class="{{ $context[1] }}">
													@if ($match->player2_id)
														{{ ($tournament->getParticipantByChallongeId($match->player2_id))->eventParticipant->user->steamname }}
														<span class="badge pull-right">{{ $scores[1] }}</span>
													@endif
													@if ($round_number != count($tournament->getMatches()) - 1 && $match->player2_is_prereq_match_loser && !$match->player2_id)
														<small><i>Loser of {{ ($match_counter - 2) }}</i></small>
													@endif
													@if ($round_number == count($tournament->getMatches()) - 1 && !$match->player2_is_prereq_match_loser && !$match->player2_id)
														<small><i>Winner of Losers Bracket</i></small>
													@endif
													@if ($round_number == count($tournament->getMatches()) - 1 && $match->player2_is_prereq_match_loser && !$match->player2_id)
														<small><i>Loser of {{ ($match_counter - 1) }} (if necessary)</i></small>
													@endif
												</td>
											</tr>
										</tbody>
									</table>
									@php
										$match_counter++
									@endphp
								@endforeach
				 			</div>
					@endforeach
	 			</div>
				<div class="page-header">
					<h3>Standings</h3>
				</div>
		 		@if ($tournament->team_size == '1v1')
					<div class="table-responsive">
						<table class="table">
							 <thead>
								<tr>
									<th>
										Player
									</th>
									<th>
										Win/Lose/Tie
									</th>
									<th>
										Points
									</th>
									<th>
										History
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($tournament_standings['final'] as $standings)
									<tr>
										<td>  
											{{ $standings['name'] }}
										</td>
										<td>
											{{ $standings['win'] }} / {{ $standings['lose'] }} / {{ $standings['tie'] }}
										</td>
										<td>
											{{ $standings['pts'] }}
										</td>
										<td>
											@foreach ($standings['history'] as $game)
												@if ($game == 'W') 
													<div class="col-xs-1">
														<span class="label label-success">W</span>
													</div>
												@endif
												@if ($game == 'L') 
													<div class="col-xs-1">
														<span class="label label-danger">L</span>
													</div>
												@endif
												@if ($game == 'T') 
													<div class="col-xs-1">
														<span class="label label-default">T</span>
													</div>
												@endif
											@endforeach
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@endif
			 	@if ($tournament->status == 'LIVE' && isset($tournament->matches) && $tournament->team_size != '1v1')
					<div class="table-responsive">
						<table class="table">
							 <thead>
								<tr>
									<th>
										Teams
									</th>
									<th>
										Win/Lose/Tie
									</th>
									<th>
										Points
									</th>
									<th>
										History
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($tournament_standings['final'] as $standings)
									<tr>
										<td>  
											{{ $standings['name'] }}
										</td>
										<td>
											{{ $standings['win'] }} / {{ $standings['lose'] }} / {{ $standings['tie'] }}
										</td>
										<td>
											{{ $standings['pts'] }}
										</td>
										<td>
											@foreach ($standings['history'] as $game)
												@if ($game == 'W') 
													<div class="col-xs-1">
														<span class="label label-success">W</span>
													</div>
												@endif
												@if ($game == 'L') 
													<div class="col-xs-1">
														<span class="label label-danger">L</span>
													</div>
												@endif
												@if ($game == 'T') 
													<div class="col-xs-1">
														<span class="label label-default">T</span>
													</div>
												@endif
											@endforeach
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@endif
			</div>
		@endif

		<!-- PARTICIPANTS -->
		
		<div class="row">
			<div class="page-header">
				<h3>Participants</h3>
			</div>

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
		</div>
	</div>


@endsection
