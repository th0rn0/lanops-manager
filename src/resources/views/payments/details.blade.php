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
				{{ Form::open(array('url'=>'/payment/post')) }}
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
					<div class="form-group">
						{{ Form::label('card_number', 'Card Number *', array('id'=>'','class'=>'')) }}
						{{ Form::text('card_number', '', array('id'=>'card_number','class'=>'form-control')) }}
					</div>
					<div class="row">
						<div class="form-group col-sm-4 col-xs-6">
							{{ Form::label('card_expiry_month', 'Card Expiry Month *', array('id'=>'','class'=>'')) }}
							{{ Form::text('card_expiry_month', '', array('id'=>'card_expiry_month','class'=>'form-control')) }}
						</div> 
						<div class="form-group col-sm-4 col-xs-6">
							{{ Form::label('card_expiry_year', 'Card Expiry Year *', array('id'=>'','class'=>'')) }}
							{{ Form::text('card_expiry_year', '', array('id'=>'card_expiry_year','class'=>'form-control')) }}
						</div>
						<div class="form-group col-sm-4 col-xs-12">
							{{ Form::label('card_cvv', 'Card CVV', array('id'=>'','class'=>'')) }}
							{{ Form::text('card_cvv', '', array('id'=>'card_cvv','class'=>'form-control')) }}
						</div>
					</div>
					<div class="form-group">
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
					</div>
					<p><small>* Required Fields</small></p>
					{{ Form::hidden('gateway', $paymentGateway) }}
					<button class="btn btn-default">Confirm Order</button>
				{{ Form::close() }}
			@else ($paymentGateway == 'credit' && Settings::isCreditEnabled())
				<h5>Credit: {{ $user->credit_total }}</h5>
				<h5>Credit After Purchase: {{ $user->credit_total - $basket->total_credit }}</h5>
				@if ($user->checkCredit(-1 * abs($basket->total_credit)))
					<hr>
					<div class="alert alert-warning">
						<h5>You have enough credit to make this purchase. Please not Credit Purchases are non refundable!</h5>
					</div>
					{{ Form::open(array('url'=>'/payment/post')) }}
					{{ Form::hidden('gateway', $paymentGateway) }}
					{{ Form::hidden('confirm', true) }}
						<button class="btn btn-default">Confirm Order</button>
					{{ Form::close() }}
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
		</div>
	</div>
</div>

@endsection