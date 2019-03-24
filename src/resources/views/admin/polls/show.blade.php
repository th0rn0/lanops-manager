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
									<td width="30%">{{ $option->name }}</td>
									<td width="5%">{{ $option->getTotalVotes() }}</td>
									<td width="40%">
										<div class="progress-bar" role="progressbar" aria-valuenow="{{ $option->getPercentage() }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $option->getPercentage() }}%;">
											{{ $option->getPercentage() }}%
										</div>
									</td>
									<td width="25%"><small>{{ $option->user->steamname }}</small></td>
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
						{{ Form::label('description','Description',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('description', $poll->description, array('id'=>'','class'=>'form-control', 'rows'=>'3')) }}
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
					<div class="form-group">
						<button type="submit" class="btn btn-default btn-block">Submit</button> 
					</div>
				{{ Form::close() }}
				<div class="form-group">
					{{ Form::open(array('url'=>'/admin/polls/' . $poll->slug . '/end')) }}
						<button type="submit" class="btn btn-danger btn-sm btn-block">End Poll</button>
					{{ Form::close() }}
				</div>
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