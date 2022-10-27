@extends ('layouts.default')

@section ('page_title', __('payments.review_terms'))

@section ('content')

<div class="container">
	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h1>
		@lang('payments.review_terms_long')
		</h1>
	</div>
	<div class="row">
		<div class="col-12 col-md-8">
			{!! Settings::getPurchaseTermsAndConditions() !!}
			<hr>
			@if (!$nextEventFlag)
				<div class="alert alert-warning">
					<h5>@lang('payments.purchase_future_event')</h5>
				</div>
			@endif
			<div class="alert alert-warning">
				<h5>@lang('payments.terms_accept') {!! Settings::getOrgName() !!}</h5>
			</div>
			{{ Form::open(array('url'=>'/payment/post')) }}
				{{ Form::hidden('gateway', $paymentGateway) }}
				<button class="btn btn-primary btn-block">@lang('payments.delivery_continue')</button>
			{{ Form::close() }}
		</div>
		<div class="col-12 col-md-4">
			<div class="card mb-3">
				<div class="card-header ">
					<h3 class="card-title">@lang('payments.order_details')</h3>
				</div>
				<div class="card-body">
					@include ('layouts._partials._shop.basket-preview')
				</div>
			</div>
		</div>
	</div>
</div>

@endsection