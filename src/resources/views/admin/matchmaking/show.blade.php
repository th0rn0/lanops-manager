@extends ('layouts.admin-default')

@section ('page_title', 'Match '. $match->id)

@section ('content')

<div class="row">
	<div class="col-lg-12">
	<h3 class="pb-2 mt-4 mb-4 border-bottom">Match {{ $match->id }}</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">
				Matchmaking
			</li>
		</ol>
	</div>
</div>

<div class="row">

	<div class="col-lg-8">

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-th-list fa-fw"></i> Teams:
			</div>
			@if (isset($match->firstteam->name) && $match->firstteam->name != "")

				<div class="card-body">
					<div class="row">
						<div class="col-sm">
							<h4>Team: {{ $match->firstteam->name }}</h4> 	
						</div>
						<div class="col-sm mt-3">
							@if($match->status != "LIVE" &&  $match->status != "COMPLETE")
							<a href="#" class="btn btn-warning btn-sm btn-block float-right" data-toggle="modal" data-target="#editFirstTeamModal">Edit Team</a>
							@endif
						</div>
					</div>
					<div class="row">
					
						<div class="col-sm">
							<p class="mb-0 mt-2">Invite Url </p>
						</div>
						<div class="col-sm">
							<div class="input-group mb-3 mt-0" style="width: 100%">
								<input class="form-control" id="matchinviteurl" type="text" readonly value="{{ config('app.url') }}/matchmaking/invite/?url={{ $match->firstteam->team_invite_tag }}">
								<button class="btn btn-primary" type="button" onclick="copyToClipBoard('matchinviteurl')"><i class="far fa-clipboard"></i></button>
							</div>
						</div>
					</div>
					<div class="dataTable_wrapper">
						<table width="100%" class="table table-striped table-hover" id="dataTables-example">
							<thead>
								<tr>
									<th></th>
									<th>User</th>
									<th>Name</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach ($match->firstteam->players as $teamplayer)
									<tr>
										<td>	<img class="img-fluid rounded" src="{{ $teamplayer->user->avatar }}"></td>
										<td>
											{{ $teamplayer->user->username }}
											@if ($teamplayer->user->steamid)
												- <span class="text-muted"><small>Steam: {{ $teamplayer->user->steamname }}</small></span>
											@endif
										</td>	
										<td>
											{{ $teamplayer->user->firstname }} {{ $teamplayer->user->surname }}
											
										</td>
										
										<td width="15%">
											@if ($teamplayer->user->id != $match->firstteam->team_owner_id)
												{{ Form::open(array('url'=>'/admin/matchmaking/' . $match->id . '/teamplayer', 'onsubmit' => 'return ConfirmDelete()')) }}
													{{ Form::hidden('_method', 'DELETE') }}
													{{ Form::hidden('userid', $teamplayer->user->id )}}
													<button type="submit" class="btn btn-danger btn-sm btn-block">Remove from Match</button>
												{{ Form::close() }}
											@else
												Teamowner
											@endif
										</td>
									</tr>
								@endforeach

							</tbody>
						</table>
					</div>
				</div>
			@endif
			
			@if (isset($match->secondteam->name) && $match->secondteam->name != "")
				<div class="card-body">
					<div class="row">
						<div class="col-sm">
							<h4>Team: {{ $match->secondteam->name }}</h4> 		
						</div>
						<div class="col-sm mt-3">
							@if($match->status != "LIVE" &&  $match->status != "COMPLETE")
							<a href="#" class="btn btn-warning btn-sm btn-block float-right" data-toggle="modal" data-target="#editSecondTeamModal">Edit Team</a>
							@endif
						</div>
					</div>
					<div class="row">
					
						<div class="col-sm">
							<p class="mb-0 mt-2">Invite Url </p>
						</div>
						<div class="col-sm">
							<div class="input-group mb-3 mt-0" style="width: 100%">
								<input class="form-control" id="matchinviteurl" type="text" readonly value="{{ config('app.url') }}/matchmaking/invite/?url={{ $match->secondteam->team_invite_tag }}">
								<button class="btn btn-primary" type="button" onclick="copyToClipBoard('matchinviteurl')"><i class="far fa-clipboard"></i></button>
							</div>
						</div>
					</div>
					<div class="dataTable_wrapper">
						<table width="100%" class="table table-striped table-hover" id="dataTables-example">
							<thead>
								<tr>
									<th></th>
									<th>User</th>
									<th>Name</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach ($match->secondteam->players as $teamplayer)
									<tr>
										<td>	<img class="img-fluid rounded" src="{{ $teamplayer->user->avatar }}"></td>
										<td>
											{{ $teamplayer->user->username }}
											@if ($teamplayer->user->steamid)
												- <span class="text-muted"><small>Steam: {{ $teamplayer->user->steamname }}</small></span>
											@endif
										</td>	
										<td>
											{{ $teamplayer->user->firstname }} {{ $teamplayer->user->surname }}
											
										</td>
										
										<td width="15%">
											@if ($teamplayer->user->id != $match->secondteam->team_owner_id)
												{{ Form::open(array('url'=>'/admin/matchmaking/' . $match->id . '/teamplayer', 'onsubmit' => 'return ConfirmDelete()')) }}
													{{ Form::hidden('_method', 'DELETE') }}
													<button type="submit" class="btn btn-danger btn-sm btn-block">Remove from Match</button>
												{{ Form::close() }}
											@else
												Teamowner
											@endif
										</td>
									</tr>
								@endforeach

							</tbody>
						</table>
					</div>
				</div>
			@endif

		</div>
	</div>
	

	<div class="col-lg-4">
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-info-circle fa-fw"></i> Match Control
			</div>
			<div class="card-body">
				<div class="list-group">
					<p>Current Status: {{$match->status}}</p>
					@if($match->status == "DRAFT")
						<div class="form-group">
						{{ Form::open(array('url'=>'/admin/matchmaking/'.$match->id.'/open' )) }}
							<button type="submit" class="btn btn-success btn-block">Open Match</button>
						{{ Form::close() }}
						</div>
					@endif
					@if($match->status == "OPEN")
						<p class="mb-0">Match Invite Url</p>
						<div class="input-group mb-3 mt-0" style="width: 100%">
							<input class="form-control" id="matchinviteurl" type="text" readonly value="{{ config('app.url') }}/matchmaking/invite/?url={{ $match->invite_tag }}">
							<button class="btn btn-primary" type="button" onclick="copyToClipBoard('matchinviteurl')"><i class="far fa-clipboard"></i></button>
						</div>
						
						<div class="form-group">
						{{ Form::open(array('url'=>'/admin/matchmaking/'.$match->id.'/start' )) }}
							<button type="submit" class="btn btn-success btn-block">Start Match</button>
						{{ Form::close() }}
						</div>
					@endif
					@if($match->status == "LIVE")
						<div class="form-group">
						{{ Form::open(array('url'=>'/admin/matchmaking/'.$match->id.'/finalize' )) }}
						{{ Form::label('team1score', 'Score of '.$match->firstteam->name ,array('id'=>'','class'=>'')) }}
						{{ Form::number('team1score', '', array('id'=>'team1score','class'=>'form-control mb-3')) }}

						{{ Form::label('team2score', 'Score of '.$match->secondteam->name ,array('id'=>'','class'=>'')) }}
						{{ Form::number('team2score', '', array('id'=>'team2score','class'=>'form-control mb-3')) }}
						<button type="submit" class="btn btn-success btn-block ">Finalize Match</button>
						{{ Form::close() }}
						</div>
					@endif

					@if($match->status == "COMPLETE")
					<p>{{$match->firstteam->name}} Score: {{$match->firstteam->team_score}}</p>
					<p>{{$match->secondteam->name}} Score: {{$match->secondteam->team_score}}</p>
					@endif


					

				</div>
			</div>
		</div>
		@if($match->status != "LIVE" &&  $match->status != "COMPLETE")
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-pencil fa-fw"></i> Edit Match
				</div>
				<div class="card-body">
					<div class="list-group">
						{{ Form::open(array('url'=>'/admin/matchmaking/'.$match->id.'/update' )) }}
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
										$match->game_id,
										array(
											'id'    => 'game_id',
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
										$match->team_size . "v" . $match->team_size ,
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
										$match->owner_id,
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
											{{ Form::checkbox('ispublic', null, $match->ispublic, array('id'=>'ispublic')) }} is public (show match publicly for signup)
										</label>
								</div>
							</div>

							<button type="submit" class="btn btn-success btn-block">Submit</button>
						{{ Form::close() }}
					</div>
				</div>
			</div>
		


			@if (!isset($match->secondteam) || !isset($match->firstteam))
				<div class="card mb-3">
					<div class="card-header">
						<i class="fa fa-plus fa-fw"></i> Add second Team
					</div>
					<div class="card-body">
						<div class="list-group">
							{{ Form::open(array('url'=>'/admin/matchmaking/'.$match->id.'/team/add' )) }}
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
									{{ Form::label('teamname','Team Name',array('id'=>'','class'=>'')) }}
									{{ Form::text('teamname',NULL,array('id'=>'teamname','class'=>'form-control')) }}
								</div>	
								<div class="form-group">
									{{ Form::label('teamowner','Team Owner',array('id'=>'','class'=>'')) }}
									{{
										Form::select(
											'teamowner',
											$users,
											NULL ,
											array(
												'id'    => 'teamowner',
												'class' => 'form-control'
											)
										)
									}}
								</div>
								

								<button type="submit" class="btn btn-success btn-block">Add</button>
							{{ Form::close() }}
						</div>
					</div>
				</div>
			@endif

			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-plus fa-fw"></i> Add user to Team
				</div>
				<div class="card-body">
					<div class="list-group">
						{{ Form::open(array('url'=>'/admin/matchmaking/'.$match->id.'/teamplayer' )) }}

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
								{{ Form::label('userid','User',array('id'=>'','class'=>'')) }}
								{{
									Form::select(
										'userid',
										$availableUsers,
										NULL ,
										array(
											'id'    => 'userid',
											'class' => 'form-control'
										)
									)
								}}
							</div>
							<div class="form-group">
								{{ Form::label('teamid','Team',array('id'=>'','class'=>'')) }}
								{{
									Form::select(
										'teamid',
										$availableTeams,
										NULL,
										array(
											'id'    => 'teamid',
											'class' => 'form-control'
										)
									)
								}}
							</div>
				
							

							<button type="submit" class="btn btn-success btn-block">Add</button>
						{{ Form::close() }}
					</div>
				</div>
			</div>
		@endif


	</div>
</div>

<!-- Modals -->
@if (isset($match->firstteam->name) && $match->firstteam->name != "")
<div class="modal fade" id="editFirstTeamModal" tabindex="-1" role="dialog" aria-labelledby="editFirstTeamModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="editFirstTeamModalLabel">Edit Team</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				{{ Form::open(array('url'=>'/admin/matchmaking/'.$match->id.'/team/'.$match->firstteam->id.'/update' )) }}
				<div class="form-group">
					{{ Form::label('teamname','Team Name',array('id'=>'','class'=>'')) }}
					{{ Form::text('teamname',$match->firstteam->name,array('id'=>'teamname','class'=>'form-control')) }}
				</div>	
				<div class="form-group">
					{{ Form::label('teamowner','Team Owner',array('id'=>'','class'=>'')) }}
					{{
						Form::select(
							'teamowner',
							$users,
							$match->firstteam->team_owner_id ,
							array(
								'id'    => 'teamowner',
								'class' => 'form-control'
							)
						)
					}}
				</div>
				<button type="submit" class="btn btn-success btn-block">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>
@endif
@if (isset($match->secondteam->name) && $match->secondteam->name != "")
	<div class="modal fade" id="editSecondTeamModal" tabindex="-1" role="dialog" aria-labelledby="editSecondTeamModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="editSecondTeamModalLabel">Edit Team</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
					{{ Form::open(array('url'=>'/admin/matchmaking/'.$match->id.'/team/'.$match->secondteam->id.'/update' )) }}
					<div class="form-group">
						{{ Form::label('teamname','Team Name',array('id'=>'','class'=>'')) }}
						{{ Form::text('teamname',$match->secondteam->name,array('id'=>'teamname','class'=>'form-control')) }}
					</div>	
					<div class="form-group">
						{{ Form::label('teamowner','Team Owner',array('id'=>'','class'=>'')) }}
						{{
							Form::select(
								'teamowner',
								$users,
								$match->secondteam->team_owner_id ,
								array(
									'id'    => 'teamowner',
									'class' => 'form-control'
								)
							)
						}}
					</div>
					<button type="submit" class="btn btn-success btn-block">Submit</button>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endif
<script>
	function copyToClipBoard(inputId) {
		/* Get the text field */
		var copyText = document.getElementById(inputId);

		/* Select the text field */
		copyText.select();
		copyText.setSelectionRange(0, 99999); /*For mobile devices*/

		/* Copy the text inside the text field */
		document.execCommand("copy");
	}
</script>

@endsection
