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
		<div class="col-xs-12 col-md-8">
			<h3>Your Tickets are now active</h3>
			<h4>You may now go to the <a href="/events/{{ $purchase->participants{0}->event->slug }}/#seating">Events Page and Book a Seat!</a></h4>
			<p><strong>Purchase ID:</strong> {{ $purchase->id }}</p>
			<p><strong>Payment Method:</strong> {{ $purchase->getPurchaseType() }}</p>
			@if (is_null(Auth::user()->discord_id))
				<h4>You havent linked your discord account! <a href="/account">Click here to link it!</a></h4> 
			@endif
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
											{{ config('app.currency_symbol') }}{{ $item->price }}
										</td>
									</tr>
								@endforeach
								<tr>
									<td></td>
									<td>
										<strong>Total:</strong>
									</td>
									<td>
										{{ config('app.currency_symbol') }}{{ $basket->total }}
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection