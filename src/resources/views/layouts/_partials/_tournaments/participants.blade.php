<!-- All Participants -->
@if (@$all)
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
					@if($tournament->team_size != '1v1' && ($admin && $user->admin))
						<th>
							PUG
						</th>
						<th>
							Team
						</th>
					@endif
				</tr>
			</thead>
			<tbody>
				@foreach ($tournament->tournamentParticipants as $tournament_participant)
					@php
						$context = 'default';
					@endphp
					@if (($tournament_participant->pug && !$tournament_participant->tournamentTeam) && (@$admin && $user->admin))
						@php
							$context = 'warning';
						@endphp
					@endif
					<tr class='{{ $context }}'>
						<td>
							<p style="padding-top:7px;">
								<img class="img-rounded" style="max-width: 4%;" src="{{$tournament_participant->eventParticipant->user->avatar}}">
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $tournament_participant->eventParticipant->user->steamname }}
								<small> - {{ $tournament_participant->eventParticipant->user->username }}</small>
							</p>
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
						@if ($tournament->team_size != '1v1' && ($admin && $user->admin))
							<td>
								@if($tournament_participant->pug)
									Yes
								@else
									No
								@endif
							</td>                        
							<td>
								{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/participants/' . $tournament_participant->id  . '/team')) }}
									<div class="form-group col-xs-12 col-sm-8">
										@if ($tournament->status != 'LIVE' || $tournament->status != 'COMPLETE' && (@$admin && $user->admin))
											{{ Form::select('event_tournament_team_id', [0 => 'None'] + $tournament->getTeams(), $tournament_participant->event_tournament_team_id, array('id'=>'name','class'=>'form-control')) }}
										@else
											{{ $tournament_participant->tournamentTeam->name }}
										@endif
									</div>
									<div class="form-group col-xs-12 col-sm-4">
										@if ($tournament->status == 'LIVE' || $tournament->status == 'COMPLETE')
											<button type="submit" class="btn btn-default btn-block" disabled>Update</button>  
										@else
											<button type="submit" class="btn btn-default btn-block">Update</button>  
										@endif
									</div>
								{{ Form::close() }}
							</td>
						@endif
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endif
<!-- Teams -->
@if ($tournament->team_size != '1v1')
	<h3>Teams</h3>
	<div class="table-responsive">
		<table class="table">
			 <thead>
				<tr>
					<th>
						Name
					</th>
					<th>
						Roster
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
						Name
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