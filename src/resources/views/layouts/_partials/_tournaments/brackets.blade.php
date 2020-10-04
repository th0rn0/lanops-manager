<div class="progress">
	<div class="progress-bar" role="progressbar" aria-valuenow="{{ $tournament->getStandings(null, true)->progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $tournament->getStandings(null, true)->progress }}%;">
		{{ $tournament->getStandings(null, true)->progress }}%
	</div>
</div>
@php
	$matchCounter = 1;
@endphp
<div class="row">
	@php
		$matches = $tournament->getMatches();
	@endphp
	@foreach ($matches as $roundNumber => $round)
		<div class="col-xs-12 col-sm-6 col-md-3">
			<h4 class="page-header">
				@if (
					(
						(
							$round == end($matches)
						) ||
						(
							$roundNumber == count($matches) &&
							!Helpers::pregArrayKeyExists('/-$/',$matches)
						)
					) && (
						$tournament->format != 'round robin'
					)
				)
					Finals
				@elseif ($roundNumber == count($matches) - 2 && $tournament->format != 'round robin')
					Semi-Finals
				@elseif (substr($roundNumber, 0, 1) == '-' && $tournament->format != 'round robin')
					Losers Round {{ substr($roundNumber, 1, 1) }}
				@else
					Round {{ $roundNumber }}
				@endif
			</h4>
			@foreach ($round as $match)
				@php
					$matchserver = App\EventTournamentServer::getTournamentServer($match->id);
				@endphp
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
								{{ $matchCounter }}
							</td>
							<td class="text-center " width="10%">
								1
							</td>
							<td class="{{ $context[0] }}">
								@if ($match->player1_id)
									@if ($tournament->team_size != '1v1')
										{{ ($tournament->getTeamByChallongeId($match->player1_id))->name }}
									@else
										{{ ($tournament->getParticipantByChallongeId($match->player1_id))->eventParticipant->user->username }}
									@endif
									<span class="badge pull-right">{{ $scores[0] }}</span>
								@endif
								@if ($match->player1_is_prereq_match_loser && !$match->player1_id)
									<small><i>Loser of {{ ($matchCounter - 1) }}</i></small>
								@endif
							</td>
							@if ( @$admin && $user->admin )
								<td rowspan="2" class="text-center" width="10%">
									@if ($match->state == 'open' && ($match->player2_id != null && $match->player1_id != null))
										@if ($tournament->team_size != '1v1')
											<button 
										 		class="btn btn-sm btn-primary" 
										 		onclick="submitScores(
										 			'{{ $match->id }}',
										 			'{{ ($tournament->getTeamByChallongeId($match->player1_id))->name }}',
										 			'{{ ($tournament->getTeamByChallongeId($match->player2_id))->name }}'
									 			)" 
									 			data-toggle="modal"
									 			data-target="#submitScoresModal"
								 			>
								 				Submit Scores
								 			</button>
										@else
										 	<button 
										 		class="btn btn-sm btn-primary" 
										 		onclick="submitScores(
										 			'{{ $match->id }}',
										 			'{{ ($tournament->getParticipantByChallongeId($match->player1_id))->eventParticipant->user->username }}',
										 			'{{ ($tournament->getParticipantByChallongeId($match->player2_id))->eventParticipant->user->username }}'
									 			)" 
									 			data-toggle="modal"
									 			data-target="#submitScoresModal"
								 			>
								 				Submit Scores
								 			</button>
							 			@endif
										@if ( !isset($matchserver) && !isset($matchserver->gameServer) )
											<button class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#selectServerModal">Select Server</button>
											<!-- Select Command Modal -->
											<div class="modal fade" id="selectServerModal" tabindex="-1" role="dialog" aria-labelledby="selectServerModalLabel" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
															<h4 class="modal-title" id="selectServerModalLabel">Select Server</h4>
														</div>
														{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug .'/match/' . $match->id , 'id'=>'selectServerModal')) }}
															<div class="modal-body">
																<div class="form-group">
																	{{ Form::label('gameServer','Server',array('id'=>'','class'=>'')) }}
																	{{ Form::select('gameServer', $tournament->game->getGameServerSelectArray(), null, array('id'=>'gameServer','class'=>'form-control')) }}
																</div>	
															</div>
															<div class="modal-footer">
																<button type="submit" class="btn btn-success">Execute</button>
																<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
															</div>
														{{ Form::close() }}
													</div>
												</div>
											</div>
										@else
											<button class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#executeServerCommandModal">Commands</button>
											<!-- execute Command Modal -->
											<div class="modal fade" id="executeServerCommandModal" tabindex="-1" role="dialog" aria-labelledby="executeServerCommandModalLabel" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
															<h4 class="modal-title" id="executeServerCommandModalLabel">Execute Server Command</h4>
														</div>
														<div class="modal-body">
															@foreach ($tournament->game->getMatchCommands() as $matchCommand)
																{{ Form::open(array('url'=>'/admin/games/' . $tournament->game->slug . '/gameservercommands/execute/' . $matchserver->gameServer->slug .'/' . $tournament->slug, 'id'=>'executeServerCommandModal')) }}
																	{{ Form::hidden('command', $matchCommand->id) }}	
																	{{ Form::hidden('challonge_match_id', $matchserver->challonge_match_id) }}	
																	challonge_match_id
																	<div class="row row-seperator">
																		<div class="col-xs-12 col-md-3">
																			<h4>{{ $matchCommand->name }}</h4>
																		</div>
																		<div class="col-xs-12 col-md-6">
																			<div class="row">
																				@foreach(App\GameServerCommandParameter::getParameters($matchCommand->command) as $gameServerCommandParameter)
																					<div class="form-group col-sm-12  col-md-6">
																						{{ Form::label($gameServerCommandParameter->slug, $gameServerCommandParameter->name, array('id'=>'','class'=>'')) }}
																						{{ Form::select($gameServerCommandParameter->slug, $gameServerCommandParameter->getParameterSelectArray(), null, array('id'=>$gameServerCommandParameter->slug,'class'=>'form-control')) }}
																					</div>
																				@endforeach
																			</div>
																		</div>
																		<div class="col-xs-12 col-md-3">
																			<button type="submit" class="btn btn-success">Execute</button>
																		</div>
																	</div>
																{{ Form::close() }}
															@endforeach
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
														</div>	
														<!-- {{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug .'/match/' . $match->id , 'id'=>'selectServerModal')) }}
															<div class="modal-body">
																<div class="form-group">
																	{{ Form::label('gameServer','Server',array('id'=>'','class'=>'')) }}
																	{{ Form::select('gameServer', $tournament->game->getGameServerSelectArray(), null, array('id'=>'gameServer','class'=>'form-control')) }}
																</div>	
															</div>
															<div class="modal-footer">
																<button type="submit" class="btn btn-success">Execute</button>
																<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
															</div>
														{{ Form::close() }} -->
													</div>
												</div>
											</div>
										@endif
								 	@endif
								</td>
							@endif
						</tr>
						<tr>
							<td class="text-center " width="10%">
								2
							</td>
							<td class="{{ $context[1] }}">
								@if ($match->player2_id)
									@if ($tournament->team_size != '1v1')
										{{ ($tournament->getTeamByChallongeId($match->player2_id))->name }}
									@else
										{{ ($tournament->getParticipantByChallongeId($match->player2_id))->eventParticipant->user->username }}
									@endif
									<span class="badge pull-right">{{ $scores[1] }}</span>
								@endif
								@if ($roundNumber != count($tournament->getMatches()) - 1 && $match->player2_is_prereq_match_loser && !$match->player2_id)
									<small><i>Loser of {{ ($matchCounter - 2) }}</i></small>
								@endif
								@if ($roundNumber == count($tournament->getMatches()) - 1 && !$match->player2_is_prereq_match_loser && !$match->player2_id)
									<small><i>Winner of Losers Bracket</i></small>
								@endif
								@if ($roundNumber == count($tournament->getMatches()) - 1 && $match->player2_is_prereq_match_loser && !$match->player2_id)
									<small><i>Loser of {{ ($matchCounter - 1) }} (if necessary)</i></small>
								@endif
							</td>
						</tr>

						{{-- Connection is only possible if a gameServer is assigned --}}
						@if($match->state == 'open' && isset($matchserver) && isset($matchserver->gameServer) && ($tournament->game->connect_game_url || $tournament->game->connect_game_command))
							@php
								$availableParameters = new \stdClass();
								$availableParameters->game = $tournament->game;
								$availableParameters->event = $tournament->event;
								$availableParameters->tournament = $tournament;
								$availableParameters->gameServer = $matchserver->gameServer;
								$availableParameters->match = $tournament->getMatch($matchserver->challonge_match_id);
							@endphp

							<tr>
								<td colspan="3"> 
									@if($tournament->game->connect_game_url)
										<a class="btn btn-primary btn-block" id="connectGameUrl" href="{{ Helpers::resolveServerCommandParameters($tournament->game->connect_game_url, NULL, $availableParameters) }}" role="button">Join Game</a>
									@endif
									@if($tournament->game->connect_game_command)
										<div class="input-group" style="width: 100%">
											<input class="form-control" id="connectGameCommand{{ $availableParameters->match->id }}" type="text" readonly value="{{ Helpers::resolveServerCommandParameters($tournament->game->connect_game_command, NULL, $availableParameters) }}">
											<span class="input-group-btn">
											  <button class="btn btn-outline-secondary" type="button" onclick="copyToClipBoard('connectGameCommand{{$availableParameters->match->id}}')"><i class="far fa-clipboard"></i></button>
											</div>
										</div>
									@endif
								</td>
							</tr>
						@endif
					</tbody>
				</table>
				@php
					$matchCounter++
				@endphp
			@endforeach
			</div>
	@endforeach
