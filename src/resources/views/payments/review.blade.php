@extends ('layouts.default')

@section ('page_title', __('payments.review_terms'))

@section ('content')

<div class="container">
	<div class="page-header">
		<h1>
		@lang('payments.review_terms_long')
		</h1> 
	</div>
	<div class="row">
		<div class="col-xs-12 col-md-8">
			{!! Settings::getPurchaseTermsAndConditions() !!}
			<hr>
			@if (!$nextEventFlag)
				<div class="alert alert-warning">
					<h5>@lang('payments.purchase_future_event')</h5>
				</div>
			@endif
			<div class="alert alert-warning">
				<h5>@lang('payments.purchase_future_event') {!! Settings::getOrgName() !!}</h5>
			</div>
			{{ Form::open(array('url'=>'/payment/post')) }}
				{{ Form::hidden('gateway', $paymentGateway) }}
				<button class="btn btn-primary btn-block">@lang('payments.delivery_continue')</button>
			{{ Form::close() }}
		</div>
		<div class="col-xs-12 col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">@lang('payments.order_details')</h3>
				</div>
				<div class="panel-body">
					@include ('layouts._partials._shop.basket-preview')
				</div>
			</div>
		</div>
	</div>
</div>

@endsection