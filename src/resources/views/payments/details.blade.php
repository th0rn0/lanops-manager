@extends ('layouts.default')

@section ('page_title', 'Payment Details')

@section ('content')

<div class="container">
	<div class="page-header">
		<h1>
			Payment Details
		</h1> 
	</div>
	<div class="row">
		<div class="col-xs-12 col-md-8">
			@if ($paymentGateway == 'stripe')
				
				{{ Form::open(array('url'=>'/payment/post', 'id'=>'payment-form')) }}
					<div class="row">
						<div class="form-group col-sm-6 col-xs-12">
							{{ Form::label('card_first_name', 'First Name *', array('id'=>'','class'=>'')) }}
							{{ Form::text('card_first_name', '', array('id'=>'card_first_name','class'=>'form-control')) }}
						</div> 
						<div class="form-group col-sm-6 col-xs-12">
							{{ Form::label('card_last_name', 'Last Name *', array('id'=>'','class'=>'')) }}
							{{ Form::text('card_last_name', '', array('id'=>'card_last_name','class'=>'form-control')) }}
						</div>
					</div>
					<div class="form-group" id="card-element"></div>
			  		<div id="card-errors" role="alert"></div>
					<!-- <div class="form-group">
						{{ Form::label('card_number', 'Card Number *', array('id'=>'','class'=>'')) }}
						{{ Form::text('card_number', '', array('id'=>'card_number','class'=>'form-control')) }}
					</div>
					<div class="row">
						<div class="form-group col-sm-4 col-xs-6">
							{{ Form::label('card_expiry_month', 'Card Expiry Month *', array('id'=>'','class'=>'')) }}
							{{ Form::select('card_expiry_month', Helpers::getCardExpiryMonthDates(), null, array('id'=>'card_expiry_month','class'=>'form-control')) }}
						</div> 
						<div class="form-group col-sm-4 col-xs-6">
							{{ Form::label('card_expiry_year', 'Card Expiry Year *', array('id'=>'','class'=>'')) }}
							{{ Form::select('card_expiry_year', Helpers::getCardExpiryYearDates(), null, array('id'=>'card_expiry_year','class'=>'form-control')) }}
						</div>
						<div class="form-group col-sm-4 col-xs-12">
							{{ Form::label('card_cvv', 'Card CVV', array('id'=>'','class'=>'')) }}
							{{ Form::text('card_cvv', '', array('id'=>'card_cvv','class'=>'form-control', 'maxlength'=>'3')) }}
						</div>
					</div> -->
				<!-- 	<div class="form-group">
						{{ Form::label('billing_address_1', 'Billing Address 1 *', array('id'=>'','class'=>'')) }}
						{{ Form::text('billing_address_1', '', array('id'=>'billing_address_1','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('billing_address_2', 'Billing Address 2', array('id'=>'','class'=>'')) }}
						{{ Form::text('billing_address_2', '', array('id'=>'billing_address_2','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('billing_country', 'Billing Country', array('id'=>'','class'=>'')) }}
						{{ Form::text('billing_country', '', array('id'=>'billing_country','class'=>'form-control')) }}
					</div>
					<div class="row">
						<div class="form-group col-sm-6 col-xs-12">
							{{ Form::label('billing_postcode', 'Billing Postcode *', array('id'=>'','class'=>'')) }}
							{{ Form::text('billing_postcode', '', array('id'=>'billing_postcode','class'=>'form-control')) }}
						</div>
						<div class="form-group col-sm-6 col-xs-12">
							{{ Form::label('billing_state', 'Billing State', array('id'=>'','class'=>'')) }}
							{{ Form::text('billing_state', '', array('id'=>'billing_state','class'=>'form-control')) }}
						</div>
					</div> -->
					<p><small>* Required Fields</small></p>
					{{ Form::hidden('gateway', $paymentGateway) }}
					<button type="button" id="checkout_btn" class="btn btn-primary btn-block">Confirm Order</button>
				{{ Form::close() }}
			@else ($paymentGateway == 'credit' && Settings::isCreditEnabled())
				<h5>Credit: {{ $user->credit_total }}</h5>
				<h5>Credit After Purchase: {{ $user->credit_total - $basket->total_credit }}</h5>
				@if ($user->checkCredit(-1 * abs($basket->total_credit)) && $basket->allow_credit)
					<hr>
					<div class="alert alert-warning">
						<h5>You have enough credit to make this purchase. Please not Credit Purchases are non refundable!</h5>
					</div>
					{{ Form::open(array('url'=>'/payment/post')) }}
					{{ Form::hidden('gateway', $paymentGateway) }}
					{{ Form::hidden('confirm', true) }}
						<button class="btn btn-primary btn-block">Confirm Order</button>
					{{ Form::close() }}
				@elseif (!$basket->allow_credit)
					<hr>
					<div class="alert alert-warning">
						<h5>You cannot use credit to purchase this basket! Please go <a href="/payment/checkout">back</a> and try another method</h5>
					</div>
				@else
					<hr>
					<div class="alert alert-warning">
						<h5>You do not have enough credit to make this purchase on your account! Please go <a href="/payment/checkout">back</a> and try another method</h5>
					</div>
				@endif
			@endif
		</div>
		<div class="col-xs-12 col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Order Details</h3>
				</div>
				<div class="panel-body">
					@include ('layouts._partials._shop.basket-preview')
				</div>
			</div>
			@if ($delivery && $deliveryDetails)
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Delivery Details</h3>
					</div>
					<div class="panel-body">
						@if ($deliveryDetails && $deliveryDetails['type'] == 'shipping')
							<address>
								<strong>{{ $delivery['shipping_first_name']}} {{ $delivery['shipping_last_name'] }}</strong><br>
								{{ $delivery['shipping_address_1'] }}<br>
								@if (trim($delivery['shipping_address_2']) != '')
									{{ $delivery['shipping_address_2'] }}<br>
								@endif
								@if (trim($delivery['shipping_country']) != '')
									{{ $delivery['shipping_country'] }}<br>
								@endif
								@if (trim($delivery['shipping_state']) != '')
									{{ $delivery['shipping_state'] }}<br>
								@endif
								{{ $delivery['shipping_postcode'] }}
							</address>
						@else
							<strong>Delivery to the next event you Attend</strong>
						@endif
					</div>
				</div>
			@endif
		</div>

	</div>
</div>
@if ($paymentGateway == 'stripe')
	<script src="https://js.stripe.com/v3/"></script>
	<script>
	    const stripe = Stripe( '{!! env('STRIPE_PUBLIC_KEY') !!}' );

	    const elements = stripe.elements();
	    const cardElement = elements.create('card');

	    cardElement.mount('#card-element');

	    const cardHolderName = document.getElementById('card_first_name') + document.getElementById('card_last_name');
		const cardButton = document.getElementById('checkout_btn');
		const clientSecret = cardButton.dataset.secret;
		cardButton.addEventListener('click', async (e) => {
			stripe.createToken(cardElement).then(function(result) {
			    if (result.error) {
			      	// Inform the customer that there was an error.
			      	var errorElement = document.getElementById('card-errors');
			      	errorElement.textContent = result.error.message;
			    } else {
			      	// Send the token to your server.
			      	stripeTokenHandler(result.token);
			    }
			});
		});

		function stripeTokenHandler(token) {
			// Insert the token ID into the form so it gets submitted to the server
			var form = document.getElementById('payment-form');
			var hiddenInput = document.createElement('input');
			hiddenInput.setAttribute('type', 'hidden');
			hiddenInput.setAttribute('name', 'stripe_token');
			hiddenInput.setAttribute('value', token.id);
			form.appendChild(hiddenInput);

			// Submit the form
			form.submit();
		}
	</script>
@endif

@endsection