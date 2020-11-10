@extends ('layouts.admin-default')

@section ('page_title', 'Matchmaking')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Matchmaking</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">
				Matchmaking
			</li>
		</ol>
	</div>
</div>

<div class="row">
	@if (!$isMatchMakingEnabled)
		<div class="col-12">
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-info-circle fa-fw"></i> MatchMaking is Currently Disabled...
				</div>
				<div class="card-body">
					<p>The Matchmaking feature can be used to make matches by admins or users without the need of an event tournament.</p>
						{{ Form::open(array('url'=>'/admin/settings/matchmaking/enable')) }}
							<button type="submit" class="btn btn-block btn-success">Enable</button>
						{{ Form::close() }}
				</div>
			</div>
		</div>
	@else
	<div class="col-lg-8">

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-th-list fa-fw"></i> Matches
			</div>
			<div class="card-body">
				<div class="dataTable_wrapper">

					<table width="100%" class="table table-striped table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th>ID</th>
								<th>Team1</th>
								<th>Team2</th>
								<th>Teamsize</th>
								<th>Status</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($matches as $match)
								<tr>
									<td>{{ $match->id }}</td>
									<td>
										@if(isset($match->firstteam->name))
											{{ $match->firstteam->name }}
										@endif
									</td>
									<td>
										@if(isset($match->secondteam->name))
											{{ $match->secondteam->name }}
										@endif
									</td>
									<td>{{ $match->team_size }}v{{ $match->team_size }}</td>
									<td>{{ $match->status }}</td>
									<td width="15%">
										<a href="/admin/matchmaking/{{ $match->id }}">
											<button type="button" class="btn btn-primary btn-sm btn-block">Edit</button>
										</a>
									</td>
									<td width="15%">
										{{ Form::open(array('url'=>'/admin/matchmaking/' . $match->id, 'onsubmit' => 'return ConfirmDelete()')) }}
											{{ Form::hidden('_method', 'DELETE') }}
											<button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
										{{ Form::close() }}
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
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-plus fa-fw"></i> Add Match
			</div>
			<div class="card-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/matchmaking/' )) }}
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
						<div class="form-group">
							{{ Form::label('team1name','Team 1 Name',array('id'=>'','class'=>'')) }}
							{{ Form::text('team1name',NULL,array('id'=>'team1name','class'=>'form-control')) }}
						</div>	
						<div class="form-group">
							{{ Form::label('team1owner','Team 1 Owner',array('id'=>'','class'=>'')) }}
							{{
								Form::select(
									'team1owner',
									$users,
									null,
									array(
										'id'    => 'team1owner',
										'class' => 'form-control'
									)
								)
							}}
						</div>
						<div class="form-group">
							{{ Form::label('team2name','Team 2 Name',array('id'=>'','class'=>'')) }}
							{{ Form::text('team2name',NULL,array('id'=>'team2name','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('team2owner','Team 2 Owner',array('id'=>'','class'=>'')) }}
							{{
								Form::select(
									'team2owner',
									$users,
									null,
									array(
										'id'    => 'team2downer',
										'class' => 'form-control'
									)
								)
							}}
						</div>
						<div class="form-group">
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
						<div class="form-group">
							{{ Form::label('ownerid','Match Owner',array('id'=>'','class'=>'')) }}
							{{
								Form::select(
									'ownerid',
									$users,
									null,
									array(
										'id'    => 'ownerid',
										'class' => 'form-control'
									)
								)
							}}
						</div>
						<div class="form-group">
							<div class="form-check">
									<label class="form-check-label">
										{{ Form::checkbox('ispublic', null, null, array('id'=>'ispublic')) }} is public (show match publicly for signup)
									</label>
							</div>
						</div>

						<button type="submit" class="btn btn-success btn-block">Submit</button>
					{{ Form::close() }}
				</div>
			</div>
		</div>
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-info-circle fa-fw"></i> Enable/Disable
			</div>
			<div class="card-body">
				<p>The Matchmaking feature can be used to make matches by admins or users without the need of an event tournament.</p>
					{{ Form::open(array('url'=>'/admin/settings/matchmaking/disable')) }}
						<button type="submit" class="btn btn-block btn-danger">Disable</button>
					{{ Form::close() }}
			</div>
		</div>
	</div>
	@endif
</div>


@endsection
