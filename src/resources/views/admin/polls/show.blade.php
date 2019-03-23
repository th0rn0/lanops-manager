@extends ('layouts.admin-default')

@section ('page_title', 'Polls')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Polls</h1>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/polls/">Polls</a>
			</li>
			<li class="active">
				{{ $poll->name }}
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> Options
			</div>
			<div class="panel-body">
				<table width="100%" class="table table-striped table-hover" id="dataTables-example">
					<thead>
						<tr>
							<th>Name</th>
							<th>Votes</th>
							<th>%</th>
							<th>Added By</th>
						</tr>
					</thead>
					<tbody>
						@if (!$poll->options->isEmpty())
							@foreach ($poll->options as $option)
								<tr class="table-row odd gradeX">
									<td>{{ $option->name }}</td>
									<td>{{ $option->getTotalVotes() }}</td>
									<td></td>
									<td><small>{{ $option->user->steamname }}</small></td>
									<td>
										@if ($option->getTotalVotes() <= 0)
											{{ Form::open(array('url'=>'/admin/polls/' . $poll->slug . '/options/' . $option->id, 'onsubmit' => 'return ConfirmDelete()')) }}
												{{ Form::hidden('_method', 'DELETE') }}
												<button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
											{{ Form::close() }}
										@endif
									</td>
								</tr>
							@endforeach
						@endif
					</tbody>
				</table>
			</div>  
		</div>
	</div>
	<div class="col-xs-12 col-sm-4">
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-pencil fa-fw"></i> Edit {{ $poll->name }}
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/polls/' . $poll->slug, 'files' => 'true')) }}
					<div class="form-group">
						{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
						{{ Form::text('name', $poll->name, array('id'=>'name','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('status','Status',array('id'=>'','class'=>'')) }}
						{{ 
							Form::select(
								'status',
								array(
									'draft'=>'Draft',
									'preview'=>'Preview',
									'published'=>'Published'
								),
								strtolower($poll->status),
								array(
									'id'=>'status',
									'class'=>'form-control'
								)
							)
						}}
					</div>
					<button type="submit" class="btn btn-default btn-block">Submit</button> 
				{{ Form::close() }}
				<hr>
				{{ Form::open(array('url'=>'/admin/polls/' . $poll->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
					{{ Form::hidden('_method', 'DELETE') }}
					<button type="submit" class="btn btn-danger btn-block">Delete</button>
				{{ Form::close() }}
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-plus fa-fw"></i> Add Option
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/polls/' . $poll->slug . '/options', 'files' => 'true')) }}
					<div class="form-group">
						{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
						{{ Form::text('name', '', array('id'=>'name','class'=>'form-control')) }}
					</div>
					<button type="submit" class="btn btn-default btn-block">Submit</button> 
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>
 
@endsection