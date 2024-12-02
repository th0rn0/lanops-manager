@extends ('layouts.default')

@section ('page_title', 'Checkout')

@section ('content')

<div class="container">
	<div class="page-header">
		<h1>
			Checkout
		</h1> 
	</div>
	<div class="row">
		<div class="col-xs-12 col-md-12">
			@if ($user->getReferralsUnclaimedCount())
				<div class="alert alert-info">
					A referral discount has been applied to the basket!
				</div>
			@endif
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Order Details</h3>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						@include ('layouts._partials._checkout.basket')
					</div>
				</div>
			</div>
			@if ($user->isReferrable())
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">This looks like it will be your first event!</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-xs-12 col-md-6">
								Use a friends Code to gift them {{ config('app.currency_symbol') }}{{ config('app.refer_a_friend_discount') }} off their next ticket!
							</div>
							<div class="col-xs-12 col-md-6">
								@if ($basket->referral_code)
									<b>{{ \App\Models\User::getUserByReferralCode($basket->referral_code)->username}}'s Referral Code has been applied! Such a rinsing Geezer!</b>
								@else
									{{ Form::open(array('url'=>'/payment/checkout/code' )) }}
										<div class="form-group">
											{{ Form::text('referral_code', '', array('id'=>'referral_code','class'=>'form-control')) }}
										</div>
										<button type="submit" class="btn btn-primary btn-block">Submit</button>
									{{ Form::close() }}
								@endif
							</div>
						</div>
					</div>
				</div>
			@endif
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Payment</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						@foreach ($activePaymentGateways as $gateway)
							<div class="col-xs-12 col-md-6">
								<div class="section-header">
									<h4>
										{{ config('laravel-omnipay.gateways.'.$gateway.'.options.displayName') }}
									</h4>
									<hr>
								</div>
								<a href="/payment/review/{{ $gateway }}">
									<button type="button" class="btn btn-primary btn-block">
										Pay by {{ config('laravel-omnipay.gateways.'.$gateway.'.options.displayName') }}
									</button>
								</a>
								<p><small>{{ config('laravel-omnipay.gateways.'.$gateway.'.options.note') }}</small></p>
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection