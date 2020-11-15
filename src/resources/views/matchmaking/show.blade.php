@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - ' . __('matchmaking.match') . " " . $match->id)  

@section ('content')

<div class="container">

	<div class="pb-2 mt-4 mb-4 border-bottom">
		<div class="row">
			<div class="col-sm">
				<h1>
				@lang('matchmaking.match') {{ $match->id }}
				</h1>
			</div>

		</div>
	</div>

	
	@if($match->ispublic == 1 || $invite != null || $teamJoin != null || $match->owner_id == Auth::id() || $match->getMatchTeamPlayer(Auth::id()))
		<div class="row">
			<div class="col-sm">
			
				<p>@lang('matchmaking.currentstatus') {{$match->status}}</p>
			</div>
			<div class="col-sm mt-0">
				@if ($match->owner_id == Auth::id())

					@if($match->status == "DRAFT")
						<div class="form-group">
						{{ Form::open(array('url'=>'/matchmaking/'.$match->id.'/open' )) }}
							<button type="submit" class="btn btn-success btn-block">@lang('matchmaking.openmatch')</button>
						{{ Form::close() }}
						</div>
					@endif
					@if($match->status == "OPEN")
						<p class="mb-0">@lang('matchmaking.matchinviteurl')</p>
						<div class="input-group mb-3 mt-0" style="width: 100%">
							<input class="form-control" id="matchinviteurl" type="text" readonly value="{{ config('app.url') }}/matchmaking/invite/?url={{ $match->invite_tag }}">
							<button class="btn btn-primary" type="button" onclick="copyToClipBoard('matchinviteurl')"><i class="far fa-clipboard"></i></button>
						</div>

						<div class="form-group">
						{{ Form::open(array('url'=>'/matchmaking/'.$match->id.'/start' )) }}
							<button type="submit" class="btn btn-success btn-block">@lang('matchmaking.startmatch')</button>
						{{ Form::close() }}
						</div>
					@endif
					@if($match->status == "LIVE")
						<div class="form-group">
						{{ Form::open(array('url'=>'/matchmaking/'.$match->id.'/finalize' )) }}
						@foreach ($match->teams as $team)

							{{ Form::label('teamscore_'. $team->id, __('matchmaking.scoreof').' '.$team->name ,array('id'=>'','class'=>'')) }}
							{{ Form::number('teamscore_'. $team->id, 0, array('id'=>'teamscore_'. $team->id,'class'=>'form-control mb-3')) }}

						@endforeach
						<button type="submit" class="btn btn-success btn-block ">@lang('matchmaking.finalizematch')</button>
						{{ Form::close() }}
						</div>
					@endif
					@if ($match->status != "LIVE" && $match->status != "COMPLETE")
						<a href="#" class="btn btn-warning btn-block mb-3" data-toggle="modal" data-target="#editMatchModal">@lang('matchmaking.editmatch')</a>

						{{ Form::open(array('url'=>'/matchmaking/' . $match->id, 'onsubmit' => 'return ConfirmDelete()')) }}
						{{ Form::hidden('_method', 'DELETE') }}
						<button type="submit" class="btn btn-danger btn-block">@lang('matchmaking.deletematch')</button>
						{{ Form::close() }}
					@endif

					@if($match->status == "COMPLETE")
					@foreach ($match->teams as $team)
						<p>{{$team->name}} @lang('matchmaking.score') {{$team->team_score}}</p>
					@endforeach
					@endif
				@endif
				@if ( $match->getMatchTeamPlayer(Auth::id()) && !$match->getMatchTeamOwner(Auth::id()) && Auth::id() != $match->owner_id )
				{{ Form::open(array('url'=>'/matchmaking/' . $match->id . '/team/'. $match->getMatchTeamPlayer(Auth::id())->team->id . '/teamplayer/'. $match->getMatchTeamPlayer(Auth::id())->id .'/delete', 'onsubmit' => 'return ConfirmDelete()')) }}
				{{ Form::hidden('_method', 'DELETE') }}
				<button type="submit" class="btn btn-danger btn-sm btn-block">@lang('matchmaking.leavematch')</button>
				{{ Form::close() }}
			
				@endif
			</div>
		</div>
	



		@if ( !$match->getMatchTeamPlayer(Auth::id()) && $match->teams()->count() < $match->team_count )

			<div class="card mb-3">
				<div class="card-header">
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
			
			@foreach ($match->teams as $team)
					<div class="row">
						<div class="col-sm">
							<h4>@lang('matchmaking.team') #{{ $team->id }}: {{ $team->name }} </h4> 	
						</div>
						<div class="col-sm mt-3">
							@if($team->match->status != "LIVE" &&  $team->match->status != "COMPLETE" && ($team->match->owner_id == Auth::id() || $team->team_owner_id == Auth::id()))
								<a href="#" class="btn btn-warning btn-sm btn-block float-right" data-toggle="modal" data-target="#editTeamModal_{{ $team->id }}">@lang('matchmaking.editteam')</a>

								@if($team->id != $team->match->oldestTeam->id )
									{{ Form::open(array('url'=>'/matchmaking/' . $match->id . '/team/'. $team->id . '/delete', 'onsubmit' => 'return ConfirmDelete()')) }}
									{{ Form::hidden('_method', 'DELETE') }}
									<button type="submit" class="btn btn-danger btn-sm btn-block float-right">@lang('matchmaking.deleteteam')</button>
									{{ Form::close() }}
								@endif


							@endif
							@if (!$team->match->getMatchTeamPlayer(Auth::id()) && $team->players->count() < $match->team_size )

							{{ Form::open(array('url'=>'/admin/matchmaking/'.$match->id.'/team/'. $team->id .'/teamplayer/add' )) }}
							<button type="submit" class="btn btn-success btn-sm btn-block float-right">@lang('matchmaking.jointeam')</button>
							{{ Form::close() }}

							@endif
						</div>
					</div>
					@if($team->match->status != "LIVE" &&  $team->match->status != "COMPLETE" &&  $team->match->status != "DRAFT")
						<div class="row">
						
							<div class="col-sm">
								<p class="mb-0 mt-2">@lang('matchmaking.inviteurl')</p>
							</div>
							<div class="col-sm">
								<div class="input-group mb-3 mt-0" style="width: 100%">
									<input class="form-control" id="teaminviteurl_{{$team->id}}" type="text" readonly value="{{ config('app.url') }}/matchmaking/invite/?url={{ $team->team_invite_tag }}">
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
											@if ($teamplayer->user->id != $team->team_owner_id)
												@if($team->match->status != "LIVE" &&  $team->match->status != "COMPLETE" && ($team->match->owner_id == Auth::id() || $team->team_owner_id == Auth::id()) )
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

			@endforeach
	
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
					<h4 class="modal-title" id="editTeamModalLabel_{{ $team->id }}">@lang('matchmaking.editteam')</h4>
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
