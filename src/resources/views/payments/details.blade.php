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
			{{ Form::open(array('url'=>'/payment/post')) }}
				<div class="row">
					<div class="form-group col-sm-6 col-xs-12">
						{{ Form::label('card_first_name', 'First Name', array('id'=>'','class'=>'')) }}
						{{ Form::text('card_first_name', '', array('id'=>'card_first_name','class'=>'form-control')) }}
					</div> 
					<div class="form-group col-sm-6 col-xs-12">
						{{ Form::label('card_last_name', 'Last Name', array('id'=>'','class'=>'')) }}
						{{ Form::text('card_last_name', '', array('id'=>'card_last_name','class'=>'form-control')) }}
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('card_number', 'Card Number', array('id'=>'','class'=>'')) }}
					{{ Form::text('card_number', '', array('id'=>'card_number','class'=>'form-control')) }}
				</div> 
				<div class="form-group">
					{{ Form::label('card_expiry_month', 'Card Expiry Month', array('id'=>'','class'=>'')) }}
					{{ Form::text('card_expiry_month', '', array('id'=>'card_expiry_month','class'=>'form-control')) }}
				</div> 
				<div class="form-group">
					{{ Form::label('card_expiry_year', 'Card Expiry Year', array('id'=>'','class'=>'')) }}
					{{ Form::text('card_expiry_year', '', array('id'=>'card_expiry_year','class'=>'form-control')) }}
				</div>
				TODO: Card Type, CVV, Billing Address 1, Billing Address 2, Billing County, Billing City, Billing Post Code
				{{ Form::hidden('gateway', $paymentGateway) }}
				<button class="btn btn-default">Confirm Order</button>
			{{ Form::close() }}
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
								@foreach ($basketItems as $item)
									<tr>
										<td>
											<strong>{{ $item->name }}</strong>
										</td>
										<td>
											x {{ $item->quantity }}
										</td>
										<td>
											£{{ $item->price }}
										</td>
									</tr>
								@endforeach
								<tr>
									<td></td>
									<td>
										<strong>Total:</strong>
									</td>
									<td>
										£{{ $basketTotal }}
									</td>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection