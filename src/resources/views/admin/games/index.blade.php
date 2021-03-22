@extends ('layouts.admin-default')

@section ('page_title', 'Games')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Games</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">
				Games
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-8">

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-th-list fa-fw"></i> Games
			</div>
			<div class="card-body">
				<table class="table table-hover table-responsive">
					<thead>
						<tr>
							<th></th>
							<th>Name</th>
							<th>Description</th>
							<th>Version</th>
							<th>Public</th>
							<th>Tournaments</th>
							<th>Header</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@foreach ($games as $game)
							@php
								$context = 'default';
								if (!$game->public) {
									$context = 'danger';
								}
							@endphp
							<tr class="{{ $context }}">
								<td class=col->
									<picture>
										<source srcset="{{  $game->image_thumbnail_path }}.webp" type="image/webp">
										<source srcset="{{  $game->image_thumbnail_path }}" type="image/jpeg">
										<img src="{{ $game->image_thumbnail_path }}" class="img img-fluid rounded" width="40%">
									</picture>
								</td>
								<td>
									{{ $game->name }}
								</td>
								<td>
									{{ $game->description }}
								</td>
								<td>
									{{ $game->version }}
								</td>
								<td>
									@if ($game->public)
										Yes
									@else
										No
									@endif
								</td>
								<td>
									TBC
								</td>
								<td>
									<picture>
										<source srcset="{{  $game->image_header_path }}.webp" type="image/webp">
										<source srcset="{{  $game->image_header_path }}" type="image/jpeg">
										<img src="{{ $game->image_header_path }}" class="img img-fluid" width="40%">
									</picture>
								</td>
								<td width="15%">
									<a href="/admin/games/{{ $game->slug }}">
										<button class="btn btn-primary btn-sm btn-block">Edit</button>
									</a>
								</td>
								<td width="15%">
									{{ Form::open(array('url'=>'/admin/games/' . $game->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
										{{ Form::hidden('_method', 'DELETE') }}
										<button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
									{{ Form::close() }}
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				{{ $games->links() }}
			</div>
		</div>
	</div>

	<div class="col-lg-4">
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-plus fa-fw"></i> Add Game
			</div>
			<div class="card-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/games/', 'files' => true )) }}
						<div class="form-group">
							{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
							{{ Form::text('name',NULL,array('id'=>'name','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('description','Description',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('description', NULL,array('id'=>'description','class'=>'form-control', 'rows'=>'2')) }}
						</div>
						<div class="form-group">
							{{ Form::label('version','Version',array('id'=>'','class'=>'')) }}
							{{ Form::text('version',NULL, array('id'=>'version','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('image_thumbnail','Thumbnail Image - 500x500',array('id'=>'','class'=>'')) }}
							{{ Form::file('image_thumbnail',array('id'=>'image_thumbnail','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('image_header','Header Image - 1600x300',array('id'=>'','class'=>'')) }}
							{{ Form::file('image_header',array('id'=>'image_header','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('gamecommandhandler','Game Commandhandler',array('id'=>'','class'=>'')) }}
							{{ Form::select('gamecommandhandler', Helpers::getGameCommandHandlerSelectArray(), null, array('id'=>'gamecommandhandler','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('gamematchapihandler','Game Match Api handler',array('id'=>'','class'=>'')) }}
							{{ Form::select('gamematchapihandler', Helpers::getGameMatchApiHandlerSelectArray(), null, array('id'=>'gamematchapihandler','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('connect_game_url','Connect Game URL',array('id'=>'','class'=>'')) }}
							{{ Form::text('connect_game_url', NULL, array('id'=>'connect_game_url','class'=>'form-control')) }}
							<small>Hint: use variables like Game Commands for Matches</small>
						</div>
						<div class="form-group">
							{{ Form::label('connect_game_command','Connect Game Command',array('id'=>'','class'=>'')) }}
							{{ Form::text('connect_game_command', NULL, array('id'=>'connect_game_command','class'=>'form-control')) }}
							<small>Hint: use variables like Game Commands for Matches</small>
						</div>
						<div class="form-group">
							{{ Form::label('connect_stream_url','Connect Stream URL',array('id'=>'','class'=>'')) }}
							{{ Form::text('connect_stream_url', NULL, array('id'=>'connect_stream_url','class'=>'form-control')) }}
							<small>Hint: use variables like Game Commands for Matches</small>
						</div>
						<div class="form-group">
							<label class="form-check-label">
								{{ Form::checkbox('matchmaking_enabled', null, null, array('id'=>'matchmaking_enabled')) }} Enabled for Matchmaking
							</label>
						</div>
						<div class="form-group">
							<label class="form-check-label">
								{{ Form::checkbox('matchmaking_autostart', null, null, array('id'=>'matchmaking_autostart')) }} Enable Match Autostart for Matchmaking
							</label>
						</div>
						<div>
							<small>Make sure the gamecommandhandler and a matchstartgameservercommand is selected! </small>
						</div>
						<div class="form-group">
							<label class="form-check-label">
								{{ Form::checkbox('matchmaking_autoapi', null, null, array('id'=>'matchmaking_autoapi')) }} Enable Match Auto Api for Matchmaking
							</label>
							<div>
								<small>Make sure the selected gamematchapihandler supports the Autoapi feature! (Get5,)</small>
							</div>
						</div>
						<div class="form-group">
							{{ Form::label('min_team_count','Min Team Count',array('id'=>'','class'=>'')) }}
							{{ Form::number('min_team_count', 2, array('id'=>'min_team_count','class'=>'form-control')) }}
							<small>This is used for the Matchmaking feature (0 for no limit)</small>
						</div>
						<div class="form-group">
							{{ Form::label('max_team_count','Max Team Count',array('id'=>'','class'=>'')) }}
							{{ Form::number('max_team_count', 2, array('id'=>'max_team_count','class'=>'form-control')) }}
							<small>This is used for the Matchmaking feature (0 for no limit)</small>
						</div>
						<button type="submit" class="btn btn-success btn-block">Submit</button>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
</div>

@endsection