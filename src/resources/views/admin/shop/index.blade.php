@extends ('layouts.admin-default')

@section ('page_title', 'Shop')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Shop</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">
				Shop
			</li>
		</ol>
	</div>
</div>

<div class="row">
	@if (!$isShopEnabled)
		<div class="col-12">
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-info-circle fa-fw"></i> Shop is Currently Disabled...
				</div>
				<div class="card-body">
					<p>The Shop can be used for buying merch, consumables etc. It is not recommended you do event ticket sales through this system.</p>
						{{ Form::open(array('url'=>'/admin/settings/shop/enable')) }}
							<button type="submit" class="btn btn-block btn-success">Enable</button>
						{{ Form::close() }}
				</div>
			</div>
		</div>
	@else
		<div class="col-12">

		</div>
		<div class="col-12 col-sm-10">
			<div class="row">
				<div class="col-12 col-sm-6">
					<div class="card mb-3">
						<div class="card-header">
							<i class="fa fa-plus fa-fw"></i> Add Item
						</div>
						<div class="card-body">
							<div class="list-group">
								{{ Form::open(array('url'=>'/admin/shop/item' )) }}
										<div class="mb-3">
											{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
											{{ Form::text('name',NULL,array('id'=>'name','class'=>'form-control')) }}
										</div>
									<div class="row">
										<div class="mb-3 col-12 col-sm-6">
											{{ Form::label('stock','Stock',array('id'=>'','class'=>'')) }}
											{{ Form::number('stock',NULL,array('id'=>'stock','class'=>'form-control')) }}
										</div>
										<div class="mb-3 col-12 col-sm-6">
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
										<div class="mb-3 col-12 col-sm-6">
											{{ Form::label('price','Price (Real)',array('id'=>'','class'=>'')) }}
											{{ Form::text('price',NULL,array('id'=>'price','class'=>'form-control')) }}
										</div>
										<div class="mb-3 col-12 col-sm-6">
											{{ Form::label('price_credit','Price Credit',array('id'=>'','class'=>'')) }}
											{{ Form::text('price_credit',NULL,array('id'=>'price_credit','class'=>'form-control')) }}
										</div>
									</div>
									<div class="mb-3">
										{{ Form::label('description','Description',array('id'=>'','class'=>'')) }}
										{{ Form::textarea('description', NULL,array('id'=>'description','class'=>'form-control wysiwyg-editor', 'rows'=>'2')) }}
									</div>
									<button type="submit" class="btn btn-block btn-success">Submit</button>
								{{ Form::close() }}
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-6">
					<div class="card mb-3">
						<div class="card-header">
							<i class="fa fa-th-list fa-fw"></i> Categories
						</div>
						<div class="card-body">
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
					<div class="card mb-3">
						<div class="card-header">
							<i class="fa fa-wrench fa-fw"></i> Settings
						</div>
						<div class="card-body">
							{{ Form::open(array('url'=>'/admin/settings' )) }}
									<div class="mb-3">
										{{ Form::label('shop_welcome_message','Welcome Message',array('id'=>'','class'=>'')) }}
										{{ Form::text('shop_welcome_message', Settings::getShopWelcomeMessage(), array('id'=>'shop_welcome_message','class'=>'form-control')) }}
										<small>Displayed at the top of the index page of the shop.</small>
									</div>
									<div class="mb-3">
										{{ Form::label('shop_open','Shop Status',array('id'=>'','class'=>'')) }}
										{{
											Form::select(
												'shop_status',
												array(
													'OPEN'=>'Open',
													'CLOSED'=>'Closed'
												),
												Settings::getShopStatus(),
												array(
													'id'=>'shop_status',
													'class'=>'form-control'
												)
											)
										}}
									</div>
									<div class="mb-3">
										{{ Form::label('shop_closed_message','Closed Message',array('id'=>'','class'=>'')) }}
										{{ Form::text('shop_closed_message', Settings::getShopClosedMessage(), array('id'=>'shop_closed_message','class'=>'form-control')) }}
										<small>Displayed at the top of the index page when the shop is closed.</small>
									</div>
									<button type="submit" class="btn btn-block btn-success">Submit</button>
							{{ Form::close() }}
						</div>
					</div>
				</div>
			</div>
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-th-list fa-fw"></i> Items
				</div>
				<div class="card-body">
					{{ $items->links() }}
					<div class="row">
						@foreach ($items as $item)
							<div class="col-12 col-sm-4 col-md-3">
								@include ('layouts._partials._shop.item-preview', ['admin' => true])
							</div>
						@endforeach
					</div>
					{{ $items->links() }}
				</div>
			</div>
		</div>

		<div class="col-12 col-sm-2">
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-plus fa-fw"></i> Add Category
				</div>
				<div class="card-body">
					<div class="list-group">
						{{ Form::open(array('url'=>'/admin/shop/category' )) }}
							<div class="mb-3">
								{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
								{{ Form::text('name',NULL,array('id'=>'name','class'=>'form-control')) }}
							</div>
							<button type="submit" class="btn btn-block btn-success">Submit</button>
						{{ Form::close() }}
					</div>
				</div>
			</div>
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-info-circle fa-fw"></i> Enable/Disable
				</div>
				<div class="card-body">
					<p>The Shop can be used for buying merch, consumables etc. It is not recommended you do event ticket sales through this system.</p>
						{{ Form::open(array('url'=>'/admin/settings/shop/disable')) }}
							<button type="submit" class="btn btn-block btn-danger">Disable</button>
						{{ Form::close() }}
				</div>
			</div>
		</div>
	@endif
</div>

@endsection
