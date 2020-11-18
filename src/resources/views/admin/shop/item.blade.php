@extends ('layouts.admin-default')

@section ('page_title', 'Shop | ' . $item->name)

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Shop - {{ $item->name }}</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="/admin/shop">Shop</a>
			</li>
			<li class="breadcrumb-item">
				<a href="/admin/shop/{{ $item->category->slug }}">{{ $item->category->slug }}</a>
			</li>
			<li class="breadcrumb-item active">
				{{ $item->name }}
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-12 col-sm-8">
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-plus fa-fw"></i> Settings
			</div>
			<div class="card-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/shop/' . $item->category->slug . '/' . $item->slug )) }}
						<div class="form-group">
							{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
							{{ Form::text('name', $item->name, array('id'=>'name','class'=>'form-control')) }}
						</div>
						<div class="row">
							<div class="form-group col-12 col-sm-6">
								{{ Form::label('stock','Stock',array('id'=>'','class'=>'')) }}
								{{ Form::number('stock', $item->stock, array('id'=>'stock','class'=>'form-control')) }}
							</div>
							<div class="form-group col-12 col-sm-6">
								{{ Form::label('category_id','Category',array('id'=>'','class'=>'')) }}
								{{
									Form::select(
										'category_id',
										Helpers::getShopCategoriesSelectArray(),
										$item->category->id,
										array(
											'id'=>'category_id',
											'class'=>'form-control'
										)
									)
								}}
							</div>
						</div>
						<div class="row">
							<div class="form-group col-12 col-sm-6">
								{{ Form::label('price','Price (Real)',array('id'=>'','class'=>'')) }}
								{{ Form::text('price', $item->price, array('id'=>'price','class'=>'form-control')) }}
							</div>
							<div class="form-group col-12 col-sm-6">
								{{ Form::label('price_credit','Price Credit',array('id'=>'','class'=>'')) }}
								{{ Form::text('price_credit', $item->price_credit, array('id'=>'price_credit','class'=>'form-control')) }}
							</div>
						</div>
						<div class="row">
							<div class="form-group col-12 col-sm-6">
								{{ Form::label('status','Status',array('id'=>'','class'=>'')) }}
								{{
									Form::select(
										'status',
										array(
											'draft'=>'Draft',
											'published'=>'Published',
											'hidden' => 'Hidden'
										),
										strtolower($item->status),
										array(
											'id'=>'status',
											'class'=>'form-control'
										)
									)
								}}
							</div>
							<div class="form-group col-12 col-sm-6">
								{{ Form::label('featured','Featured',array('id'=>'','class'=>'')) }}
								{{
									Form::select(
										'featured',
										array(
											false =>'No',
											true =>'Yes'
										),
										$item->featured,
										array(
											'id'=>'featured',
											'class'=>'form-control'
										)
									)
								}}
							</div>
						</div>
						<div class="form-group">
							{{ Form::label('description','Description',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('description', $item->description, array('id'=>'description','class'=>'form-control wysiwyg-editor', 'rows'=>'4')) }}
						</div>
						<button type="submit" class="btn btn-block btn-success">Submit</button>
					{{ Form::close() }}
					<hr>
					{{ Form::open(array('url'=>'/admin/shop/' . $item->category->slug . '/' . $item->slug )) }}
						{{ Form::hidden('_method', 'DELETE') }}
						<button type="submit" class="btn btn-block btn-danger">Delete</button>
					{{ Form::close() }}
				</div>
			</div>
		</div>
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-plus fa-fw"></i> Images
			</div>
			<div class="card-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/shop/' . $item->category->slug . '/' . $item->slug . '/images', 'files' => 'true')) }}
						{{ csrf_field() }}
						<div class="form-group">
							{{ Form::label('images','Upload Images',array('id'=>'','class'=>'')) }}
							{{ Form::file('images[]',array('id'=>'images','class'=>'form-control', 'multiple'=>false)) }}
						</div>
						<button type="submit" class="btn btn-block btn-success">Upload</button>
					{{ Form::close() }}
					<hr>
					<div class="row">
						@foreach ($item->images as $image)
							<div class="col-12 col-md-3">
								<picture>
									<source srcset="{{ $image->path }}.webp" type="image/webp">
									<source srcset="{{ $image->path }}" type="image/jpeg">
									<img class="img img-fluid rounded" src="{{ $image->path }}">
								</picture>

								<br>
								<div class="row">
									{{ Form::open(array('url'=>'/admin/shop/' . $item->category->slug . '/' . $item->slug . '/images/' . $image->id, 'files' => 'true')) }}
										<div class="col-12 col-md-6">
											{{ Form::number('order', $image->order, array('id'=>'order', 'name' => 'order', 'class'=>'form-control')) }}
										</div>
										<div class="col-12 col-md-6">
											{{ Form::label('default','Default?',array('id'=>'','class'=>'')) }}
												{{ Form::checkbox('default', 'true', $image->default, array('id'=>'default', 'name' => 'default')) }}
										</div>
										<div class="col-12">
											<br>
											<button type="submit" class="btn btn-block btn-success">Update</button>
										</div>
									{{ Form::close() }}
								</div>
								<hr>
								{{ Form::open(array('url'=>'/admin/shop/' . $item->category->slug . '/' . $item->slug . '/images/' . $image->id, 'files' => 'true')) }}
				                	<input type="hidden" name="_method" value="DELETE">
									<button type="submit" class="btn btn-block btn-danger">Delete</button>
								{{ Form::close() }}
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-12 col-sm-4">
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-th-list fa-fw"></i> Details
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-12">
						<ul class="list-group">
							<li class="list-group-item">{{ $item->name }}</li>
							<li class="list-group-item">Stock: {{ $item->stock }}</li>
							<li class="list-group-item">No. of Sales: {{ $item->getTotalSales() }}</li>
							<li class="list-group-item">Added By: {{ $item->user->username }}</li>
						</ul>
						<p>Default Image:</p>
						<picture>
							<source srcset="{{ $item->getDefaultImageUrl() }}.webp" type="image/webp">
							<source srcset="{{ $item->getDefaultImageUrl() }}" type="image/jpeg">
							<img class="img img-fluid rounded" src="{{ $item->getDefaultImageUrl() }}">
						</picture>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
