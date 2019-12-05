@extends ('layouts.admin-default')

@section ('page_title', 'Purchase - ' . $purchase->id)

@section ('content')
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">Purchases</h3>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/purchases/">Purchases</a>
			</li>
			<li class="active">
				{{ $purchase->id }}
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-credit-card fa-fw"></i> Items
			</div>
			<div class="panel-body">
				<table width="100%" class="table table-striped table-hover">
					<thead>
						<tr>
							<th>Item</th>
							<th>Quantity</th>
							<th>Price Paid</th>
						</tr>
					</thead>
					<tbody>
						@if ($purchase->order != null)
							@foreach ($purchase->order->items as $item)
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
						@elseif (!$purchase->participants->isEmpty())
							@foreach ($purchase->participants as $participant)
								<tr>
									<td>
										{{ $participant->ticket->name }} for {{ $participant->event->display_name }}
									</td>
									<td>
										1
									</td>
									<td>
										@if ($participant->ticket->price != null)
											{{ Settings::getCurrencySymbol() }}{{ $participant->ticket->price }}
											@if ($participant->ticket->price_credit != null && Settings::isCreditEnabled())
												/
											@endif
										@endif
										@if ($participant->ticket->price_credit != null && Settings::isCreditEnabled())
											{{ $item->price_credit }} Credits 
										@endif
									</td>
								</tr>
							@endforeach
						@endif
					</tbody>
				</table>
			</div>  
		</div>
	</div>
	<div class="col-xs-12 col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-credit-card fa-fw"></i> Details
			</div>
			<div class="panel-body">
				<ul class="list-group">



					<li class="list-group-item list-group-item-info"><strong>Purchase ID: <span class="pull-right">{{ $purchase->id }}</span></strong></li>
					<li class="list-group-item list-group-item-info">
						<strong>
							User: 
							<span class="pull-right">
								{{ $purchase->user->username }}
								@if ($purchase->user->steamid)
									- <span class="text-muted"><small>Steam: {{ $purchase->user->steamname }}</small></span>
								@endif
							</span>
						</strong>
					</li>
					<li class="list-group-item list-group-item-info"><strong>Name: <span class="pull-right">{{ $purchase->user->firstname }} {{ $purchase->user->surname }}</span></strong></li>
					<li class="list-group-item @if (strtolower($purchase->status) != 'success') list-group-item-danger @else list-group-item-success @endif"><strong>Status: <span class="pull-right">{{ $purchase->status }}</span></strong></li>
					<li class="list-group-item list-group-item-info"><strong>Type: <span class="pull-right">{{ $purchase->type }}</span></strong></li>
					@if ($purchase->paypal_email != null)
						<li class="list-group-item list-group-item-info">
							<strong>Paypal Email: <span class="pull-right">{{ $purchase->paypal_email }}</span></strong>
						</li>
					@endif
					<li class="list-group-item list-group-item-info"><strong>Transaction ID: <span class="pull-right">{{ $purchase->transaction_id }}</span></strong></li>
					<li class="list-group-item list-group-item-info"><strong>Timestamp: <span class="pull-right">{{ $purchase->created_at }}</span></strong></li>
				</ul>
				@if ($purchase->order)
					<a href="/admin/orders/{{ $purchase->order->id }}"><button class="btn btn-block btn-success">View Order</button></a>
				@endif
				@if (count($purchase->participants) > 0)
					@foreach ($purchase->participants as $participant)
						<a href="/admin/events/{{ $participant->event->slug }}/participants/{{ $participant->id }}"><button class="btn btn-block btn-success">View Participant - {{ $participant->user->username }}</button></a>
					@endforeach
				@endif
			</div>  
		</div>
	</div>
</div>

@endsection