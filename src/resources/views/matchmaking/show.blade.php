@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - ' . __('matchmaking.match') . " " . $match->id)

@section ('content')

<div class="container">

	<div class="pb-2 mt-4 mb-4 border-bottom">

		<span>
			<h1 class="d-inline">@lang('matchmaking.match') {{ $match->id }} </h1>
			<span class="float-right">
				@if ($match->status == 'COMPLETE')
					<span class="badge badge-success">@lang('matchmaking.ended')</span>
				@endif
				@if ($match->status == 'OPEN')
					<span class="badge badge-success"><i class="fas fa-wifi"></i></span>
				@endif
				@if ($match->status == 'LIVE')
					<span class="badge badge-success">@lang('matchmaking.live')</span>
				@endif
				@if ($match->status == 'PENDING')
					<span class="badge badge-light">@lang('matchmaking.pending')</span>
				@endif
				@if ($match->status == 'WAITFORPLAYERS')
					<span class="badge badge-light">@lang('matchmaking.waitforplayers')</span>
				@endif
				@if ($match->status != 'COMPLETE' && !$match->getMatchTeamPlayer(Auth::id()))
					<span class="badge badge-danger">@lang('matchmaking.notsignedup')</span>
				@endif
				@if ($match->status != 'COMPLETE' && $match->getMatchTeamPlayer(Auth::id()))
					<span class="badge badge-success">@lang('matchmaking.signedup')</span>
				@endif
				@if ( $match->owner_id == Auth::id())
					<span class="badge badge-info">@lang('matchmaking.matchowner')</span>
				@endif
				@if ( $match->getMatchTeamOwner(Auth::id()))
					<span class="badge badge-info">@lang('matchmaking.teamowner')</span>
				@endif
			</span>
		</span>

	</div>


	@if($match->ispublic == 1 || $invite != null || $teamJoin != null || $match->owner_id == Auth::id() || $match->getMatchTeamPlayer(Auth::id()))
		@if ($match->owner_id == Auth::id())
				@if($match->status == "OPEN")
					{{-- Invite URL --}}
					<p class="mb-0">@lang('matchmaking.matchinviteurl')</p>
					<div class="input-group mb-3 mt-0" style="width: 100%">
						<input class="form-control" id="matchinviteurl" type="text" readonly value="{{ config('app.url') }}/matchmaking/invite/?url={{ $match->invite_tag }}">
						<div class="input-group-append">
							<button class="btn btn-primary" type="button" onclick="copyToClipBoard('matchinviteurl')"><i class="far fa-clipboard"></i></button>
						</div>
					</div>
				@endif
				{{-- Open, Start, Finalize --}}
				@if($match->status == "DRAFT")
					<div class="form-group">
					{{ Form::open(array('url'=>'/matchmaking/'.$match->id.'/open' )) }}
						<button type="submit" class="btn btn-success btn-block"><i class="fas fa-wifi"></i> @lang('matchmaking.openmatch')</button>
					{{ Form::close() }}
					</div>
				@endif
				@if($match->status == "OPEN")
					<div class="form-group">
					{{ Form::open(array('url'=>'/matchmaking/'.$match->id.'/start' )) }}
						<button type="submit" class="btn btn-success btn-block"><i class="fas fa-play"></i> @lang('matchmaking.startmatch')</button>
					{{ Form::close() }}
					</div>
				@endif
				@if($match->status == "LIVE")
					{{ Form::open(array('url'=>'/matchmaking/'.$match->id.'/finalize' )) }}
						<div class="row">
							@foreach ($match->teams as $team)
								<div class="col">
									<div class="form-group">
										{{ Form::label('teamscore_'. $team->id, 'Score of '.$team->name ,array('id'=>'','class'=>'')) }}
										@if ($match->game->matchmaking_autoapi)
											{{ Form::number('teamscore_'. $team->id, $team->team_score, array('id'=>'teamscore_'. $team->id,'class'=>'form-control mb-3', 'disabled' => 'disabled')) }}
										@else
											{{ Form::number('teamscore_'. $team->id, $team->team_score, array('id'=>'teamscore_'. $team->id,'class'=>'form-control mb-3')) }}
										@endif
									</div>
								</div>
							@endforeach
						</div>
						@if (!$match->game->matchmaking_autoapi)
							<button type="submit" class="btn btn-success btn-block ">@lang('matchmaking.finalizematch')</button>
						@endif
					{{ Form::close() }}
				@endif
				{{-- Edit, Delete --}}
				@if ($match->status != "LIVE" && $match->status != "COMPLETE" && $match->status != "PENDING" && $match->status != "WAITFORPLAYERS")
					<div class="row">
						<div class="col">
							<a href="#" class="btn btn-warning btn-block mb-3 text-nowrap" data-toggle="modal" data-target="#editMatchModal"><i class="fas fa-edit"></i> @lang('matchmaking.editmatch')</a>
						</div>
						<div class="col">
							{{ Form::open(array('url'=>'/matchmaking/' . $match->id, 'onsubmit' => 'return ConfirmDelete()')) }}
							{{ Form::hidden('_method', 'DELETE') }}
							<button type="submit" class="btn btn-danger btn-block  mb-3 text-nowrap"><i class="fas fa-trash"></i> @lang('matchmaking.deletematch')</button>
							{{ Form::close() }}
						</div>
					</div>
				@endif
			<hr>
		@endif


		@if ( $match->getMatchTeamPlayer(Auth::id()) && !$match->getMatchTeamOwner(Auth::id()) && Auth::id() != $match->owner_id && $match->status != "LIVE" && $match->status != "COMPLETE" && $match->status != "PENDING" && $match->status != "WAITFORPLAYERS")
			{{ Form::open(array('url'=>'/matchmaking/' . $match->id . '/team/'. $match->getMatchTeamPlayer(Auth::id())->team->id . '/teamplayer/'. $match->getMatchTeamPlayer(Auth::id())->id .'/delete', 'onsubmit' => 'return ConfirmDelete()')) }}
				{{ Form::hidden('_method', 'DELETE') }}
				<button type="submit" class="btn btn-danger btn-sm btn-block mb-3">@lang('matchmaking.leavematch')</button>
			{{ Form::close() }}

		@endif

		@if (($match->status == 'LIVE' || $match->status == "WAITFORPLAYERS")  && isset($match->matchMakingServer) && isset($match->matchMakingServer->gameServer) )
		<script>
			function updateStatus(id ,serverStatus){

				if(serverStatus.info == false)
				{
					jQuery(id + "_map").html( "-" );
					jQuery(id + "_players").html( "-" );
				}else
				{
					jQuery(id + "_map").html( serverStatus.info.Map );
					jQuery(id + "_players").html( serverStatus.info.Players );
				}
			}
		</script>
		<div class="row">
			<div class="col-sm">
			@php
			$availableParameters = new \stdClass();
			$availableParameters->game = $match->matchMakingServer->gameServer->game;
			$availableParameters->gameServer = $match->matchMakingServer->gameServer;
			@endphp
					<h6  class="mt-0">{{ $match->matchMakingServer->gameServer->name }}</h6>
					<script>

						document.addEventListener("DOMContentLoaded", function(event) {

							$.get( '/games/{{ $match->matchMakingServer->gameServer->game->slug }}/gameservers/{{ $match->matchMakingServer->gameServer->slug }}/status', function( data ) {
								var serverStatus = JSON.parse(data);
								updateStatus('#serverstatus_{{ $match->matchMakingServer->gameServer->id }}', serverStatus);
							});
							var start = new Date;

							setInterval(function() {
								$.get( '/games/{{ $match->matchMakingServer->gameServer->game->slug }}/gameservers/{{ $match->matchMakingServer->gameServer->slug }}/status', function( data ) {
									var serverStatus = JSON.parse(data);
									updateStatus('#serverstatus_{{ $match->matchMakingServer->gameServer->id }}', serverStatus);
								});
							}, 30000);
						});
					</script>

					<div class="mb-3" id="serverstatus_{{ $match->matchMakingServer->gameServer->id }}">
						<div><i class="fas fa-map-marked-alt"></i><strong > Map: </strong><span id="serverstatus_{{ $match->matchMakingServer->gameServer->id }}_map"></span></div>
						<div><i class="fas fa-users"></i><span class ="ml-2"><strong class ="ml-2" > Players: </strong></span><span id="serverstatus_{{ $match->matchMakingServer->gameServer->id }}_players"></span></div>
					</div>
		</div>
		<div class="col-sm">
					@if($match->matchMakingServer->gameServer->game->connect_stream_url && $match->matchMakingServer->gameServer->stream_port != 0)
					<a class="btn btn-primary btn-block" href="{{ Helpers::resolveServerCommandParameters($match->matchMakingServer->gameServer->game->connect_stream_url, NULL, $availableParameters) }}" role="button">Join Stream</a>
					@endif
					@if($match->matchMakingServer->gameServer->game->connect_game_url && $match->getMatchTeamPlayer(Auth::id()))
					<a class="btn btn-primary btn-block " id="connectGameUrl" href="{{ Helpers::resolveServerCommandParameters($match->matchMakingServer->gameServer->game->connect_game_url, NULL, $availableParameters) }}" role="button">Join Game</a>
					@endif
					@if($match->matchMakingServer->gameServer->game->connect_game_command && $match->getMatchTeamPlayer(Auth::id()))
						<div class="input-group mt-2 mb-3">
							<input class="form-control" id="connectGameCommand{{ $availableParameters->gameServer->id }}" type="text" readonly value="{{ Helpers::resolveServerCommandParameters($match->matchMakingServer->gameServer->game->connect_game_command, NULL, $availableParameters) }}">
							<span class="input-group-btn">
							<button class="btn btn-primary " type="button" onclick="copyToClipBoard('connectGameCommand{{$availableParameters->gameServer->id}}')"><i class="fas fa-external-link-alt"></i></button>
						</div> 
					@endif
		
		</div>

		</div>
	@endif

		{{-- TODO --}}
		@if ( !$match->getMatchTeamPlayer(Auth::id()) && $match->teams()->count() < $match->team_count )

			<div class="card mb-3">
				<div class="card-header @if(Colors::isBodyDarkMode()) border-light @endif">
					<i class="fa fa-plus fa-fw"></i> @lang('matchmaking.addteam')
				</div>
				<div class="card-body">
					<div class="list-group">
						{{ Form::open(array('url'=>'/matchmaking/'.$match->id.'/team/add' )) }}
							<div class="form-group">
								{{ Form::label('teamname',__('matchmaking.teamname'),array('id'=>'','class'=>'')) }}
								{{ Form::text('teamname',NULL,array('id'=>'teamname','class'=>'form-control')) }}
							</div>

							<button type="submit" class="btn btn-success btn-block">@lang('matchmaking.add')</button>
						{{ Form::close() }}
					</div>
				</div>
			</div>

		@endif

		<div class="card-deck">

			@php

				$winnerTeam;
				foreach ($match->teams as $team)
				{
					if(isset($winnerTeam))
					{
						if($winnerTeam->team_score < $team->team_score)
						{
							$winnerTeam = $team;
						}
					}
					else
					{
						$winnerTeam = $team;
					}
				}
			@endphp

			@foreach ($match->teams as $team)
				<div class="col">
					<div class="card mb-3">
						<div class="card-header @if(Colors::isBodyDarkMode()) border-light  @endif">
							<div class="row">
								<div class="col">
									<h4>@lang('matchmaking.team') #{{ $loop->iteration }}: {{ $team->name }}</h4> <small>{{ $team->players->count() }}/ {{ $match->team_size }}</small>
								</div>
								<div class="col">
									@if($team->match->status != "LIVE" && $team->match->status != "COMPLETE" && $team->match->status != "PENDING" && $team->match->status != "WAITFORPLAYERS" && ($team->match->owner_id == Auth::id() || $team->team_owner_id == Auth::id()))
										<div class="row">
											<div class="col">
												<button class="btn btn-warning btn-sm btn-block float-right text-nowrap" data-toggle="modal" data-target="#editTeamModal_{{ $team->id }}"><i class="fas fa-user-edit"></i> @lang('matchmaking.editteam')</button>
											</div>

											@if($team->id != $team->match->oldestTeam->id )
												<div class="col">
													{{ Form::open(array('url'=>'/matchmaking/' . $match->id . '/team/'. $team->id . '/delete', 'onsubmit' => 'return ConfirmDelete()')) }}
													{{ Form::hidden('_method', 'DELETE') }}
													<button type="submit" class="btn btn-danger btn-sm btn-block float-right text-nowrap"><i class="fas fa-trash"></i> @lang('matchmaking.deleteteam')</button>
													{{ Form::close() }}
												</div>
											@endif
										</div>
									@endif
									@if (!$team->match->getMatchTeamPlayer(Auth::id()) && $team->players->count() < $match->team_size )

										{{ Form::open(array('url'=>'/matchmaking/'.$match->id.'/team/'. $team->id .'/teamplayer/add' )) }}
										<button type="submit" class="btn btn-success btn-sm btn-block float-right"><i class="fas fa-user-plus"></i> @lang('matchmaking.jointeam')</button>
										{{ Form::close() }}

									@endif
									@if ($team->match->getMatchTeamPlayer(Auth::id()) && !$match->getMatchTeamOwner(Auth::id()) && $team->players->count() < $match->team_size && $team->match->getMatchTeamPlayer(Auth::id())->team->id != $team->id)
									{{ Form::open(array('url'=>'/matchmaking/'.$match->id.'/team/'. $team->id .'/teamplayer/'. $team->match->getMatchTeamPlayer(Auth::id())->id .'/change' )) }}
										<button type="submit" class="btn btn-success btn-sm btn-block float-right"><i class="fas fa-user-plus"></i> @lang('matchmaking.switchteam')</button>
									{{ Form::close() }}
									@endif
									@if($match->status == "COMPLETE" || ($match->status == "LIVE" && isset($match->game) && isset ($match->game->gamematchapihandler) && $match->game->matchmaking_autoapi))
										<div class="text-center float-right">
											<h4 style="width:50px;" class="border p-2 @if($winnerTeam->id == $team->id) border-success bg-success-light text-success @else border-danger bg-danger-light text-danger @endif">{{ $team->team_score }}</h4>
										</div>
									@endif
								</div>
							</div>
						</div>
						<div class="card-body">
							@if($team->match->status != "LIVE" &&  $team->match->status != "COMPLETE" &&  $team->match->status != "PENDING" && $team->match->status != "WAITFORPLAYERS" &&  $team->match->status != "DRAFT" && ($team->match->owner_id == Auth::id()))
								<div>
									<p class="mb-0 mt-2">@lang('matchmaking.inviteurl')</p>
									<div class="input-group mb-3 mt-0" style="width: 100%">
										<input class="form-control" id="teaminviteurl_{{$team->id}}" type="text" readonly value="{{ config('app.url') }}/matchmaking/invite/?url={{ $team->team_invite_tag }}">
										<div class="input-group-append">
											<button class="btn btn-primary" type="button" onclick="copyToClipBoard('teaminviteurl_{{$team->id}}')"><i class="far fa-clipboard"></i></button>
										</div>
									</div>
								</div>
							@endif
							<div class="dataTable_wrapper">
								<table width="100%" class="table table-striped table-hover" id="dataTables-example">
									<thead>
										<tr>
											<th></th>
											<th>@lang('matchmaking.user')</th>
											<th>@lang('matchmaking.name')</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										@foreach ($team->players as $teamplayer)
											<tr>
												<td>
													<img class="img-fluid rounded" style="max-width: 20%;" src="{{ $teamplayer->user->avatar }}">
												</td>
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
													@if ($teamplayer->user->id != $team->team_owner_id)
														@if($team->match->status != "LIVE" &&  $team->match->status != "COMPLETE"&&  $team->match->status != "PENDING" && ($team->match->owner_id == Auth::id() || $team->team_owner_id == Auth::id()) )
															{{ Form::open(array('url'=>'/matchmaking/' . $match->id . '/team/'. $team->id . '/teamplayer/'. $teamplayer->id .'/delete', 'onsubmit' => 'return ConfirmDelete()')) }}
																{{ Form::hidden('_method', 'DELETE') }}
																<button type="submit" class="btn btn-danger btn-sm btn-block">@lang('matchmaking.removefrommatch')</button>
															{{ Form::close() }}
														@endif
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
					</div>
				</div>
			@endforeach
		</div>
	@else
		<script>
			window.addEventListener("load", function(){
			$("#showMatchErrorModal").modal()
		});
		</script>

	@endif


