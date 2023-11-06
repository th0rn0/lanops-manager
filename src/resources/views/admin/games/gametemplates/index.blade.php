@extends ('layouts.admin-default')

@section ('page_title', 'Games')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Game Templates</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="/admin/games/">Games</a>
			</li>
			<li class="breadcrumb-item active">
				Game Templates
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-th-list fa-fw"></i> Game Templates
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
							<th>Has Game URL</th>
							<th>Has Game Command</th>
							<th>Has Stream URL</th>
							<th>Matchmaking Enabled</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($gameTemplates as $gameTemplate)
							<tr>
								<td class=col->
								</td>
								<td>
									{{ $gameTemplate->name }}
								</td>
								<td>
									{{ $gameTemplate->description }}
								</td>
								<td>
									{{ $gameTemplate->version }}
								</td>
								<td>
									@if ($gameTemplate->public)
										Yes
									@else
										No
									@endif
								</td>
								<td>
									@if ($gameTemplate->connect_game_url)
									Yes
									@else
										No
									@endif
								</td>
								<td>
									@if ($gameTemplate->connect_game_command)
										Yes
									@else
										No
									@endif
								</td>
								<td>
									@if ($gameTemplate->connect_stream_url)
										Yes
									@else
										No
									@endif
								</td>
								<td>
									@if ($gameTemplate->matchmaking_enabled)
										Yes
									@else
										No
									@endif
								</td>
								<td width="15%">
									{{ Form::open(array('url'=>'/admin/games/gametemplates', 'onsubmit' => 'return ConfirmSubmit()' )) }}
										{{ Form::hidden('_method', 'POST') }}
										{{ Form::hidden('gameTemplateClass', get_class($gameTemplate), array('id'=>'gameTemplateClass','class'=>'form-control')) }}
									<button type="submit" class="btn btn-success btn-block">Deploy</button>
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

@endsection