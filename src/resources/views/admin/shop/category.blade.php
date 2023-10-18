@extends ('layouts.admin-default')

@section ('page_title', 'Shop | ' . $category->name)

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Shop - {{ $category->name }}</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="/admin/shop">Shop</a>
			</li>
			<li class="breadcrumb-item active">
				{{ $category->name }}
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-12 col-sm-8 col-md-9">
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-th-list fa-fw"></i> Items
			</div>
			<div class="card-body">
				<div class="dataTable_wrapper">
					<table width="100%" class="table table-striped table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th>Name</th>
								<th>Stock</th>
								<th>No. of Sales</th>
								<th>Price</th>
								<th>Status</th>
								<th>Featured</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
								<tr class="table-row" class="odd gradeX">
									<td>{{ $item->name }}</td>
									<td>{{ $item->stock }}</td>
									<td>{{ $item->getTotalSales() }}</td>
									<td>
										@if ($item->price != null)
											{{ Settings::getCurrencySymbol() }}{{ $item->price }}
											@if ($item->price_credit != null)
												/
											@endif
										@endif
										@if ($item->price_credit != null)
											{{ $item->price_credit }} Credits
										@endif
									</td>
									<td>{{ ucfirst(strtolower($item->status)) }}</td>
									<td>
										@if ($item->featured)
											Yes
										@else
											No
										@endif
									</td>
									<td>
										<a href="/admin/shop/{{ $item->category->slug }}/{{ $item->slug }}">
											<button class="btn btn-sm btn-block btn-success">Edit</button>
										</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					{{ $items->links() }}
				</div>
			</div>
		</div>
	</div>

	<div class="col-12 col-sm-4 col-md-3">
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-plus fa-fw"></i> Settings
			</div>
			<div class="card-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/shop/' . $category->slug )) }}
						<div class="mb-3">
							{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
							{{ Form::text('name', $category->name, array('id'=>'name','class'=>'form-control')) }}
						</div>
						<div class="mb-3">
							{{ Form::label('order','Order',array('id'=>'','class'=>'')) }}
							{{ Form::number('order', $category->order, array('id'=>'order','class'=>'form-control')) }}
						</div>
						<div class="mb-3">
							{{ Form::label('status','Status',array('id'=>'','class'=>'')) }}
							{{
								Form::select(
									'status',
									array(
										'draft'=>'Draft',
										'published'=>'Published',
										'hidden' => 'Hidden'
									),
									strtolower($category->status),
									array(
										'id'=>'status',
										'class'=>'form-control'
									)
								)
							}}
						</div>
						<button type="submit" class="btn btn-block btn-success">Submit</button>
					{{ Form::close() }}
					<hr>
					{{ Form::open(array('url'=>'/admin/shop/' . $category->slug )) }}
						{{ Form::hidden('_method', 'DELETE') }}
						<button type="submit" class="btn btn-block btn-danger">Delete</button>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
