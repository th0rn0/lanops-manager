@extends ('layouts.admin-default')

@section ('page_title', 'Games')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Games</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="/admin/games/">Games</a>
			</li>
			<li class="breadcrumb-item active">
				{{ $game->name }}
			</li>
		</ol>
</div>
</div>

<div class="row">
	<div class="col-lg-8">

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-th-list fa-fw"></i> Tournaments - TBC
			</div>
			<div class="card-body">
				<table width="100%" class="table table-hover" id="dataTables-example">

				</table>
			</div>
		</div>

		<div class="card mb-3"  id="gameservers">
			<div class="card-header">
				<i class="fa fa-th-list fa-fw"></i> Game Servers @if($game->gamecommandhandler != "0") <small>(click on a Server to execute Commands)</small> @endif
			</div>
			<div class="card-body table-responsive">
				@if($game->gamecommandhandler != "0")
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
				@endif
				<table width="100%" class="table table-hover" id="dataTables-example">
					<thead>
						<tr>
							<th>Name</th>
							<th>Slug</th>
							<th>Type</th>
							<th>Address</th>
							<th>Game Port</th>
							<th>Stream Port</th>
							<th>RCON Port</th>
							<th>Status</th>
							<th><th>
						</tr>
						</thead>
						<tbody>
							@foreach ($game->gameServers as $gameServer)
								@php
									$context = 'default';
									if (!$game->public) {
										$context = 'danger';
									}
								@endphp
								<tr class="{{ $context }} clickable" data-toggle="collapse" data-target="#collapse_row{{ $gameServer->id }}">

									<td>
										{{ $gameServer->name }}
									</td>
									<td>
										{{ $gameServer->slug }}
									</td>
									<td>
										{{ $gameServer->type }}
									</td>
									<td>
										{{ $gameServer->address }}
									</td>
									<td>
										{{ $gameServer->game_port }}
									</td>
									<td>
										{{ $gameServer->stream_port }}
									</td>
									<td>
										{{ $gameServer->rcon_port }}
									</td>
									<td>
										@if($game->gamecommandhandler != "0")
											<script>
												jQuery( document ).ready(function(){
													jQuery.get( '/games/{{ $game->slug }}/gameservers/{{ $gameServer->slug }}/status', function( data ) {
														var serverStatus = JSON.parse(data);
														updateStatus('#serverstatus_{{ $gameServer->id }}', serverStatus);
													});
													var start = new Date;

													setInterval(function() {
														jQuery.get( '/games/{{ $game->slug }}/gameservers/{{ $gameServer->slug }}/status', function( data ) {
															var serverStatus = JSON.parse(data);
															updateStatus('#serverstatus_{{ $gameServer->id }}', serverStatus);
														});
													}, 10000);
												});
											</script>
											<div id="serverstatus_{{ $gameServer->id }}">
												<div><strong>Map:</strong><span id="serverstatus_{{ $gameServer->id }}_map"></span></div>
												<div><strong>Players:</strong><span id="serverstatus_{{ $gameServer->id }}_players"></span></div>
											</div>
										@else
											<div>Game Commandhandler required</div>
										@endif
									</td>
									<td width="15%">

										<div>
											<button class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#editGameServerModal{{$gameServer->id}}">Edit</button>
											{{ Form::open(array('url'=>'/admin/games/' . $game->slug . '/gameservers/' . $gameServer->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
												{{ Form::hidden('_method', 'DELETE') }}
												<button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
											{{ Form::close() }}
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="10" style="padding: 0;">
										<div id="collapse_row{{ $gameServer->id }}" class="collapse" style="padding: 8px;">

											<h4>Available Commands</h4>
											@foreach ($game->getGameServerCommands() as $gameServerCommand)

												{{ Form::open(array('url'=>'/admin/games/' . $game->slug . '/gameservercommands/execute/' . $gameServer->slug, 'id'=>'selectCommandModal')) }}
													{{ Form::hidden('command', $gameServerCommand->id) }}
													<div class="row row-seperator">
														<div class="col-12 col-md-3">
															<h4>{{ $gameServerCommand->name }}</h4>
														</div>
														<div class="col-12 col-md-6">
															<div class="row">
																@foreach(App\GameServerCommandParameter::getParameters($gameServerCommand->command) as $gameServerCommandParameter)
																	<div class="form-group col-sm-12  col-md-6">
																		{{ Form::label($gameServerCommandParameter->slug, $gameServerCommandParameter->name, array('id'=>'','class'=>'')) }}
																		{{ Form::select($gameServerCommandParameter->slug, $gameServerCommandParameter->getParameterSelectArray(), null, array('id'=>$gameServerCommandParameter->slug,'class'=>'form-control')) }}
																	</div>
																@endforeach
															</div>
														</div>
														<div class="col-12 col-md-3">
															<button type="submit" class="btn btn-success">Execute</button>
														</div>
		 						 					</div>
											  	{{ Form::close() }}
											@endforeach
										</div>
									</td>
								</tr>

								<div class="modal fade" id="editGameServerModal{{$gameServer->id}}" tabindex="-1" role="dialog" aria-labelledby="editGameServerModalLabel{{$gameServer->id}}" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="editGameServerModalLabel{{$gameServer->id}}">Edit GameServer</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											</div>
											{{ Form::open(array('url'=>'/admin/games/' . $game->slug . '/gameservers' . '/' . $gameServer->slug )) }}
												<div class="modal-body">
													<div class="list-group">
														<div class="form-group">
															{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
															{{ Form::text('name', $gameServer->name, array('id'=>'name','class'=>'form-control')) }}
														</div>
														<div class="form-group">
															{{ Form::label('address','Address',array('id'=>'','class'=>'')) }}
															{{ Form::text('address', $gameServer->address, array('id'=>'address','class'=>'form-control')) }}
														</div>
														<div class="form-group">
															{{ Form::label('type','Type',array('id'=>'','class'=>'')) }}
															{{ Form::select('type', ['Casual' => 'Casual', 'Match' => 'Match'],  $gameServer->type, array('id'=>'type','class'=>'form-control')) }}
														</div>
														<div class="form-group">
															{{ Form::label('game_port','Game Port',array('id'=>'','class'=>'')) }}
															{{ Form::number('game_port', $gameServer->game_port, array('id'=>'game_port','class'=>'form-control')) }}
														</div>
														<div class="form-group">
															{{ Form::label('game_password','Game Password',array('id'=>'','class'=>'')) }}
															{{ Form::text('game_password', $gameServer->game_password, array('id'=>'game_password','class'=>'form-control')) }}
														</div>
														<div class="form-group">
															{{ Form::label('stream_port','Stream Port',array('id'=>'','class'=>'')) }}
															{{ Form::number('stream_port', $gameServer->stream_port, array('id'=>'stream_port','class'=>'form-control')) }}
														</div>
														<div class="form-group">
															{{ Form::label('rcon_port','RCON Port',array('id'=>'','class'=>'')) }}
															{{ Form::number('rcon_port', $gameServer->rcon_port, array('id'=>'rcon_port','class'=>'form-control')) }}
														</div>
														<div class="form-group">
															{{ Form::label('rcon_password','RCON Password',array('id'=>'','class'=>'')) }}
															{{ Form::text('rcon_password', $gameServer->rcon_password, array('id'=>'rcon_password','class'=>'form-control')) }}
														</div>
														<div class="form-group">
															<div class="checkbox">
																	<label>
																		{{ Form::checkbox('ispublic', null, $gameServer->ispublic, array('id'=>'ispublic')) }}Server is Public
																	</label>
															</div>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="submit" class="btn btn-success">Submit</button>
													<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
												</div>
											{{ Form::close() }}
										</div>
									</div>
								</div>
							@endforeach
						</tbody>
				</table>
			</div>
		</div>

		@if($game->gamecommandhandler != "0")
			<div class="card mb-3" id="gameservercommands">
				<div class="card-header">
					<i class="fa fa-th-list fa-fw"></i> Game Commands
				</div>
				<div class="card-body table-responsive">

					<div>
						<h4>Variable Usage</h4>
						Use Variables in commands like this: {>gameServer} <br>
						If the used variable contains an object the Properties can also be accessed: {>gameServer->address} or {>gameServer->rcon_port} <br>
						If you need the parameter as an optional parameter (for example the password for the connect url/command: {>§gameserver->password}
					</div>
					<div>
						<h4>Command Scope</h4>
							Command Scopes are used to define the context for the command
						</br>
						<ul>
							<li>
								GameServer: This Parameters are Visible per Server: Available Variable: {>game},{>gameServer}
							</li>
							<li>
								Match (used in Tournaments): This Parameters are Visible for Matches and can use the variable {>game},{>event},{>tournament},{>match},{>gameServer},{>gamematchapiurl->matchconfig},{>gamematchapiurl->matchfinalize}
							</li>
							<li>
								Match (used in Matchmaking): This Parameters are Visible for Matches and can use the variable {>game},{>match},{>gameServer},{>gamematchapiurl->matchconfig},{>gamematchapiurl->matchfinalize}
							</li>
						</ul>
					</div>


					<table width="100%" class="table table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th>Name</th>
								<th>Slug</th>
								<th>Command</th>
								<th>Scope</th>
								<th><th>
							</tr>
						</thead>
						<tbody>
							@foreach ($game->gameServerCommands as $gameServerCommand)
								@php
									$context = 'default';
									if (!$game->public) {
										$context = 'danger';
									}
								@endphp
								<tr class="{{ $context }}">

									<td>
										{{ $gameServerCommand->name }}
									</td>
									<td>
										{{ $gameServerCommand->slug }}
									</td>
									<td>
										{{ $gameServerCommand->command }}
									</td><td>
										{{ Helpers::getGameServerCommandScopeSelectArray()[$gameServerCommand->scope] }}
									</td>
									<td width="15%">
										<button class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#editGameServerCommandModal{{$gameServerCommand->id}}">Edit</button>
										{{ Form::open(array('url'=>'/admin/games/' . $game->slug . '/gameservercommands/' . $gameServerCommand->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
											{{ Form::hidden('_method', 'DELETE') }}
											<button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
										{{ Form::close() }}
									</td>
								</tr>

								<div class="modal fade" id="editGameServerCommandModal{{$gameServerCommand->id}}" tabindex="-1" role="dialog" aria-labelledby="editGameServerCommandModalLabel{{$gameServerCommand->id}}" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="editGameServerCommandModalLabel{{$gameServerCommand->id}}">Edit GameServer Command</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											</div>
											{{ Form::open(array('url'=>'/admin/games/' . $game->slug . '/gameservercommands' . '/' . $gameServerCommand->slug )) }}
												<div class="modal-body">
													<div class="list-group">
														<div class="row">
															<div class="form-group col-12 col-sm-6">
																{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
																{{ Form::text('name', $gameServerCommand->name, array('id'=>'name','class'=>'form-control')) }}
															</div>
															<div class="form-group col-12 col-sm-6">
																	{{ Form::label('scope','Scope',array('id'=>'','class'=>'')) }}
																	{{ Form::select('scope', Helpers::getGameServerCommandScopeSelectArray(), $gameServerCommand->scope, array('id'=>'scope','class'=>'form-control')) }}
															</div>
															<div class="form-group col-12">
																{{ Form::label('command','Command',array('id'=>'','class'=>'')) }}
																{{ Form::text('command', $gameServerCommand->command, array('id'=>'command','class'=>'form-control')) }}
															</div>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="submit" class="btn btn-success">Submit</button>
													<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
												</div>
											{{ Form::close() }}
										</div>
									</div>
								</div>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>

			<div class="card mb-3"  id="gameservercommandparameters">
				<div class="card-header">
					<i class="fa fa-th-list fa-fw"></i> Game Command Parameters
				</div>
				<div class="card-body table-responsive">
					<table width="100%" class="table table-hover" id="dataTables-example">
					<thead>
								<tr>
									<th>Name</th>
									<th>Slug</th>
									<th>Options</th>
									<th><th>
								</tr>
							</thead>
							<tbody>
								@foreach ($game->gameServerCommandParameters as $gameServerCommandParameter)
									@php
										$context = 'default';
										if (!$game->public) {
											$context = 'danger';
										}
									@endphp
									<tr class="{{ $context }}">

										<td>
											{{ $gameServerCommandParameter->name }}
										</td>
										<td>
											{{ $gameServerCommandParameter->slug }}
										</td>
										<td>
											{{ $gameServerCommandParameter->options }}
										</td>
										<td width="15%">
											<button class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#editGameServerCommandParameterModal{{$gameServerCommandParameter->id}}">Edit</button>
											{{ Form::open(array('url'=>'/admin/games/' . $game->slug . '/gameservercommandparameters/' . $gameServerCommandParameter->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
												{{ Form::hidden('_method', 'DELETE') }}
												<button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
											{{ Form::close() }}
										</td>
									</tr>
									<div class="modal fade" id="editGameServerCommandParameterModal{{$gameServerCommandParameter->id}}" tabindex="-1" role="dialog" aria-labelledby="editGameServerCommandParameterModalLabel{{$gameServerCommandParameter->id}}" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<h4 class="modal-title" id="editGameServerCommandParameterModalLabel{{$gameServerCommandParameter->id}}">Edit GameServer Command Parameter</h4>
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												</div>
												{{ Form::open(array('url'=>'/admin/games/' . $game->slug . '/gameservercommandparameters' . '/' . $gameServerCommandParameter->slug )) }}
													<div class="modal-body">
														<div class="list-group">
															<div class="row">
																<div class="form-group col-12">
																	{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
																	{{ Form::text('name', $gameServerCommandParameter->name, array('id'=>'name','class'=>'form-control')) }}
																</div>
																<div class="form-group col-12">
																	{{ Form::label('options','Options',array('id'=>'','class'=>'')) }}
																	{{ Form::text('options', $gameServerCommandParameter->options, array('id'=>'options','class'=>'form-control')) }}
																</div>
															</div>
														</div>
													</div>
													<div class="modal-footer">
														<button type="submit" class="btn btn-success">Submit</button>
														<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
													</div>
												{{ Form::close() }}
											</div>
										</div>
									</div>
								@endforeach
							</tbody>
					</table>
				</div>
			</div>
		@endif
	</div>

	<div class="col-lg-4">
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-pencil fa-fw"></i> Edit Game
			</div>
			<div class="card-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/games/' . $game->slug, 'files' => true )) }}
						<div class="form-group">
							{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
							{{ Form::text('name', $game->name, array('id'=>'name','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('description','Description',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('description', $game->description, array('id'=>'description','class'=>'form-control', 'rows'=>'2')) }}
						</div>
						<div class="row">
							<div class="form-group col-12 col-sm-6">
								{{ Form::label('version','Version',array('id'=>'','class'=>'')) }}
								{{ Form::text('version', $game->version, array('id'=>'version','class'=>'form-control')) }}
							</div>
							<div class="form-group col-12 col-sm-6">
								{{ Form::label('public','Show Publicly',array('id'=>'','class'=>'')) }}
								{{ Form::select('public', [0 => 'No', 1 => 'Yes'], $game->public, array('id'=>'public','class'=>'form-control')) }}
							</div>
						</div>
						<div class="form-group">
						{{ Form::label('gamecommandhandler','Game Commandhandler',array('id'=>'','class'=>'')) }}
						{{ Form::select('gamecommandhandler', Helpers::getGameCommandHandlerSelectArray(), $game->gamecommandhandler, array('id'=>'gamecommandhandler','class'=>'form-control')) }}
						</div>						
						<div class="form-group">
						{{ Form::label('gamematchapihandler','Game Match Api handler',array('id'=>'','class'=>'')) }}
						{{ Form::select('gamematchapihandler', Helpers::getGameMatchApiHandlerSelectArray(), $game->gamematchapihandler, array('id'=>'gamematchapihandler','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('matchstartgameservercommand','Match start command',array('id'=>'','class'=>'')) }}
							{{ Form::select('matchstartgameservercommand', $allCommands, $game->matchStartgameServerCommand, array('id'=>'matchstartgameservercommand','class'=>'form-control')) }}
						<small>if match autostarting is enabled in either the Tournament or the Matchmaking settings this gameservercommand would be executed on match start</small>
						</div>
						<div class="form-group">
							@if ($game->image_thumbnail_path != '')
								<h5>Preview:</h5>
								<picture>
									<source srcset="{{  $game->image_thumbnail_path }}.webp" type="image/webp">
									<source srcset="{{  $game->image_thumbnail_path }}" type="image/jpeg">
									<img src="{{ $game->image_thumbnail_path }}" class="img img-fluid">
								</picture>
							@endif
							{{ Form::label('image_thumbnail','Thumbnail Image - 300x400',array('id'=>'','class'=>'')) }}
							{{ Form::file('image_thumbnail',array('id'=>'image_thumbnail','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							@if ($game->image_header_path != '')
								<h5>Preview:</h5>
								<picture>
									<source srcset="{{  $game->image_header_path }}.webp" type="image/webp">
									<source srcset="{{  $game->image_header_path }}" type="image/jpeg">
									<img src="{{ $game->image_header_path }}" class="img img-fluid">
								</picture>
							@endif
							{{ Form::label('image_header','Header Image - 1600x300',array('id'=>'','class'=>'')) }}
							{{ Form::file('image_header',array('id'=>'image_header','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('connect_game_url','Connect Game URL',array('id'=>'','class'=>'')) }}
							{{ Form::text('connect_game_url', $game->connect_game_url, array('id'=>'connect_game_url','class'=>'form-control')) }}
							<small>Hint: use variables like in the  Game Commands for the Match Scope</small>
						</div>
						<div class="form-group">
							{{ Form::label('connect_game_command','Connect Game Command',array('id'=>'','class'=>'')) }}
							{{ Form::text('connect_game_command', $game->connect_game_command, array('id'=>'connect_game_command','class'=>'form-control')) }}
							<small>Hint: use variables like in the Game Commands the Match Scope</small>
						</div>
						<div class="form-group">
							{{ Form::label('connect_stream_url','Connect Stream URL',array('id'=>'','class'=>'')) }}
							{{ Form::text('connect_stream_url', $game->connect_stream_url, array('id'=>'connect_stream_url','class'=>'form-control')) }}
							<small>Hint: use variables like in the Game Commands the Match Scope</small>
						</div>
						<div class="form-group">
							<label class="form-check-label">
								{{ Form::checkbox('matchmaking_enabled', null, $game->matchmaking_enabled, array('id'=>'matchmaking_enabled')) }} Enabled for Matchmaking
							</label>
						</div>
						<div class="form-group">
							{{ Form::label('min_team_count','Min Team Count',array('id'=>'','class'=>'')) }}
							{{ Form::number('min_team_count', $game->min_team_count, array('id'=>'min_team_count','class'=>'form-control')) }}
							<small>This is used for the Matchmaking feature (0 for no limit)</small>
						</div>
						<div class="form-group">
							{{ Form::label('max_team_count','Max Team Count',array('id'=>'','class'=>'')) }}
							{{ Form::number('max_team_count', $game->max_team_count, array('id'=>'max_team_count','class'=>'form-control')) }}
							<small>This is used for the Matchmaking feature (0 for no limit)</small>
						</div>
						<button type="submit" class="btn btn-success btn-block">Submit</button>
					{{ Form::close() }}
					<hr>
					{{ Form::open(array('url'=>'/admin/games/' . $game->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
						{{ Form::hidden('_method', 'DELETE') }}
						<button type="submit" class="btn btn-danger btn-block">Delete</button>
					{{ Form::close() }}
				</div>
			</div>
		</div>

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-add fa-fw"></i> Add Game Server
			</div>
			<div class="card-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/games/' . $game->slug . '/gameservers' )) }}
						<div class="form-group">
							{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
							{{ Form::text('name', NULL, array('id'=>'name','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('type','Type',array('id'=>'','class'=>'')) }}
							{{ Form::select('type', ['Casual' => 'Casual', 'Match' => 'Match'], NULL, array('id'=>'type','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('address','Address',array('id'=>'','class'=>'')) }}
							{{ Form::text('address', NULL, array('id'=>'address','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('game_port','Game Port',array('id'=>'','class'=>'')) }}
							{{ Form::number('game_port', NULL, array('id'=>'game_port','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('game_password','Game Password',array('id'=>'','class'=>'')) }}
							{{ Form::text('game_password', NULL, array('id'=>'game_password','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('stream_port','Stream Port',array('id'=>'','class'=>'')) }}
							{{ Form::number('stream_port', NULL, array('id'=>'stream_port','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('rcon_port','RCON Port',array('id'=>'','class'=>'')) }}
							{{ Form::number('rcon_port', NULL, array('id'=>'rcon_port','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('rcon_password','RCON Password',array('id'=>'','class'=>'')) }}
							{{ Form::text('rcon_password', NULL, array('id'=>'rcon_password','class'=>'form-control')) }}
						</div>
						<div class="form-check">
							<label class="form-check-label">
								{{ Form::checkbox('ispublic', null, null, array('id'=>'ispublic')) }}Server is Public
							</label>
						</div>
						<button type="submit" class="btn btn-success btn-block">Submit</button>
					{{ Form::close() }}
				</div>
			</div>
		</div>

		@if($game->gamecommandhandler != "0")
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-add fa-fw"></i> Add Game Command
				</div>
				<div class="card-body">
					<div class="list-group">
						{{ Form::open(array('url'=>'/admin/games/' . $game->slug . '/gameservercommands' )) }}
							<div class="row">
								<div class="form-group col-12 col-sm-6">
									{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
									{{ Form::text('name', NULL, array('id'=>'name','class'=>'form-control')) }}
								</div>
								<div class="form-group col-12 col-sm-6">
										{{ Form::label('scope','Scope',array('id'=>'','class'=>'')) }}
										{{ Form::select('scope', Helpers::getGameServerCommandScopeSelectArray(), null, array('id'=>'scope','class'=>'form-control')) }}
								</div>
								<div class="form-group col-12">
									{{ Form::label('command','Command',array('id'=>'','class'=>'')) }}
									{{ Form::text('command', NULL, array('id'=>'name','class'=>'form-control')) }}
								</div>

								<button type="submit" class="btn btn-success btn-block">Submit</button>
							</div>
						{{ Form::close() }}
					</div>
				</div>
			</div>

			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-add fa-fw"></i> Add Game Command Parameter
				</div>
				<div class="card-body">
					<div class="list-group">
						{{ Form::open(array('url'=>'/admin/games/' . $game->slug . '/gameservercommandparameters' )) }}
							<div class="row">
								<div class="form-group col-12 col-sm-6">
									{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
									{{ Form::text('name', NULL, array('id'=>'name','class'=>'form-control')) }}
								</div>
								<div class="form-group col-12">
									{{ Form::label('options','Parameter options',array('id'=>'','class'=>'')) }}
									{{ Form::text('options', NULL, array('id'=>'options','class'=>'form-control')) }}
								</div>

								<button type="submit" class="btn btn-success btn-block">Submit</button>
							</div>
						{{ Form::close() }}
					</div>
				</div>
			</div>
		@endif
	</div>
</div>
@endsection