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
	<h3>Teams</h3>
	<div class="table-responsive">
		<table class="table">
			 <thead>
				<tr>
					<th>
						Team Name
					</th>
					<th>
						Team Members
					</th>
					
				</tr>
			</thead>
			<tbody>
				@foreach ($tournament->tournamentTeams as $tournament_team)
					<tr>
						<td width="50%">
							<h4>{{ $tournament_team->name }}</h4>

						</td>
						<td>
							@if ($tournament_team->tournamentParticipants)
								@foreach ($tournament_team->tournamentParticipants as $participant)
									<img class="img-rounded" style="max-width: 8%;" src="{{ $participant->eventParticipant->user->avatar }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $participant->eventParticipant->user->steamname }}
									<span class="pull-right">
										@if ($participant->eventParticipant->seat)
											{{ $participant->eventParticipant->seat->seat }}
										@else
											Not Seated
										@endif
									</span>
									<br><br>
								@endforeach
							@else
								No one yet
							@endif
						</td>
						
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<h3>PUGs</h3>
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
@endif