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
					@if($tournament->team_size != '1v1' && (@$admin && $user->admin))
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
				@foreach ($tournament->tournamentParticipants as $tournamentParticipant)
					@php
						$context = 'default';
					@endphp
					@if (($tournamentParticipant->pug && !$tournamentParticipant->tournamentTeam) && (@$admin && $user->admin))
						@php
							$context = 'warning';
						@endphp
					@endif
					<tr class='{{ $context }}'>
						<td>
							<p style="padding-top:7px;">
								<img alt="{{ $tournamentParticipant->eventParticipant->user->username }}'s Avatar" class="img-rounded" style="max-width: 4%;" src="{{ $tournamentParticipant->eventParticipant->user->avatar }}">
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $tournamentParticipant->eventParticipant->user->username }}
								<small> - {{ $tournamentParticipant->eventParticipant->user->username }}</small>
							</p>
						</td>
						<td>
							<p style="padding-top:15px;">
								@if ($tournamentParticipant->eventParticipant->seat)
									{{ $tournamentParticipant->eventParticipant->seat->seat }}
								@else
									Not Seated
								@endif 
							</p>
						</td>
						@if ($tournament->team_size != '1v1' && (@$admin && $user->admin))
							<td>
								@if($tournamentParticipant->pug)
									Yes
								@else
									No
								@endif
							</td>                        
							<td>
								{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/participants/' . $tournamentParticipant->id  . '/team')) }}
									<div class="form-group col-xs-12 col-sm-8">
										@if ($tournament->status != 'LIVE' || $tournament->status != 'COMPLETE' && (@$admin && $user->admin))
											{{ Form::select('event_tournament_team_id', [0 => 'None'] + $tournament->getTeams(), $tournamentParticipant->event_tournament_team_id, array('id'=>'name','class'=>'form-control')) }}
										@else
											{{ $tournamentParticipant->tournamentTeam->name }}
										@endif
									</div>
									<div class="form-group col-xs-12 col-sm-4">
										@if ($tournament->status == 'LIVE' || $tournament->status == 'COMPLETE')
											<button type="submit" class="btn btn-default btn-sm btn-block" disabled>Update</button>  
										@else
											<button type="submit" class="btn btn-default btn-sm btn-block">Update</button>  
										@endif
									</div>
								{{ Form::close() }}
							</td>
						@endif
						@if (@$admin && $user->admin)
							<td>
								{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/participants/' . $tournamentParticipant->id  . '/remove')) }}
									<input type="hidden" name="event_participant_id" value="{{ $tournamentParticipant->event_participant_id }}">
									<button type="submit" class="btn btn-danger btn-sm btn-block">Remove</button>
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
				@foreach ($tournament->tournamentTeams as $tournamentTeam)
					<tr>
						<td width="50%">
							<h4>{{ $tournamentTeam->name }}</h4>
						</td>
						<td>
							@if ($tournamentTeam->tournamentParticipants)
								@foreach ($tournamentTeam->tournamentParticipants as $participant)
									<img alt="{{ $participant->eventParticipant->user->username }}'s Avatar" class="img-rounded" style="max-width: 8%;" src="{{ $participant->eventParticipant->user->avatar }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $participant->eventParticipant->user->username }}
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
				@foreach ($tournament->tournamentParticipants as $tournamentParticipant)
					@if ($tournamentParticipant->pug)
						<tr>
							<td>
								<p>
									<img alt="{{ $tournamentParticipant->eventParticipant->user->username }}'s Avatar" class="img-rounded" style="max-width: 6%;" src="{{ $tournamentParticipant->eventParticipant->user->avatar }}">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $tournamentParticipant->eventParticipant->user->username }}
								</p>
							</td>
							<td>
								<p>
									@if ($tournamentParticipant->eventParticipant->seat)
										{{ $tournamentParticipant->eventParticipant->seat->seat }}
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