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
		<div class="row">
			<div class="col-xs-12 col-sm-6">
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
								<div class="row">
									<div class="form-group col-xs-12 col-sm-6">
										{{ Form::label('stock','Stock',array('id'=>'','class'=>'')) }}
										{{ Form::number('stock',NULL,array('id'=>'stock','class'=>'form-control')) }}
									</div>
									<div class="form-group col-xs-12 col-sm-6">
										{{ Form::label('category_id','Category',array('id'=>'','class'=>'')) }}
										{{ 
											Form::select(
												'category_id',
												Helpers::getShopCategoriesSelectArray(),
												'',
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
										{{ Form::text('price',NULL,array('id'=>'price','class'=>'form-control')) }}
									</div> 
									<div class="form-group col-xs-12 col-sm-6">
										{{ Form::label('price_credit','Price Credit',array('id'=>'','class'=>'')) }}
										{{ Form::text('price_credit',NULL,array('id'=>'price_credit','class'=>'form-control')) }}
									</div>
								</div>
								<div class="form-group">
									{{ Form::label('description','Description',array('id'=>'','class'=>'')) }}
									{{ Form::textarea('description', NULL,array('id'=>'description','class'=>'form-control wysiwyg-editor', 'rows'=>'2')) }}
								</div>
								<button type="submit" class="btn btn-block btn-success">Submit</button>
							{{ Form::close() }}
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-th-list fa-fw"></i> Categories
					</div>
					<div class="panel-body">
						<div class="dataTable_wrapper">
							<table width="100%" class="table table-striped table-hover" id="dataTables-example">
								<thead>
									<tr>
										<th>Name</th>
										<th>No. of Items</th>
										<th>Status</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									@foreach ($categories as $category)
										<tr class="table-row" class="odd gradeX">
											<td>{{ $category->name }}</td>
											<td>{{ $category->getItemTotal() }}</td>
											<td>{{ $category->status }}</td>
											<td>
												<a href="/admin/shop/{{ $category->slug }}">
													<button class="btn btn-sm btn-block btn-success">Edit</button>
												</a>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
							{{ $categories->links() }}
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-th-list fa-fw"></i> Items
			</div>
			<div class="panel-body">
				{{ $items->links() }}
				<div class="row">
					@foreach ($items as $item)
						<div class="col-xs-12 col-sm-4 col-md-3">
							@include ('layouts._partials._shop.item-preview', ['admin' => true])
						</div>
					@endforeach
				</div>
				{{ $items->links() }}
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
				<i class="fa fa-info-circle fa-fw"></i> Enable/Disable
			</div>
			<div class="panel-body">
				<p>The Shop can be used for buying merch, consumables etc. It is not recommended you do event ticket sales through this system.</p>
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
