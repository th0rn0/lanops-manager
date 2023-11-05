@extends ('layouts.admin-default')

@section ('page_title', 'Gallery')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Gallery - {{ $album->name }}</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="/admin/gallery">Gallery</a>
			</li>
			<li class="breadcrumb-item active">
				{{ $album->name }}
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-8">

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-image fa-fw"></i> Images
			</div>
			<div class="card-body">
				<div class="dataTable_wrapper">
					<table width="100%" class="table table-striped table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th>Image/File</th>
								<th>Name</th>
								<th>Description</th>
								<th>Album Cover</th>
								<th>Added</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($images as $image)
								<tr>
									{{ Form::open(array('url'=>'/admin/gallery/' . $album->slug . '/' . $image->id, 'files' => true )) }}
										<td >
											@if ($image->filetype == 0)
												<picture>
													<source srcset="{{ $image->path }}.webp" type="image/webp">
													<source srcset="{{ $image->path }}" type="image/jpeg">
													<img class="img-responsive img-thumbnail" style="width: 100px" src="{{ $image->path }}">
												</picture>
											@elseif ($image->filetype == 1)
												<i class="fas fa-file-download fa-7x"></i>
											@else
												<i class="far fa-question-circle fa-7x"></i>
											@endif
										</td>
										<td>
											<div class="mb-3">
												{{ Form::text('name', $image->nice_name,array('id'=>'name','class'=>'form-control')) }}
											</div>
										</td>
										<td>
											<div class="mb-3">
												{{ Form::textarea('desc', $image->desc,array('id'=>'desc','class'=>'form-control', 'rows'=>'2')) }}
											</div>
										</td>
										<td>
											<div class="form-check">
												@if ($image->filetype == 0)
													<label class="form-check-label">
														@if ($album->album_cover_id == $image->id)
															{{ Form::checkbox('album_cover', 1, true, array('id'=>'album_cover')) }}
														@else
															{{ Form::checkbox('album_cover', 1, false, array('id'=>'album_cover')) }}
														@endif
													</label>
												@endif

											</div>
										</td>
										<td>
											{{ $image->created_at }}
										</td>
										<td width="15%">
											<button type="submit" class="btn btn-primary btn-sm btn-block">Update</button>
										{{-- </td> --}}
									{{ Form::close() }}
									{{ Form::open(array('url'=>'/admin/gallery/' . $album->slug . '/' . $image->id, 'files' => true, 'onsubmit' => 'return ConfirmDelete()')) }}
										{{ Form::hidden('_method', 'DELETE') }}
										{{-- <td width="15%"> --}}
											<button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
										</td>
									{{ Form::close() }}
								</tr>
							@endforeach
						</tbody>
					</table>
					{{ $images->links() }}
				</div>
				<!-- /.table-responsive -->
			</div>
		</div>

	</div>
	<div class="col-lg-4">

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-upload fa-fw"></i> Add Images
			</div>
			<div class="card-body">
				{{ Form::open(array('url'=>'/admin/gallery/' . $album->slug . '/upload', 'files' => 'true')) }}
					{{ csrf_field() }}
					<div class="mb-3">
						{{ Form::label('images','Select Images',array('id'=>'','class'=>'')) }}
						{{ Form::file('images[]',array('id'=>'images','class'=>'form-control', 'multiple'=>true)) }}
					</div>
					<button type="submit" class="btn btn-primary btn-block">Upload</button>
				{{ Form::close() }}
			</div>
		</div>
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-wrench fa-fw"></i> Settings
			</div>
			<div class="card-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/gallery/' . $album->slug)) }}
						<div class="mb-3">
							{{ Form::label('name','Album Name',array('id'=>'','class'=>'')) }}
							{{ Form::text('name',$album->name,array('id'=>'name','class'=>'form-control')) }}
						</div>
						<div class="mb-3">
							{{ Form::label('description','Description',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('description', $album->description,array('id'=>'description','class'=>'form-control', 'rows'=>'2')) }}
						</div>
						<div class="row">
							<div class="col-lg-6 col-sm-12 mb-3">
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
							 <div class="col-lg-6 col-sm-12 mb-3">
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
	jQuery( function() {
		jQuery( "#start_date" ).datepicker();
		jQuery( "#end_date" ).datepicker();
	});
</script>

@endsection