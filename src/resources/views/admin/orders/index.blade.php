@extends ('layouts.admin-default')

@section ('page_title', 'Orders')

@section ('content')
<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Orders</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">
				Orders
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-reorder fa-fw"></i> Orders
			</div>
			<div class="card-body">
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
								}elseif($order->status == 'PENDING') {
									$statusColor = 'info';
								}elseif($order->status == 'SHIPPED') {
									$statusColor = 'info';
								}elseif($order->status == 'PROCESSING') {
									$statusColor = 'info';
								}
							@endphp
							<tr class="table-{{ $statusColor }} text-{{ $statusColor }}">
								<td>{{ $order->id }}</td>
								<td>@if(isset($order->purchase->user)) {{ $order->purchase->user->username }} @else User deleted @endif</td>
								<td>@if(isset($order->purchase->user)) {{ $order->purchase->user->firstname }} {{ $order->purchase->user->surname }} @else User deleted @endif</td>
								<td><strong>{{ $order->status }}</strong></td>
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