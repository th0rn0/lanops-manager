@extends ('layouts.admin-default')

@section ('page_title', 'Tournaments - ' . $tournament->name)

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">
			Tournament - {{ $tournament->name }}
			@if ($tournament->isComplete())
				<small> - Ended</small>
			@endif
		</h3>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/tournaments/">Tournaments</a>
			</li>
			<li class="active">
				{{ $tournament->name }}
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> Participants
			</div>
			<div class="panel-body">
				{{-- @if (
					($tournament->hasEvent() && $tournament->event->getEventParticipant()) ||
					(!$tournament->hasEvent())
					)
					@if (Auth::user() && !$tournament->isUserSignedUp(Auth::user()) && $tournament->signupsOpen() && !$tournament->hasTeams())
						{{ Form::open(array('url'=>'/tournaments/' . $tournament->slug . '/register')) }}
							<button type="submit" class="btn btn-success btn-sm btn-block">Signup</button>
						{{ Form::close() }}
					@elseif (Auth::user() && !$tournament->isUserSignedUp(Auth::user()) && $tournament->signupsOpen() && $tournament->hasTeams())
						{{ Form::open(array('url'=>'/tournaments/' . $tournament->slug . '/registerTeam')) }}
							<div class="row">
								<div class="col-xs-12 col-md-6 form-group @error('team_name') has-error @enderror">
									{{ Form::label('team_name','Team Name',array('id'=>'','class'=>'')) }}
									<input id="team_name" type="team_name" class="form-control" name="team_name" required>
								</div>
								<div class="col-xs-12 col-md-6 form-group @error('team_password') has-error @enderror">
									{{ Form::label('team_password','Team Password',array('id'=>'','class'=>'')) }}
									<input id="team_password" type="team_password" class="form-control" name="team_password">
								</div>
							</div>
							<button type="submit" class="btn btn-success btn-sm btn-block">Register Team</button>
						{{ Form::close() }}
					@elseif (Auth::user() && $tournament->isUserSignedUp(Auth::user()) && $tournament->signupsOpen())
						{{ Form::open(array('url'=>'/tournaments/' . $tournament->slug . '/unregister')) }}
							<button type="submit" class="btn btn-danger btn-sm btn-block">Remove Signup</button>
						{{ Form::close() }}
					@endif
				@endif --}}
				@if ($tournament->hasTeams())
					<table width="100%" class="table table-striped table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th>Team Name</th>
								<th>Participants</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@if ($tournament->teams)
								@foreach ($tournament->teams as $team)
									<tr class="table-row odd gradeX">
										<td width="30%">{{ $team->name }} - {{ $team->getParticipantCount() }}/{{ $tournament->team_size }}</td>
										<td>
											@include('layouts._partials._tournaments.participantsList', ['participants' => $team->participants])
										</td>
										<td>
											{{-- @if (
												Auth::user() && !$tournament->isUserSignedUp(Auth::user()) && 
												$tournament->signupsOpen() && 
												(($tournament->hasEvent() && $tournament->event->getEventParticipant()) || !$tournament->hasEvent()))
												{{ Form::open(array('url'=>'/tournaments/' . $tournament->slug . '/register')) }}
													{{ Form::hidden('tournament_team_id', $team->id) }}
													<button type="submit" class="btn btn-success btn-sm btn-block">Join Team</button>
												{{ Form::close() }}
											@endif --}}
										</td>
									</tr>
								@endforeach
							@endif
						</tbody>
					</table>
				@elseif ($tournament->hasParticipants())
					@include('layouts._partials._tournaments.participantsList', ['participants' => $tournament->participants])
				@endif
				{{-- <table width="100%" class="table table-striped table-hover" id="dataTables-example">
					<thead>
						<tr>
							<th>Avatar</th>
							<th>User</th>
							<th>Remove</th>
						</tr>
					</thead>
					<tbody>
						@if ($tournament->participants)
							@foreach ($tournament->participants as $participant)
								<tr class="table-row odd gradeX">
									<td width="30%"><img class="img-responsive img-rounded" src="{{ $participant->user->avatar }}"/></td>
									<td>
										{{ $participant->user->username }}
										@if ($participant->user->steamid)
											<br><span class="text-muted"><small>Steam: {{ $participant->user->steamname }}</small></span>
										@endif
									</td>
									<td>

									</td>
								</tr>
							@endforeach
						@endif
					</tbody>
				</table> --}}
			</div>  
		</div>
	</div>
	<div class="col-xs-12 col-sm-4">
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-pencil fa-fw"></i> Edit {{ $tournament->name }}
			</div>
			<div class="panel-body">
				{{ Form::label('name','Tournament Signup Link:',array('id'=>'','class'=>'')) }}
				<a href="{{ $_SERVER['REQUEST_SCHEME'] }}://{{ $_SERVER['HTTP_HOST'] }}/tournaments/{{ $tournament->slug }}">
					{{ $_SERVER['REQUEST_SCHEME'] }}://{{ $_SERVER['HTTP_HOST'] }}/tournaments/{{ $tournament->slug }}
				</a>
				{{ Form::open(array('url'=>'/admin/tournaments/' . $tournament->slug, 'files' => 'true')) }}
					<div class="form-group">
						{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
						{{ Form::text('name', $tournament->name, array('id'=>'name','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('status','Status',array('id'=>'','class'=>'')) }}
						{{ 
							Form::select(
								'status',
								App\Models\Tournament::getStatusArray(),
								$tournament->status,
								array(
									'id'=>'status',
									'class'=>'form-control'
								)
							)
						}}
					</div>
					<div class="form-group">
						{{ Form::label('team_size','Team Size (0 is no Teams)',array('id'=>'','class'=>'')) }}
						{{ Form::text('team_size', $tournament->team_size ,array('id'=>'name','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('event_id','Link to Event',array('id'=>'','class'=>'')) }}
						{{ 
							Form::select(
								'event_id',
								Helpers::getEventNames('DESC', 0, true),
								$tournament->event_id,
								array(
									'id'=>'event_id',
									'class'=>'form-control'
								)
							)
						}}
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-success btn-block">Submit</button> 
					</div>
				{{ Form::close() }}
				<hr>
				{{ Form::open(array('url'=>'/admin/tournaments/' . $tournament->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
					{{ Form::hidden('_method', 'DELETE') }}
					<button type="submit" class="btn btn-danger btn-block">Delete</button>
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>

@endsection