@extends ('layouts.admin-default')

@section ('page_title', 'Gallery')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Gallery</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">
				Gallery
			</li>
		</ol>
	</div>
</div>

<div class="row">
	@if (!$isGalleryEnabled)
		<div class="col-12">
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-info-circle fa-fw"></i> Gallery is Currently Disabled...
				</div>
				<div class="card-body">
					<p>The Gallery can be used for uploading pictures.</p>
						{{ Form::open(array('url'=>'/admin/settings/gallery/enable')) }}
							<button type="submit" class="btn btn-block btn-success">Enable</button>
						{{ Form::close() }}
				</div>
			</div>
		</div>
	@else
	<div class="col-lg-8">

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-th-list fa-fw"></i> Albums
			</div>
			<div class="card-body">
				<div class="dataTable_wrapper">
					<table width="100%" class="table table-striped table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th>Name</th>
								<th>Description</th>
								<th>URL</th>
								<th>Status</th>
								<th># of Images</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($albums as $album)
								<tr>
									<td>{{ $album->name }}</td>
									<td>{{ $album->description }}</td>
									<td>{{ $album->slug }}</td>
									<td>{{ $album->status }}</td>
									<td>{{ $album->images()->count() }}</td>
									<td width="15%">
										<a href="/admin/gallery/{{ $album->slug }}">
											<button type="button" class="btn btn-primary btn-sm btn-block">Edit</button>
										</a>
									</td>
									<td width="15%">
										{{ Form::open(array('url'=>'/admin/gallery/' . $album->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
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
	</div>

	<div class="col-lg-4">
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-plus fa-fw"></i> Add Album
			</div>
			<div class="card-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/gallery/', 'files' => true )) }}
						<div class="mb-3">
							{{ Form::label('name','Album Name',array('id'=>'','class'=>'')) }}
							{{ Form::text('name',NULL,array('id'=>'name','class'=>'form-control')) }}
						</div>
						<div class="mb-3">
							{{ Form::label('description','Description',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('description', NULL,array('id'=>'description','class'=>'form-control', 'rows'=>'2')) }}
						</div>
						<button type="submit" class="btn btn-success btn-block">Submit</button>
					{{ Form::close() }}
				</div>
			</div>
		</div>
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-info-circle fa-fw"></i> Enable/Disable
			</div>
			<div class="card-body">
				<p>The Gallery can be used for uploading pictures.</p>
					{{ Form::open(array('url'=>'/admin/settings/gallery/disable')) }}
						<button type="submit" class="btn btn-block btn-danger">Disable</button>
					{{ Form::close() }}
			</div>
		</div>
	</div>
	@endif
</div>

<script type="text/javascript">
	jQuery( function() {
		jQuery( "#start_date" ).datepicker();
		jQuery( "#end_date" ).datepicker();
	});
</script>

@endsection
