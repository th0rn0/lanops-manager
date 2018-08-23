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
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-md-3">
					<h4>
						{{ $tournament->description }}
					</h4>
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

					<!-- REGISTRATION -->
					<h4 class="page-header">
						Registration
					</h4>

					<!-- Team Registration -->
					@if ($tournament->team_size != '1v1' && $tournament->status == 'OPEN' && !$tournament->getParticipant($user->active_event_participant->id))
						<div class="row border-between">
							<div class="col-xs-12 col-sm-6">
								<label>Join a Team</label>
								<div class="row">
									@foreach ($tournament->tournamentTeams as $tournament_team)
										<div class="col-xs-6 col-sm-6">
											{{ Form::open(array('url'=>'/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/register', 'files' => true )) }}
												<input type="hidden" name="event_participant_id" value="{{ $user->active_event_participant->id }}">
												<input type="hidden" name="event_tournament_team_id" value="{{ $tournament_team->id }}">
												<button type="submit" name="action" value="sign_up" class="btn btn-primary btn-block">{{ $tournament_team->name }}</button>
											{{ Form::close() }}
										</div>
									@endforeach
								</div>
							</div>
							<div class="col-xs-12 col-sm-6">
								<label>Create a Team</label>
								{{ Form::open(array('url'=>'/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/register/team', 'files' => true )) }}
									<div class="row">
										<div class="form-group col-sm-6 col-xs-12">
											{{ Form::text('team_name', '',array('id'=>'team_name','class'=>'form-control', 'required' => 'required', 'placeholder' => 'Team Name')) }}
										</div>
										<div class="form-group col-sm-6 col-xs-12">
											<button type="submit" class="btn btn-primary btn-block">Create Team</button>
										</div>
									</div>
									<input type="hidden" name="event_participant_id" value="{{ $user->active_event_participant->id }}">
								{{ Form::close() }}
								<hr>
								{{ Form::open(array('url'=>'/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/register/pug', 'files' => true )) }}
									<input type="hidden" name="event_participant_id" value="{{ $user->active_event_participant->id }}">
									<button type="submit" class="btn btn-primary btn-block">Sign up as PUG</button>
								{{ Form::close() }}
							</div>
						</div>
					@endif



					@if ($tournament->status == 'OPEN' && !$tournament->getParticipant($user->active_event_participant->id) && $tournament->team_size == '1v1')
						{{ Form::open(array('url'=>'/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/register', 'files' => true )) }}
							<input type="hidden" name="event_participant_id" value="{{ $user->active_event_participant->id }}">
							<button type="submit" class="btn btn-default">Signup</button>
						{{ Form::close() }}
					@endif

					@if ($tournament->status == 'OPEN' && $tournament->getParticipant($user->active_event_participant->id))
						{{ Form::open(array('url'=>'/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/register/remove', 'files' => true )) }}
							<div class="row">
								<div class="col-xs-12 col-md-6">
									@if ($tournament->team_size != '1v1')
										@if (($tournament->getParticipant($user->active_event_participant->id))->pug)
											<h4>You are signed up as a PUG. Team will be assigned shortly.</h4>
										@else
											<h4>You are signed up with {{ ($tournament->getParticipant($user->active_event_participant->id))->tournamentTeam->name }}</h4>
										@endif
									@else
										<h4>You are signed up</h4>
									@endif
									<input type="hidden" name="event_participant_id" value="{{ $user->active_event_participant->id }}">
									<button type="submit" class="btn btn-primary btn-block">Remove Signup</button>
								</div>
							</div>
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
				@include ('layouts._partials._tournaments.brackets')
			</div>
			<div class="row">
				<div class="page-header">
					<h3>Standings</h3>
				</div>
				@include ('layouts._partials._tournaments.standings')
			</div>
		@endif

		<!-- PARTICIPANTS -->
		
		@if (($tournament->status != 'LIVE' && $tournament->status != 'COMPLETE'))
			<div class="row">
				<div class="page-header">
					<h3>Participants</h3>
				</div>
				@include ('layouts._partials._tournaments.participants')
			</div>
		@endif
	</div>

@endsection
