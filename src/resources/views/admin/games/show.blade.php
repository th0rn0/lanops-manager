@extends ('layouts.admin-default')

@section ('page_title', 'Games')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">Games</h3>		
		<ol class="breadcrumb">
			<li>
				<a href="/admin/games/">Games</a>
			</li>
			<li class="active">
				{{ $game->name }}
			</li>
		</ol>
</div>
</div>

<div class="row">
	<div class="col-lg-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-th-list fa-fw"></i> Tournaments - TBC
			</div>
			<div class="panel-body">
				<table width="100%" class="table table-hover" id="dataTables-example">
					
				</table>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-th-list fa-fw"></i> Game Servers
			</div>
			<div class="panel-body">
				<table width="100%" class="table table-hover" id="dataTables-example">
				<thead>
							<tr>
								<th>Name</th>
								<th>Address</th>
								<th>Game Port</th>
								<th>Game Password</th>
								<th>RCON Port</th>
								<th>RCON Password</th>
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
								<tr class="{{ $context }}">
									
									<td>
										{{ $gameServer->name }}
									</td>
									<td>
										{{ $gameServer->address }}
									</td>
									<td>
										{{ $gameServer->game_port }}
									</td>
									<td>
										@if (isset($gameServer->game_password))
											********
										@endif
									</td>
									<td>
										{{ $gameServer->rcon_port }}
									</td>
									<td>
										@if (isset($gameServer->rcon_password))
											********
										@endif
									</td>
									<td width="15%">
										{{ Form::open(array('url'=>'/admin/games/' . $game->slug . '/gameservers/' . $gameServer->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
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

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-th-list fa-fw"></i> Game Commands
			</div>
			<div class="panel-body">
				<table width="100%" class="table table-hover" id="dataTables-example">
				<thead>
							<tr>
								<th>Name</th>
								<th>Command</th>
								<th><th>
							</tr>
						</thead>
						<tbody>
							@foreach ($game->gameCommands as $gameCommand)
								@php
									$context = 'default';
									if (!$game->public) {
										$context = 'danger';
									}
								@endphp
								<tr class="{{ $context }}">
									
									<td>
										{{ $gameCommand->name }}
									</td>
									<td>
										{{ $gameCommand->command }}
									</td>
									<td width="15%">
										{{ Form::open(array('url'=>'/admin/games/' . $game->slug . '/gameservercommands/' . $gameCommand->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
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

	<div class="col-lg-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-pencil fa-fw"></i> Edit Game
			</div>
			<div class="panel-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/games/' . $game->slug, 'files' => true )) }}
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
							{{ Form::text('name', $game->name, array('id'=>'name','class'=>'form-control')) }}
						</div> 
						<div class="form-group">
							{{ Form::label('description','Description',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('description', $game->description, array('id'=>'description','class'=>'form-control', 'rows'=>'2')) }}
						</div>
						<div class="row">
							<div class="form-group col-xs-12 col-sm-6">
								{{ Form::label('version','Version',array('id'=>'','class'=>'')) }}
								{{ Form::text('version', $game->version, array('id'=>'version','class'=>'form-control')) }}
							</div> 
							<div class="form-group col-xs-12 col-sm-6">
								{{ Form::label('public','Show Publicly',array('id'=>'','class'=>'')) }}
								{{ Form::select('public', [0 => 'No', 1 => 'Yes'], $game->public, array('id'=>'public','class'=>'form-control')) }}
							</div>
						</div>
						<div class="form-group">
							@if ($game->image_thumbnail_path != '')
								<h5>Preview:</h5>
								<img src="{{ $game->image_thumbnail_path }}" class="img img-responsive">
							@endif
							{{ Form::label('image_thumbnail','Thumbnail Image - 300x400',array('id'=>'','class'=>'')) }}
							{{ Form::file('image_thumbnail',array('id'=>'image_thumbnail','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							@if ($game->image_header_path != '')
								<h5>Preview:</h5>
								<img src="{{ $game->image_header_path }}" class="img img-responsive">
							@endif
							{{ Form::label('image_header','Header Image - 1600x300',array('id'=>'','class'=>'')) }}
							{{ Form::file('image_header',array('id'=>'image_header','class'=>'form-control')) }}
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

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-add fa-fw"></i> Add Game Server
			</div>
			<div class="panel-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/games/' . $game->slug . '/gameservers' )) }}
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
							{{ Form::text('name', NULL, array('id'=>'name','class'=>'form-control')) }}
						</div> 
						<div class="form-group">
							{{ Form::label('address','Address',array('id'=>'','class'=>'')) }}
							{{ Form::text('address', NULL, array('id'=>'name','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('game_port','Game Port',array('id'=>'','class'=>'')) }}
							{{ Form::number('game_port', NULL, array('id'=>'name','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('game_password','Game Password',array('id'=>'','class'=>'')) }}
							{{ Form::text('game_password', NULL, array('id'=>'name','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('rcon_port','RCON Port',array('id'=>'','class'=>'')) }}
							{{ Form::number('rcon_port', NULL, array('id'=>'name','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('rcon_password','RCON Password',array('id'=>'','class'=>'')) }}
							{{ Form::text('rcon_password', NULL, array('id'=>'name','class'=>'form-control')) }}
						</div>
											
						<button type="submit" class="btn btn-success btn-block">Submit</button>
					{{ Form::close() }}					
				</div>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-add fa-fw"></i> Add Game Command
			</div>
			<div class="panel-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/games/' . $game->slug . '/gamecommands' )) }}
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
							{{ Form::text('name', NULL, array('id'=>'name','class'=>'form-control')) }}
						</div> 
						<div class="form-group">
							{{ Form::label('command','Command',array('id'=>'','class'=>'')) }}
							{{ Form::text('command', NULL, array('id'=>'name','class'=>'form-control')) }}
						</div>
											
						<button type="submit" class="btn btn-success btn-block">Submit</button>
					{{ Form::close() }}					
				</div>
			</div>
		</div>
	</div>
</div>

@endsection