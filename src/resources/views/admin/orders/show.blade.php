@extends ('layouts.admin-default')

@section ('page_title', 'Order - ' . $order->id)

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">
			Order - {{ $order->id }}
		</h3>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/orders/">Orders</a>
			</li>
			<li class="active">
				{{ $order->id }}
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-4">
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-reorder fa-fw"></i> Items
			</div>
			<div class="panel-body">
				<table width="100%" class="table table-striped table-hover" id="dataTables-example">
					<thead>
						<tr>
							<th>Item</th>
							<th>Quantity</th>
							<th>Price Paid</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($order->items as $item)
							<tr>
								<td>
									{{ $item->item->name }}
								</td>
								<td>
									{{ $item->quantity }}
								</td>
								<td>
									@if ($item->price != null)
										{{ Settings::getCurrencySymbol() }}{{ $item->price }}
										@if ($item->price_credit != null && Settings::isCreditEnabled())
											/
										@endif
									@endif
									@if ($item->price_credit != null && Settings::isCreditEnabled())
										{{ $item->price_credit }} Credits 
									@endif
									Each | 
									@if ($item->price != null)
										{{ Settings::getCurrencySymbol() }}{{ $item->price * $item->quantity }}
										@if ($item->price_credit != null && Settings::isCreditEnabled())
											/
										@endif
									@endif
									@if ($item->price_credit != null && Settings::isCreditEnabled())
										{{ $item->price_credit * $item->quantity }} Credits 
									@endif
									Total
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-credit-card fa-fw"></i> Details
			</div>
			<div class="panel-body">
				<ul class="list-group">
					@php
						$statusColor = 'info';
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
					<li class="list-group-item list-group-item-{{ $statusColor }}"><strong>Order Status: {{ $order->status }}</strong></li>
					<li class="list-group-item @if (strtolower($order->purchase->status) != 'success') list-group-item-danger @else list-group-item-success @endif"><strong>Payment Status: {{ $order->purchase->status }}</strong></li>
					<li class="list-group-item list-group-item-info"><strong>Order ID: {{ $order->id }}</strong></li>
					<li class="list-group-item list-group-item-info"><strong>Transaction ID: {{ $order->purchase->transaction_id }}</strong></li>
					<li class="list-group-item list-group-item-info"><strong>Purchase ID: {{ $order->purchase->id }}</strong></li>
					@if ($order->purchase->paypal_email != null)
						<li class="list-group-item list-group-item-info">
							<strong>Paypal Email: {{ $order->purchase->paypal_email }}</strong>
						</li>
					@endif
					<li class="list-group-item list-group-item-info"><strong>Name: {{ $order->purchase->user->firstname }} {{ $order->purchase->user->surname }}</strong></li>
					<li class="list-group-item list-group-item-info">
						<strong>
							User: {{ $order->purchase->user->username }}
							@if ($order->purchase->user->steamid)
								- <span class="text-muted"><small>Steam: {{ $order->purchase->user->steamname }}</small></span>
							@endif
						</strong>
					</li>
					<li class="list-group-item list-group-item-info"><strong>Payment Type: {{ $order->purchase->type }}</strong></li>
					<li class="list-group-item list-group-item-info"><strong>Ordered at: {{ $order->created_at }}</strong></li>
				</ul>
			</div>  
		</div>
	</div>
</div>
 
@endsection