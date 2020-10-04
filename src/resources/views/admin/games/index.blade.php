@extends ('layouts.admin-default')

@section ('page_title', 'Games')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">Games</h3>
		<ol class="breadcrumb">
			<li class="active">
				Games
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-th-list fa-fw"></i> Games
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover">
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
										<img src="{{ $game->image_thumbnail_path }}" class="img img-responsive img-rounded" width="40%">
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
										<img src="{{ $game->image_header_path }}" class="img img-responsive" width="40%">
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
	</div>

	<div class="col-lg-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-plus fa-fw"></i> Add Game
			</div>
			<div class="panel-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/games/', 'files' => true )) }}
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
							{{ Form::label('gamecommandhandler','Game Commandhandler',array('id'=>'','class'=>'')) }}
							{{ Form::select('gamecommandhandler', Helpers::getGameCommandHandler(), null, array('id'=>'gamecommandhandler','class'=>'form-control')) }}
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
							{{ Form::label('connect_game_url','Connect Game URL',array('id'=>'','class'=>'')) }}
							{{ Form::text('connect_game_url', NULL, array('id'=>'connect_game_url','class'=>'form-control')) }}
							<small>Hint: use as in Game Commands for Matches</small>
						</div> 
						<div class="form-group">
							
							{{ Form::label('connect_game_command','Connect Game Command',array('id'=>'','class'=>'')) }}
							{{ Form::text('connect_game_command', NULL, array('id'=>'connect_game_command','class'=>'form-control')) }}
							<small>Hint: use as in Game Commands for Matches</small>
						</div> 
						<button type="submit" class="btn btn-success btn-block">Submit</button>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
</div>

@endsection