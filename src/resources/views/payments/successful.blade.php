@extends ('layouts.default')

@section ('page_title', 'Payment Successful!')

@section ('content')

<div class="container">
	<div class="page-header">
		<h1>
			Thank you for your Payment!
		</h1> 
	</div>
	<div class="row">
		@if ($type == 'tickets')
			<div class="col-xs-12 col-md-8">
				<h3>Your Tickets are now active</h3>
				<h4>You may now go to the <a href="/events/{{ $purchase->participants{0}->event->slug }}/#seating">Events Page and Book a Seat!</a></h4>
				<p><strong>Purchase ID:</strong> {{ $purchase->id }}</p>
				<p><strong>Payment Method:</strong> {{ $purchase->getPurchaseType() }}</p>
				<h3>Tickets</h3>
				<hr>
				<div class="row">
					@foreach ($purchase->participants as $participant)
						<div class="col-lg-4 col-sm-6 col-xs-12 text-center">
							<h5>{{ $participant->event->display_name }}</h5>
							<h5>{{ $participant->ticket->name }}</h5>
							<img class="img img-responsive" src="/{{ $participant->qrcode }}"/>
						</div>
					@endforeach
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Order Details</h3>
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped">
								<tbody>
									@php ($total = 0)
									@foreach ($basket as $item)
										<tr>
											<td>
												<strong>{{ $item->name }}</strong>
											</td>
											<td>
												x {{ $item->quantity }}
											</td>
											<td>
												{{ Settings::getCurrencySymbol() }}{{ $item->price }}
											</td>
										</tr>
									@endforeach
									<tr>
										<td></td>
										<td>
											<strong>Total:</strong>
										</td>
										<td>
											{{ Settings::getCurrencySymbol() }}{{ $basket->total }}
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		@elseif ($type == 'shop')
			<div class="col-xs-12 col-md-12">
				<h3>Order Successful!</h3>
				<h4></h4>
				<p><strong>Purchase ID:</strong> {{ $purchase->id }}</p>
				<p><strong>Payment Method:</strong> {{ $purchase->getPurchaseType() }}</p>
				<h3>Items</h3>
				<hr>
				<div class="row">
					@foreach ($basket as $item)
						<div class="col-lg-3 col-sm-4 col-xs-12 text-center">
							<h5>{{ $item->name }}</h5>
							<h5>Quantity: {{ $item->quantity }}</h5>
							<h5>
								@if ($item->price != null && $item->price != 0)
									{{ Settings::getCurrencySymbol() }}{{ $item->price * $item->quantity }}
									@if ($item->price_credit != null && Settings::isCreditEnabled() && $item->price_credit != 0)
										/
									@endif
								@endif
								@if ($item->price_credit != null && Settings::isCreditEnabled() && $item->price_credit != 0)
									{{ $item->price_credit * $item->quantity }} Credits
								@endif
							</h5>
							<img class="img img-responsive img-rounded" src="{{ $item->getDefaultImageUrl() }}"/>
						</div>
					@endforeach
				</div>
			</div>
		@endif
	</div>
</div>

@endsection