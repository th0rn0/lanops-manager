@extends ('layouts.admin-default')

@section ('page_title', 'Orders')

@section ('content')
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">Orders</h3>
		<ol class="breadcrumb">
			<li class="active">
				Orders
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-reorder fa-fw"></i> Orders
			</div>
			<div class="panel-body">
				<table width="100%" class="table table-striped table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th>User</th>
							<th>Name</th>
							<th>Status</th>
							<th>Payment Type</th>
							<th>Ordered At</th>
							<th></th>
						</tr>
					</thead>
					<tbody class="table-row odd gradeX">
						@foreach ($orders as $order)
							@php
								$statusColor = '';
								if ($order->status == 'CANCELLED') {
									$statusColor = 'warning';
								}elseif($order->status == 'EVENT') {
									$statusColor = 'warning';
								}elseif($order->status == 'ERROR') {
									$statusColor = 'danger';
								}elseif($order->status == 'COMPLETE') {
									$statusColor = 'success';
								}
							@endphp
							<tr class="{{ $statusColor }}">
								<td>{{ $order->id }}</td>
								<td>{{ $order->purchase->user->username }}</td>
								<td>{{ $order->purchase->user->firstname }} {{ $order->purchase->user->surname }}</td>
								<td>{{ $order->status }}</td>
								<td>{{ $order->purchase->type }}</td>
								<td>{{ $order->created_at }}</td>
								<td>
									<a href="/admin/orders/{{ $order->id }}">
										<button class="btn btn-sm btn-block btn-success">Edit</button>
									</a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				{{ $orders->links() }}
			</div>  
		</div>
	</div>
</div>

@endsection