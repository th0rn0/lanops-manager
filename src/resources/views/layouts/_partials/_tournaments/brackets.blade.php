<div class="progress">
	<div class="progress-bar" role="progressbar" aria-valuenow="{{ $tournament->getStandings(null, true)->progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $tournament->getStandings(null, true)->progress }}%;">
		{{ $tournament->getStandings(null, true)->progress }}%
	</div>
</div>
@php
	$match_counter = 1;
@endphp
<div class="row">
	@php
		$matches = $tournament->getMatches();
	@endphp
	@foreach ($matches as $round_number => $round)
		<div class="col-xs-12 col-sm-6 col-md-3">
			<h4 class="page-header">
				@if (
					(
						(
							$round == end($matches)
						) ||
						(
							$round_number == count($matches) &&
							!Helpers::pregArrayKeyExists('/-$/',$matches)
						)
					) && (
						$tournament->format != 'round robin'
					)
				)
					Finals
				@elseif ($round_number == count($matches) - 2 && $tournament->format != 'round robin')
					Semi-Finals
				@elseif (substr($round_number, 0, 1) == '-' && $tournament->format != 'round robin')
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
									@if ($tournament->team_size != '1v1')
										{{ ($tournament->getTeamByChallongeId($match->player1_id))->name }}
									@else
										{{ ($tournament->getParticipantByChallongeId($match->player1_id))->eventParticipant->user->steamname }}
									@endif
									<span class="badge pull-right">{{ $scores[0] }}</span>
								@endif
								@if ($match->player1_is_prereq_match_loser && !$match->player1_id)
									<small><i>Loser of {{ ($match_counter - 1) }}</i></small>
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
										 			'{{ ($tournament->getParticipantByChallongeId($match->player1_id))->eventParticipant->user->steamname }}',
										 			'{{ ($tournament->getParticipantByChallongeId($match->player2_id))->eventParticipant->user->steamname }}'
									 			)" 
									 			data-toggle="modal"
									 			data-target="#submitScoresModal"
								 			>
								 				Submit Scores
								 			</button>
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
										{{ ($tournament->getParticipantByChallongeId($match->player2_id))->eventParticipant->user->steamname }}
									@endif
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