@extends ('layouts.admin-default')

@section ('page_title', 'Gallery')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">Gallery - {{ $album->name }}</h3>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/gallery">Gallery</a>
			</li>
			<li class="active">
				{{ $album->name }}
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-lg-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-image fa-fw"></i> Images
			</div>
			<div class="panel-body">
				<div class="dataTable_wrapper">
					<table width="100%" class="table table-striped table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th>Image</th>
								<th>Added</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($images as $image)
								<tr>
									{{ Form::open(array('url'=>'/admin/gallery/' . $album->slug . '/' . $image->id, 'files' => true )) }}
										<td class=" col-xs-3">
											<img class="img-responsive img-thumbnail" src="{{ $image->getUrl('optimized') }}">
										</td>
										<td>
											{{ $image->created_at }}
										</td>
										<td width="15%">
											@if ($album->album_cover_id == $image->id)
												<button disabled type="submit" class="btn btn-primary btn-sm btn-block">Set Album Cover</button>
											@else
												<button type="submit" class="btn btn-primary btn-sm btn-block">Set Album Cover</button>
											@endif
										</td>
									{{ Form::close() }}
									{{ Form::open(array('url'=>'/admin/gallery/' . $album->slug . '/' . $image->id, 'files' => true, 'onsubmit' => 'return ConfirmDelete()')) }}
										{{ Form::hidden('_method', 'DELETE') }}
										<td width="15%">
											<button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
										</td>
									{{ Form::close() }}
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<!-- /.table-responsive -->
			</div>
		</div>

	</div>
	<div class="col-lg-4">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-upload fa-fw"></i> Add Images
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/gallery/' . $album->slug . '/upload', 'files' => 'true')) }}
					{{ csrf_field() }}
					<div class="form-group">
						{{ Form::label('images','Select Images',array('id'=>'','class'=>'')) }}
						{{ Form::file('images[]',array('id'=>'images','class'=>'form-control', 'multiple'=>true)) }}
					</div>
					<button type="submit" class="btn btn-primary btn-block">Upload</button>
				{{ Form::close() }}
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Settings
			</div>
			<div class="panel-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/gallery/' . $album->slug)) }}
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
							{{ Form::text('name',$album->name,array('id'=>'name','class'=>'form-control')) }}
						</div> 
						<div class="form-group">
							{{ Form::label('description','Description',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('description', $album->description,array('id'=>'description','class'=>'form-control', 'rows'=>'2')) }}
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
										strtolower($album->status),
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
										strtolower($album->event_id),
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
					{{ Form::open(array('url'=>'/admin/gallery/' . $album->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
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