@extends ('layouts.admin-default')

@section ('page_title', 'Tournaments')

@section ('content')
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">Tournaments</h3>
		<ol class="breadcrumb">
			<li class="active">
				Tournaments
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> Tournaments
			</div>
			<div class="panel-body">
				<table width="100%" class="table table-striped table-hover">
					<thead>
						<tr>
							<th>Name</th>
							<th>Event</th>
							<th>Teams Size</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($tournaments as $tournament)
							<tr class="table-row odd gradeX" data-href="/admin/tournaments/{{ $tournament->slug }}">
								<td>{{ $tournament->name }}</td>
								<td>
									@if ($tournament->hasEvent())
										{{ $tournament->event->display_name }}
									@else
										None
									@endif
								</td>
								<td>
									@if ($tournament->hasTeams())
										{{ $tournament->team_size }}
									@else
										None
									@endif
								</td>
								<td>{{ $tournament->status }}</td>
								<td width="15%">
									<a href="/admin/tournaments/{{ $tournament->slug }}">
										<button type="button" class="btn btn-primary btn-sm btn-block">Edit</button>
									</a>
								</td>
								<td width="15%">
									{{ Form::open(array('url'=>'/admin/tournaments/' . $tournament->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
										{{ Form::hidden('_method', 'DELETE') }}
										<button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
									{{ Form::close() }}
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				{{ $tournaments->links() }}
			</div>  
		</div>
	</div>
	<div class="col-xs-12 col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-plus fa-fw"></i> Create Tournament
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/tournaments/', 'files' => 'true')) }}
					<div class="form-group">
						{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
						{{ Form::text('name', NULL ,array('id'=>'name','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('team_size','Team Size (0 is no Teams)',array('id'=>'','class'=>'')) }}
						{{ Form::text('team_size', 0 ,array('id'=>'name','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('event_id','Link to Event',array('id'=>'','class'=>'')) }}
						{{ 
							Form::select(
								'event_id',
								Helpers::getEventNames('DESC', 0, true),
								'',
								array(
									'id'=>'event_id',
									'class'=>'form-control'
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

@endsection