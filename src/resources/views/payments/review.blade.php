@extends ('layouts.default')

@section ('page_title', 'Review Terms & Conditions')

@section ('content')

<div class="container">
	<div class="page-header">
		<h1>
			Review Terms & Conditions of Purchase
		</h1> 
	</div>
	<div class="row">
		<div class="col-xs-12 col-md-8">
			@include ('layouts._partials._terms.purchase')
			<hr>
			@if (!$nextEventFlag)
				<div class="alert alert-warning">
					<h5>Please be aware you are not purchasing tickets for our next event but instead a future event after that.</h5>
				</div>
			@endif
			<div class="alert alert-warning">
				<h5>By Clicking on Continue you are agreeing to the Terms and Conditions as set by {!! config('app.name') !!}</h5>
			</div>
			{{ Form::open(array('url'=>'/payment/post')) }}
				{{ Form::hidden('gateway', $paymentGateway) }}
				<button class="btn btn-primary btn-block">Continue</button>
			{{ Form::close() }}
		</div>
		<div class="col-xs-12 col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Order Details</h3>
				</div>
				<div class="panel-body">
					@include ('layouts._partials._checkout.basket')
				</div>
			</div>
		</div>
	</div>
</div>

@endsection