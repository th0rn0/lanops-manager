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
				@foreach ($tournament->getStandings('desc', true)->final as $standings)
					<tr>
						<td>  
							<img class="img-rounded" style="max-width: 6%;" src="{{ ($tournament->getParticipantByChallongeId($standings->id))->eventParticipant->user->avatar }}">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ ($tournament->getParticipantByChallongeId($standings->id))->eventParticipant->user->steamname }}
						</td>
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
				@foreach ($tournament->getStandings('desc', true)->final as $standings)
					<tr>
						<td>  
							{{ $standings->name }}
						</td>
						<td>
							{{ $standings->win }} / {{ $standings->lose }} / {{ $standings->tie }}
						</td>
						<td>
							{{ $standings->pts }}
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