</div>


<!-- Modals -->
@foreach ($match->teams as $team)

	<div class="modal fade" id="editTeamModal_{{ $team->id }}" tabindex="-1" role="dialog" aria-labelledby="editTeamModalLabel_{{ $team->id }}" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="editTeamModalLabel_{{ $team->id }}"><i class="fas fa-user-edit"></i> @lang('matchmaking.editteam')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
					{{ Form::open(array('url'=>'/matchmaking/'.$match->id.'/team/'.$team->id.'/update' )) }}
					<div class="form-group">
						{{ Form::label('teamname',__('matchmaking.teamname'),array('id'=>'','class'=>'')) }}
						{{ Form::text('teamname',$team->name,array('id'=>'teamname','class'=>'form-control')) }}
					</div>
					<button type="submit" class="btn btn-success btn-block">@lang('matchmaking.submit')</button>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endforeach

<div class="modal fade" id="editMatchModal" tabindex="-1" role="dialog" aria-labelledby="editMatchModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="editMatchModalLabel">@lang('matchmaking.editmatch')</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				{{ Form::open(array('url'=>'/matchmaking/'.$match->id.'/update' )) }}
							<div class="form-group">
								{{ Form::label('game_id',__('matchmaking.games'),array('id'=>'','class'=>'')) }}
								{{
									Form::select(
										'game_id',
										Helpers::getMatchmakingGameSelectArray(),
										$match->game_id,
										array(
											'id'    => 'game_id',
											'class' => 'form-control'
										)
									)
								}}
							</div>
							<div class="form-group">
								{{ Form::label('team_size',__('matchmaking.teamsize'),array('id'=>'','class'=>'')) }}
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
								{{ Form::label('team_count',__('matchmaking.teamcounts'),array('id'=>'','class'=>'')) }}
								{{
									Form::number('team_count',
										$match->team_count,
										array(
											'id'    => 'team_size',
											'class' => 'form-control'
										))
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
</div>



@if($teamJoin != null)
	<div class="modal fade" id="jointeamModal" tabindex="-1" role="dialog" aria-labelledby="jointeamModallabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="jointeamModallabel">@lang('matchmaking.jointeam')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
					@if(!$match->getMatchTeamPlayer(Auth::Id()))
						@lang('matchmaking.doyouwanttojointeam') <strong>{{ $teamJoin->name}} </strong>


						{{ Form::open(array('url'=>'/matchmaking/'.$match->id.'/team/'. $teamJoin->id .'/teamplayer/add' )) }}
						<button type="submit" class="btn btn-success btn-block ">@lang('matchmaking.jointeam')</button>
						{{ Form::close() }}
					@else
						@lang('matchmaking.cannotjoinyoualreadyareinateam')
					@endif

				</div>
			</div>
		</div>
	</div>




	<script>
		window.addEventListener("load", function(){
		$("#jointeamModal").modal()
	});
	</script>
@endif


<div class="modal fade" id="showMatchErrorModal" tabindex="-1" role="dialog" aria-labelledby="showMatchErrorModallabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="showMatchErrorModallabel">@lang('matchmaking.error')</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
					@lang('matchmaking.nopermissions')

					<a href="/matchmaking/">@lang('matchmaking.matchmakinghome')</a>

			</div>
		</div>
	</div>
</div>



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
