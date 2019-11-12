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
					<li class="list-group-item">Purchase ID: {{ $purchase->id }}</li>
					<li class="list-group-item">
						User: {{ $purchase->user->username }}
						@if ($purchase->user->steamid)
							- <span class="text-muted"><small>Steam: {{ $purchase->user->steamname }}</small></span>
						@endif
					</li>
					<li class="list-group-item">Name: {{ $purchase->user->firstname }} {{ $purchase->user->surname }}</li>
					<li class="list-group-item">Status: {{ $purchase->status }}</li>
					<li class="list-group-item">Type: {{ $purchase->type }}</li>
					@if ($purchase->paypal_email != null)
						<li class="list-group-item">
							Paypal Email: {{ $purchase->paypal_email }} 
						</li>
					@endif
					<li class="list-group-item">Transaction ID: {{ $purchase->transaction_id }}</li>
					<li class="list-group-item">Timestamp: {{ $purchase->created_at }}</li>
				</ul>
			</div>  
		</div>
	</div>
</div>

@endsection