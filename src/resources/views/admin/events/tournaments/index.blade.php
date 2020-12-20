@extends ('layouts.admin-default')

@section ('page_title', 'Tournaments - ' . $event->display_name)

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Tournaments</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="/admin/events/">Events</a>
			</li>
			<li class="breadcrumb-item">
				<a href="/admin/events/{{ $event->slug }}">{{ $event->display_name }}</a>
			</li>
			<li class="breadcrumb-item active">
				Tournaments
			</li>
		</ol>
	</div>
</div>

@include ('layouts._partials._admin._event.dashMini')

<div class="row">
	<div class="col-lg-8">

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-list-ol fa-fw"></i> Tournaments
			</div>
			<div class="card-body">
				<div class="card-group" id="accordion">
					@if (config('challonge.api_key') != null)
						<table width="100%" class="table table-striped table-hover" id="dataTables-example">
							<thead>
								<tr>
									<th>Name</th>
									<th>Status</th>
									<th>Game</th>
									<th>Format</th>
									<th>Signups</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($tournaments as $tournament)
									<tr class="table-row odd gradeX" data-href="/admin/events/{{ $event->slug }}/tournaments/{{ $tournament->slug }}">
										<td>{{ $tournament->name }}</td>
										<td>{{ $tournament->status }}</td>
										<td>
											@if ($tournament->game)
												{{ $tournament->game->name }}
											@else
												None
											@endif
										</td>
										<td>{{ ucfirst($tournament->format) .  ' - ' . $tournament->team_size }}</td>
										<td class="center">{{ $tournament->tournamentParticipants->count() }}</td>
										<td width="15%">
											<a href="/admin/events/{{ $event->slug }}/tournaments/{{ $tournament->slug }}">
												<button type="button" class="btn btn-primary btn-sm btn-block">Edit</button>
											</a>
										</td>
										<td width="15%">
											{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
												{{ Form::hidden('_method', 'DELETE') }}
												<button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
											{{ Form::close() }}
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
						{{ $tournaments->links() }}
					@else
						No Challonge Key Found. Tournaments are disabled.
					@endif
				</div>
			</div>
		</div>

	</div>
	<div class="col-lg-4">

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-plus fa-fw"></i> Add Tournament
			</div>
			<div class="card-body">
				@if (config('challonge.api_key') != null)
					{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments', 'files' => 'true')) }}
						<div class="form-group">
							{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
							{{ Form::text('name', NULL ,array('id'=>'name','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('game_id','Game',array('id'=>'','class'=>'')) }}
							{{
								Form::select(
									'game_id',
									Helpers::getGameSelectArray(),
									null,
									array(
										'id'    => 'game_id',
										'class' => 'form-control'
									)
								)
							}}
						</div>
						<div class="row">
							<div class="col-lg-6 col-sm-12 form-group">
								{{ Form::label('format','Format',array('id'=>'','class'=>'')) }}
								{{
									Form::select(
										'format',
										array(
											'single elimination'	=> 'Single Elimination',
											'double elimination'  	=> 'Double Elimination',
											'round robin'         	=> 'Round Robin',
											'list'					=> 'List'
										),
										null,
										array(
											'id'    => 'format',
											'class' => 'form-control'
										)
									)
								}}
							</div>
							<div class="col-lg-6 col-sm-12 form-group">
								{{ Form::label('team_size','Team Size',array('id'=>'','class'=>'')) }}
								{{
									Form::select(
										'team_size',
										array(
											'1v1' => '1v1',
											'2v2' => '2v2',
											'3v3' => '3v3',
											'4v4' => '4v4',
											'5v5' => '5v5',
											'6v6' => '6v6'
										),
										null,
										array(
											'id'    => 'team_size',
											'class' => 'form-control'
										)
									)
								}}
							</div>
						</div>

						<div class="form-group">
							{{ Form::label('description','Description',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('description', NULL,array('id'=>'description','class'=>'form-control', 'rows'=>'2')) }}
						</div>
						<div class="form-group">
							{{ Form::label('rules','Rules',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('rules', NULL,array('id'=>'rules','class'=>'form-control wysiwyg-editor')) }}
						</div>
						<div class="row">
							<div class="col-lg-6 col-sm-12 form-group">
								<div class="form-check">
									<label class="form-check-label">
										{{ Form::checkbox('allow_bronze', null, true, array('id'=>'allow_bronze')) }} Match for 3rd Place?
									</label>
								</div>
							</div>
							<div class="col-lg-6 col-sm-12 form-group">
								<div class="form-check">
									<label class="form-check-label">
										{{ Form::checkbox('allow_player_teams', null, true, array('id'=>'allow_player_teams')) }} Allow Player Teams?
									</label>
								</div>
							</div>
							<div class="col-lg-6 col-sm-12 form-group">
								<div class="form-check">
									<label class="form-check-label">
										{{ Form::checkbox('random_teams', null, false, array('id'=>'random_teams')) }} Random Teams
										<small>All Players can join as PUG. On Tournament start all Teams will be generated. </small>
									</label>
								</div>
							</div>
						</div>
						<div class="" form-group">
							<label class="form-check-label">
								{{ Form::checkbox('match_autostart', null, null, array('id'=>'match_autostart')) }} Enable Match Autostart for this Tournament
							</label>
						</div>
						<div>
							<small>Make sure to select a game where the gamecommandhandler and a matchstartgameservercommand is selected in the <a href="/admin/games">game settings</a>! </small>
						</div>
						<div class="form-group">
							<label class="form-check-label">
								{{ Form::checkbox('match_autoapi', null, null, array('id'=>'match_autoapi')) }} Enable Match Auto Api for this Tournament
							</label>
							<div>
								<small>Make sure to select a game where the in the <a href="/admin/games">settings</a> selected gamematchapihandler supports the Autoapi feature! (Get5,)</small>
							</div>
						</div>

						<button type="submit" class="btn btn-success btn-block">Submit</button>
					{{ Form::close() }}
				@else
					No Challonge Key Found. Tournaments are disabled.
				@endif
			</div>
		</div>

	</div>
</div>

@endsection