@extends ('layouts.admin-default')

@section ('page_title', $tournament->name . ' - Tournaments')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Tournaments - {{ $tournament->name }}</h1>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/events/">Events</a>
			</li>
			<li>
				<a href="/admin/events/{{ $event->slug }}">{{ $event->display_name }}</a> 
			</li>
			<li>
				<a href="/admin/events/{{ $event->slug }}/tournaments">Tournaments</a>
			</li>
			<li class="active">
				{{ $tournament->name }}
			</li>
		</ol>
	</div>
</div>

@include ('layouts._partials._admin._event.dashMini')

<div class="row">
	<div class="col-lg-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> Participants
				<a target="_blank" href="{{ $tournament->getChallongeUrl() }}" class="btn btn-info btn-xs pull-right">Go to Brackets</a>
			</div>
			<div class="panel-body">
				@if ($tournament->team_size != '1v1')
					<h3>Teams</h3>
					<div class="dataTable_wrapper">
						<table width="100%" class="table table-striped table-hover" id="participant_table">
							<thead>
								<tr>
									<th>Name</th>
									<th>Roster</th>
									<th hidden>Wins</th>
									<th hidden>Loses</th>
									<th hidden>Draws</th>
									<th hidden>Edit</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($tournament->tournamentTeams as $tournament_team)
									<tr>
										<td>
											{{ $tournament_team->name }}
										</td>
										<td>
											@foreach ($tournament_team->tournamentParticipants as $participant)
												{{ $participant->eventParticipant->user->steamname }}
												-
												@if($participant->eventParticipant->seat)
													{{ $participant->eventParticipant->seat->seat }}
												@else
													Not Seated
												@endif 
												<br>
											@endforeach
										</td>
										<td hidden>
											0
										</td>
										<td hidden>
											0
										</td>
										<td hidden>
											0
										</td>
										<td hidden>
											edit me
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<hr>
				@endif
				<h3>Participants</h3>
				<div class="dataTable_wrapper">
					<table width="100%" class="table table-striped table-hover" id="participant_table">
						<thead>
							<tr>
								<th>Name</th>
								@if($tournament->team_size != '1v1')
									<th>PUG</th>
									<th>Team</th>
									<th></th>
								@endif
								<th hidden>Wins</th>
								<th hidden>Loses</th>
								<th hidden>Draws</th>
								<th hidden>Edit</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($tournament->tournamentParticipants as $participant)
								{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/participants/' . $participant->id  . '/team')) }}
									<tr>
										<td>
											{{ $participant->eventParticipant->user->steamname }}
											-
											@if($participant->eventParticipant->seat)
												{{ $participant->eventParticipant->seat->seat }}
											@else
												Not Seated
											@endif 
										</td>
										@if ($tournament->team_size != '1v1')
											<td>
												@if($participant->pug)
													Yes
												@else
													No
												@endif
											</td>                        
											<td>
												@if ($tournament->status == 'LIVE' || $tournament->status == 'COMPLETE')
													{{ $participant->getTeamName() }}
												@else
													<div class="form-group">
														{{ Form::select('event_tournament_team_id', [0 => 'None'] + $tournament->getTeamsArray(), $participant->event_tournament_team_id, array('id'=>'name','class'=>'form-control')) }}
													</div>
												@endif
											</td>
											<td>
												@if ($tournament->status == 'LIVE' || $tournament->status == 'COMPLETE')
													<button type="submit" class="btn btn-default" disabled>Update</button>  
												@else
													<button type="submit" class="btn btn-default">Update</button>  
												@endif
											</td> 
										{{ Form::close() }}
									@endif
									<td hidden>
										0
									</td>
									<td hidden>
										0
									</td>
									<td hidden>
										0
									</td>
									<td hidden>
										edit me
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>

	</div>
	<div class="col-lg-4">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Settings
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug )) }}
					@if ($errors->any())
					  	<div class="alert alert-danger">
					        <ul>
					          	@foreach ($errors->all() as $error)
					            	<li>{{ $error }}</li>
					          	@endforeach
					        </ul>
					  	</div>
					@endif
					<div class="form-group">
						{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
						@if ($tournament->status == 'LIVE' || $tournament->status == 'COMPLETE')
							{{ Form::text('name', $tournament->name ,array('id'=>'name','class'=>'form-control', 'disabled'=>'true')) }}
						@else
							{{ Form::text('name', $tournament->name ,array('id'=>'name','class'=>'form-control')) }}
						@endif
					</div> 
					<div class="row">
						<div class="col-lg-6 col-sm-12 form-group">
							{{ Form::label('status','Status',array('id'=>'','class'=>'')) }}
							@if ($tournament->status == 'LIVE' || $tournament->status == 'COMPLETE')
								{{ 
									Form::select(
										'status',
										array(
											'DRAFT'=>'DRAFT',
											'OPEN'=>'OPEN',
											'CLOSED'=>'CLOSED',
											'LIVE'=>'LIVE',
											'COMPLETE'=>'COMPLETE'
										),
										$tournament->status,
										array(
											'id'=>'status',
											'class'=>'form-control',
											'disabled'=>'true'
										)
									)
								}}
							@else
								{{ 
									Form::select(
										'status',
										array(
											'DRAFT'=>'DRAFT',
											'OPEN'=>'OPEN',
											'CLOSED'=>'CLOSED',
											'LIVE'=>'LIVE',
											'COMPLETE'=>'COMPLETE'
										),
										$tournament->status,
										array(
											'id'=>'status',
											'class'=>'form-control'
										)
									)
								}}
							@endif
						</div> 
						<div class="col-lg-6 col-sm-12 form-group">
							{{ Form::label('format','Format',array('id'=>'','class'=>'')) }}
							{{ Form::text('format', ucfirst($tournament->format) .  ' - ' . $tournament->team_size ,array('id'=>'format','class'=>'form-control', 'disabled'=>'true')) }}
						</div> 
					</div>
					<div class="form-group">
						{{ Form::label('game','Game',array('id'=>'','class'=>'')) }}
						{{ Form::text('game', $tournament->game ,array('id'=>'game','class'=>'form-control')) }}
					</div>
					@if ($tournament->status != 'LIVE' && $tournament->status != 'COMPLETE')
						<div class="form-group">
							{{ Form::label('description','Description',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('description', $tournament->description,array('id'=>'description','class'=>'form-control', 'rows'=>'2')) }}
						</div>
					@endif
					@if ($tournament->status != 'LIVE' && $tournament->status != 'COMPLETE')
						<div class="form-group">
							<button type="submit" class="btn btn-default">Submit</button>
						</div>
					@endif
				{{ Form::close() }}
				<div class="form-group">
					@if (count($tournament->tournamentParticipants) >= 2 && $tournament->status != 'LIVE' && $tournament->status != 'COMPLETE')
						{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/start')) }}
							<button type="submit" name="action" value="start" class="btn btn-default">Start Tournament</button>
						{{ Form::close() }}
					@endif
					@if ($tournament->status == 'LIVE')
						{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/finalize')) }}
							<button type="submit" class="btn btn-default">Finalize Tournament</button>
						{{ Form::close() }}
					@endif
				</div>
				@if ($tournament->status != 'COMPLETE')
					<hr>
					{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
						{{ Form::hidden('_method', 'DELETE') }}
						<button type="submit" class="btn btn-danger">Delete</button>
					{{ Form::close() }}
				@endif
			</div>
		</div>

	</div>
</div>

@endsection