@extends ('layouts.admin-default')

@section ('page_title', 'Shop | ' . $item->name)

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Shop - {{ $item->name }}</h1>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/shop">Shop</a>
			</li>
			<li>
				<a href="/admin/shop/{{ $item->category->slug }}">{{ $item->category->slug }}</a>
			</li>
			<li class="active">
				{{ $item->name }}
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-plus fa-fw"></i> Settings
			</div>
			<div class="panel-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/shop/' . $item->category->slug . '/' . $item->slug )) }}
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
							{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
							{{ Form::text('name', $item->name, array('id'=>'name','class'=>'form-control')) }}
						</div>
						<div class="row">
							<div class="form-group col-xs-12 col-sm-6">
								{{ Form::label('stock','Stock',array('id'=>'','class'=>'')) }}
								{{ Form::number('stock', $item->stock, array('id'=>'stock','class'=>'form-control')) }}
							</div>
							<div class="form-group col-xs-12 col-sm-6">
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
							<div class="form-group col-xs-12 col-sm-6">
								{{ Form::label('price','Price (Real)',array('id'=>'','class'=>'')) }}
								{{ Form::text('price', $item->price, array('id'=>'price','class'=>'form-control')) }}
							</div> 
							<div class="form-group col-xs-12 col-sm-6">
								{{ Form::label('price_credit','Price Credit',array('id'=>'','class'=>'')) }}
								{{ Form::text('price_credit', $item->price_credit, array('id'=>'price_credit','class'=>'form-control')) }}
							</div> 
						</div>
						<div class="row">
							<div class="form-group col-xs-12 col-sm-6">
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
							<div class="form-group col-xs-12 col-sm-6">
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
				</div>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-th-list fa-fw"></i> Details
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12 col-sm-8 col-md-9">
						<ul class="list-group">
							<li class="list-group-item">{{ $item->name }}</li>
							<li class="list-group-item">Stock: {{ $item->stock }}</li>
							<li class="list-group-item">No. of Sales: {{ $item->getTotalSales() }}</li>
							<li class="list-group-item">Added By: {{ $item->user->steamname }}</li>
						</ul>
						<p>Images:</p>
						<img class="img img-responsive img-rounded" src="{{ $item->getDefaultImageUrl() }}">
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>

@endsection