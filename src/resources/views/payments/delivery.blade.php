@extends ('layouts.default')

@section ('page_title', 'Delivery Details')

@section ('content')

<div class="container">
	<div class="page-header">
		<h1>
			Delivery Details
		</h1> 
	</div>
	<div class="row">
		<div class="col-xs-12 col-md-8">
			{{ Form::open(array('url'=>'/payment/post')) }}
				<div class="row">
					<div class="form-group col-sm-6 col-xs-12">
						{{ Form::label('shipping_first_name', 'First Name *', array('id'=>'','class'=>'')) }}
						{{ Form::text('shipping_first_name', '', array('id'=>'shipping_first_name','class'=>'form-control')) }}
					</div> 
					<div class="form-group col-sm-6 col-xs-12">
						{{ Form::label('shipping_last_name', 'Last Name *', array('id'=>'','class'=>'')) }}
						{{ Form::text('shipping_last_name', '', array('id'=>'shipping_last_name','class'=>'form-control')) }}
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('shipping_address_1', 'Shipping Address 1 *', array('id'=>'','class'=>'')) }}
					{{ Form::text('shipping_address_1', '', array('id'=>'shipping_address_1','class'=>'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::label('shipping_address_2', 'Shipping Address 2', array('id'=>'','class'=>'')) }}
					{{ Form::text('shipping_address_2', '', array('id'=>'shipping_address_2','class'=>'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::label('shipping_country', 'Shipping Country', array('id'=>'','class'=>'')) }}
					{{ Form::text('shipping_country', '', array('id'=>'shipping_country','class'=>'form-control')) }}
				</div>
				<div class="row">
					<div class="form-group col-sm-6 col-xs-12">
						{{ Form::label('shipping_postcode', 'Shipping Postcode *', array('id'=>'','class'=>'')) }}
						{{ Form::text('shipping_postcode', '', array('id'=>'shipping_postcode','class'=>'form-control')) }}
					</div>
					<div class="form-group col-sm-6 col-xs-12">
						{{ Form::label('shipping_state', 'Shipping State', array('id'=>'','class'=>'')) }}
						{{ Form::text('shipping_state', '', array('id'=>'shipping_state','class'=>'form-control')) }}
					</div>
				</div>
				<p><small>* Required Fields</small></p>
				{{ Form::hidden('gateway', $paymentGateway) }}
				<button class="btn btn-default">Continue</button>
			{{ Form::close() }}
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