</div>

@if (@$admin && $user->admin)
	<!-- Modals -->
	<div class="modal fade" id="submitScoresModal" tabindex="-1" role="dialog" aria-labelledby="submitScoresModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="submitScoresModalLabel">Submit Scores</h4>
				</div>
				<div class="modal-body">
					{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/match', 'class'=>'form-horizontal')) }}
						<div class="form-group">
							{{ Form::label('player1_score','',array('id'=>'player1_score_lbl','class'=>'col-xs-6 col-sm-9 text-left')) }}
							<div class="col-xs-6 col-sm-3 text-left">
								{{ Form::number('player1_score', 0, array('id'=>'player1_score','class'=>'form-control')) }}
							</div>
						</div>
						<hr>
						<div class="form-group">
							{{ Form::label('player2_score','',array('id'=>'player2_score_lbl','class'=>'col-xs-6 col-sm-9 text-left')) }}
							<div class="col-xs-6 col-sm-3 text-left">
								{{ Form::number('player2_score', 0, array('id'=>'player2_score','class'=>'form-control')) }}
							</div>
						</div>
						<hr>
						<h4>Verify Winner</h4>
						<div class='radio center-text'>
							<label class="radio-inline" id="player1_verify_lbl">
								<input type="radio" name="player_winner_verify" id="player1_verify" value="player1"><span id="player1_verify_span"></span>
							</label>
							<label class="radio-inline" id="player2_verify_lbl">
								<input type="radio" name="player_winner_verify" id="player2_verify" value="player2"><span id="player2_verify_span"></span>
							</label>
						</div>
						{{ Form::hidden('tournament_match_id', null, array('id'=>'tournament_match_id','class'=>'form-control')) }}
						<br>
						<button type="submit" class="btn btn-default">Submit</button>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
	<!-- JavaScript-->
	<script>
		function submitScores(match_id, player1_name, player2_name)
		{
			$("#tournament_match_id").val(match_id);
			$('[id$=player1_score_lbl]').text(player1_name);
			$('[id$=player1_verify_span]').text(player1_name);
			$('[id$=player2_score_lbl]').text(player2_name);
			$('[id$=player2_verify_span]').text(player2_name);
		}
		
	</script>
@endif

<script>
	function copyToClipBoard(inputId) {
		/* Get the text field */
		var copyText = document.getElementById(inputId);

		/* Select the text field */
		copyText.select();
		copyText.setSelectionRange(0, 99999); /*For mobile devices*/

		/* Copy the text inside the text field */
		document.execCommand("copy");

		/* Alert the copied text */
		alert("Copied the text: " + copyText.value);
	}
</script>
