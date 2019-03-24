@extends ('layouts.admin-default')

@section ('page_title', 'Polls')

@section ('content')
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Polls</h1>
		<ol class="breadcrumb">
			<li class="active">
				Polls
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> Polls
			</div>
			<div class="panel-body">
				<table width="100%" class="table table-striped table-hover">
					<thead>
						<tr>
							<th>Name</th>
							<th>Status</th>
							<th>Votes</th>
							<th>Options</th>
							<th>Created By</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($polls->reverse() as $poll)
							<tr class="table-row odd gradeX" data-href="/admin/polls/{{ $poll->slug }}">
								<td>{{ $poll->name }}</td>
								<td>
									@if (!$poll->hasEnded())
										{{ $poll->status }}
									@else
										ENDED
									@endif
								</td>
								<td>{{ $poll->getTotalVotes() }}</td>
								<td>
									{{ $poll->options->count() }}
								</td>
								<td><small>{{ $poll->user->steamname }}</small></td>
								<td width="15%">
									<a href="/admin/polls/{{ $poll->slug }}">
										<button type="button" class="btn btn-primary btn-sm btn-block">Edit</button>
									</a>
								</td>
								<td width="15%">
									{{ Form::open(array('url'=>'/admin/polls/' . $poll->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
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
	<div class="col-xs-12 col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-plus fa-fw"></i> Create Poll
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/polls/', 'files' => 'true')) }}
					<div class="form-group">
						{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
						{{ Form::text('name', NULL ,array('id'=>'name','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('description','Description',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('description', '', array('id'=>'','class'=>'form-control', 'rows'=>'3')) }}
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
					{{ Form::label('options','Options',array('id'=>'','class'=>'')) }}
					@include ('layouts._partials._polls.add-options')
					<div class="row">
						<div class="col-lg-6 col-sm-12 form-group">
							<div class="checkbox">
								<label>
									{{ Form::checkbox('allow_options_user', true, array('id'=>'allow_options_user','class'=>'form-control')) }} Allow users to add their own options?
								</label>
							</div>
						</div> 
						<div class="col-lg-6 col-sm-12 form-group">
							<div class="checkbox">
								<label>
									{{ Form::checkbox('allow_options_multi', true, array('id'=>'allow_options_multi','class'=>'form-control')) }} Allow multiple options?
								</label>
							</div>
						</div> 
					</div>
					<button type="submit" class="btn btn-default btn-block">Submit</button> 
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>

@endsection