@extends ('layouts.admin-default')

@section ('page_title', 'Shop')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Shop</h1>
		<ol class="breadcrumb">
			<li class="active">
				Shop
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-10">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-th-list fa-fw"></i> Settings
			</div>
			<div class="panel-body">

			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-th-list fa-fw"></i> Categories
			</div>
			<div class="panel-body">
				@foreach ($categories as $category)
					{{ $category->name }}
				@endforeach
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-th-list fa-fw"></i> Items
			</div>
			<div class="panel-body">
				@foreach ($items as $item)
					{{ $item->name }}
					{{ $item->price_real }}
					{{ $item->price_credit }}
					{{ $item->category->name }}
					{{ $item->quantity }}
				@endforeach
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-sm-2">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-plus fa-fw"></i> Add Category
			</div>
			<div class="panel-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/shop/category' )) }}
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
							{{ Form::text('name',NULL,array('id'=>'name','class'=>'form-control')) }}
						</div> 
						<button type="submit" class="btn btn-block btn-success">Submit</button>
					{{ Form::close() }}
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-plus fa-fw"></i> Add Item
			</div>
			<div class="panel-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/shop/item' )) }}
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
							{{ Form::text('name',NULL,array('id'=>'name','class'=>'form-control')) }}
						</div> 
						<div class="form-group">
							{{ Form::label('quantity','Quantity',array('id'=>'','class'=>'')) }}
							{{ Form::number('quantity',NULL,array('id'=>'quantity','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('category_id','Category',array('id'=>'','class'=>'')) }}
							{{ Form::text('category_id',NULL,array('id'=>'category_id','class'=>'form-control')) }}
						</div> 
						<div class="form-group">
							{{ Form::label('price_real','Price (Real)',array('id'=>'','class'=>'')) }}
							{{ Form::text('price_real',NULL,array('id'=>'price_real','class'=>'form-control')) }}
						</div> 
						<div class="form-group">
							{{ Form::label('price_credit','Price Credit',array('id'=>'','class'=>'')) }}
							{{ Form::text('price_credit',NULL,array('id'=>'price_credit','class'=>'form-control')) }}
						</div> 
						<div class="form-group">
							{{ Form::label('description','Description',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('description', NULL,array('id'=>'description','class'=>'form-control', 'rows'=>'2')) }}
						</div>
						<button type="submit" class="btn btn-block btn-success">Submit</button>
					{{ Form::close() }}
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-info-circle fa-fw"></i> Enable/Disable
			</div>
			<div class="panel-body">
				<p>The Shop can be used for buying tickets, merch or anything you need.</p>
				@if ($isShopEnabled)
					{{ Form::open(array('url'=>'/admin/settings/shop/disable')) }}
						<button type="submit" class="btn btn-block btn-danger">Disable</button>
					{{ Form::close() }}
				@else
					{{ Form::open(array('url'=>'/admin/settings/shop/enable')) }}
						<button type="submit" class="btn btn-block btn-success">Enable</button>
					{{ Form::close() }}
				@endif
			</div>
		</div>
	</div>
</div>

@endsection
