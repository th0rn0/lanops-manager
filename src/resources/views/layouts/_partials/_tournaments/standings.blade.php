@if ($tournament->team_size == '1v1')
	<div class="table-responsive">
		<table class="table">
			 <thead>
				<tr>
					<th>
						Player
					</th>
					<th>
						Seat
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
				@foreach ($tournament->getStandings('desc', true)->final as $standings)
					<tr>
						<td>  
							<img class="img-rounded" style="max-width: 6%;" src="{{ ($tournament->getParticipantByChallongeId($standings->id))->eventParticipant->user->avatar }}">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ ($tournament->getParticipantByChallongeId($standings->id))->eventParticipant->user->username }}
						</td>
						<td>
							@if (($tournament->getParticipantByChallongeId($standings->id))->eventParticipant->seat)
								{{ ($tournament->getParticipantByChallongeId($standings->id))->eventParticipant->seat->seat }}
							@else
								Not Seated
							@endif
						<td>
							<p>
								{{ $standings->win }} / {{ $standings->lose }} / {{ $standings->tie }}
							</p>
						</td>
						<td>
							<p>
								{{ $standings->pts }}
							</p>
						</td>
						<td>
							@foreach ($standings->history as $game)
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
@if ($tournament->team_size != '1v1')
	<div class="table-responsive">
		<table class="table">
			 <thead>
				<tr>
					<th>
						Team
					</th>
					<th>
						Roster
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
				@foreach ($tournament->getStandings('desc', true)->final as $standings)
					<tr>
						<td>
							{{ $standings->name }}
						</td>
						<td>
							@if (($tournament->getTeamByChallongeId($standings->id)->tournamentParticipants))
								@foreach (($tournament->getTeamByChallongeId($standings->id)->tournamentParticipants) as $participant)
									<img class="img-rounded" style="max-width: 6%;" src="{{ $participant->eventParticipant->user->avatar }}">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $participant->eventParticipant->user->username }}<br>
								@endforeach
							@endif
						</td>
						<td>
							{{ $standings->win }} / {{ $standings->lose }} / {{ $standings->tie }}
						</td>
						<td>
							{{ $standings->pts }}
						</td>
						<td>
							<div class="row">
								@foreach ($standings->history as $game)
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
							</div>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endif