@extends ('layouts.admin-default')

@section ('page_title', 'Help')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">Help - {{ $helpCategory->name }}</h3>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/help">Help</a>
			</li>
			<li class="active">
				{{ $helpCategory->name }}
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-lg-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-entry fa-fw"></i> Entrys
			</div>
			<div class="panel-body">
				<div class="dataTable_wrapper">
					<table width="100%" class="table table-striped table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th>Name</th>
								<th>Content</th>
								<th>Added</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($entrys as $entry)
								<tr>
									{{ Form::open(array('url'=>'/admin/help/' . $helpCategory->slug . '/' . $entry->id, 'files' => true )) }}
										<td>
											<div class="form-group">
												{{ Form::text('name', $entry->display_name,array('id'=>'name','class'=>'form-control')) }}
											</div>
										</td>
										<td>
											<div class="form-group">
												{{ Form::textarea('content', $entry->content,array('id'=>'content','class'=>'form-control wysiwyg-editor-small', 'rows'=>'2')) }}
											</div>
										</td>
										<td>
											{{ $entry->created_at }}
										</td>
										<td width="15%">
											<button type="submit" class="btn btn-primary btn-sm btn-block">Update</button>
										</td>
									{{ Form::close() }}
									{{ Form::open(array('url'=>'/admin/help/' . $helpCategory->slug . '/' . $entry->id, 'files' => true, 'onsubmit' => 'return ConfirmDelete()')) }}
										{{ Form::hidden('_method', 'DELETE') }}
										<td width="15%">
											<button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
										</td>
									{{ Form::close() }}
								</tr>
							@endforeach
						</tbody>
					</table>
					{{ $entrys->links() }}
				</div>
				<!-- /.table-responsive -->
			</div>
		</div>

	</div>
	<div class="col-lg-4">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-upload fa-fw"></i> Add Entry
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/help/' . $helpCategory->slug . '/add',)) }}
					{{ csrf_field() }}
					<div class="form-group">
						{{ Form::label('name','Entry Name',array('id'=>'','class'=>'')) }}
						{{ Form::text('name', NULL ,array('id'=>'name','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('content','Entry Content',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('content', NULL ,array('id'=>'content','class'=>'form-control wysiwyg-editor', 'rows'=>'2')) }}
					</div>
					<button type="submit" class="btn btn-primary btn-block">Add</button>
				{{ Form::close() }}
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Settings
			</div>
			<div class="panel-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/help/' . $helpCategory->slug)) }}
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
							{{ Form::label('name','Album Name',array('id'=>'','class'=>'')) }}
							{{ Form::text('name',$helpCategory->name,array('id'=>'name','class'=>'form-control')) }}
						</div> 
						<div class="form-group">
							{{ Form::label('description','Content',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('description', $helpCategory->description,array('id'=>'description','class'=>'form-control', 'rows'=>'2')) }}
						</div>
						<div class="row">
							<div class="col-lg-6 col-sm-12 form-group">
								{{ Form::label('status','Status',array('id'=>'','class'=>'')) }}
								{{ 
									Form::select(
										'status',
										array(
											'draft'=>'Draft',
											'published'=>'Published',
										),
										strtolower($helpCategory->status),
										array(
											'id'=>'status',
											'class'=>'form-control'
										)
									)
								}}
							</div>
							 <div class="col-lg-6 col-sm-12 form-group">
								{{ Form::label('event_id','Event',array('id'=>'','class'=>'')) }}
								{{ 
									Form::select(
										'event_id',
										Helpers::getEventNames('DESC', 0),
										strtolower($helpCategory->event_id),
										array(
											'id'=>'event_id',
											'class'=>'form-control'
										)
									)
								}}
							</div>
						</div>
						<button type="submit" class="btn btn-success btn-block">Submit</button>
					{{ Form::close() }}
					<hr>
					{{ Form::open(array('url'=>'/admin/help/' . $helpCategory->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
						{{ Form::hidden('_method', 'DELETE') }}
						<button type="submit" class="btn btn-danger btn-block">Delete</button>
					{{ Form::close() }}
				</div>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript">
	$( function() {
		$( "#start_date" ).datepicker();
		$( "#end_date" ).datepicker();
	});
</script>

@endsection