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
					{{ Form::label('card_number', 'Card Details', array('id'=>'','class'=>'')) }}
					<div class="form-control" id="card-element"></div>
			  		<div id="card-errors" role="alert"></div>
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

        var style = {
            base: {
			  	lineHeight: '1.429'
            }
        };

	    const stripe = Stripe( "{!! config('laravel-omnipay.gateways.stripe.credentials.public') !!}" );

	    const elements = stripe.elements();
	    const cardElement = elements.create('card', {style: style});

	    cardElement.mount('#card-element');

	    const cardHolderName = document.getElementById('card_first_name') + document.getElementById('card_last_name');
		const cardButton = document.getElementById('checkout_btn');
		const clientSecret = cardButton.dataset.secret;
		const form = document.getElementById('payment-form');
		cardButton.addEventListener('click', async (e) => {
		    stripe.createPaymentMethod('card', cardElement, {
                billing_details: {name: cardHolderName.value }
            }).then(function(result) {

                if (result.error) {
                    cardButton.disabled = false;
                    alert(result.error.message);
                } else {
					var hiddenInput = document.createElement('input');
					hiddenInput.setAttribute('type', 'hidden');
					hiddenInput.setAttribute('name', 'stripe_token');
					hiddenInput.setAttribute('value', result.paymentMethod.id);
					form.appendChild(hiddenInput);
					console.log(result.paymentMethod.id);
					form.submit();
                }
            });
		});
	</script>
@endif

@endsection