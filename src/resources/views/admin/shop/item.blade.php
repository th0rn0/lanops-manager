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
	<div class="col-xs-12 col-sm-10 col-md-7">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-th-list fa-fw"></i> Details
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12 col-sm-8 col-md-9">
						<p>{{ $item->name }}</p>
						<p>Stock: {{ $item->stock }}</p>
						<p>Images:</p>
						<img class="img img-responsive img-rounded" src="{{ $item->getDefaultImageUrl() }}">
						<p>Sales Figures here</p>
					</div>
					
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-4 col-md-3">
		@include ('layouts._partials._shop.item-preview', ['admin'=>'true'])
	</div>
	<div class="col-xs-12 col-sm-2">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-plus fa-fw"></i> Settings
			</div>
			<div class="panel-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/shop/' . $item->category->slug . '/' . $item->category->slug)) }}
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
						<button type="submit" class="btn btn-block btn-success">Submit</button>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
