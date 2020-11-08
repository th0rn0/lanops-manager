@extends ('layouts.default')

@section ('page_title', __('payments.checkout'))

@section ('content')

<div class="container">
	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h1>
			@lang('payments.checkout')
		</h1>
	</div>
	<div class="row">
		<div class="col-12 col-md-12">
			<div class="card @if(Settings::isDarkModeEnabled()) border-light @endif mb-3">
				<div class="card-header @if(Settings::isDarkModeEnabled()) border-light @endif ">
					<h3 class="card-title">@lang('payments.order_details')</h3>
				</div>
				<div class="card-body">
					<table class="table table-striped table-responsive">
						<tbody>
							@foreach ($basket as $item)
								<tr>
									<td>
										<strong>{{ $item->name }}</strong>
									</td>
									<td class="text-right">
										x {{ $item->quantity }}
									</td>
									<td class="text-right">
										@if ($item->price != null && $item->price != 0)
											{{ Settings::getCurrencySymbol() }}{{ number_format($item->price, 2) }}
											@if ($item->price_credit != null && Settings::isCreditEnabled() && $item->price_credit != 0)
												/
											@endif
										@endif
										@if ($item->price_credit != null && Settings::isCreditEnabled() && $item->price_credit != 0)
											{{ number_format($item->price_credit, 2) }} @lang('payments.credits')
										@endif
										Each
									</td>
									<td class="text-right">
										@if ($item->price != null && $item->price != 0)
											{{ Settings::getCurrencySymbol() }}{{ number_format($item->price * $item->quantity, 2) }}
											@if ($item->price_credit != null && Settings::isCreditEnabled() && $item->price_credit != 0)
												/
											@endif
										@endif
										@if ($item->price_credit != null && Settings::isCreditEnabled() && $item->price_credit != 0)
											{{ number_format($item->price_credit * $item->quantity, 2) }} @lang('payments.credits')
										@endif
									</td>
								</tr>
							@endforeach
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td class="text-right">
									<strong>@lang('payments.total')</strong>
									@if ($basket->total != null)
										{{ Settings::getCurrencySymbol() }}{{ number_format($basket->total, 2) }}
										@if ($basket->total_credit != null && Settings::isCreditEnabled())
											/
										@endif
									@endif
									@if ($basket->total_credit != null && Settings::isCreditEnabled())
										{{ number_format($basket->total_credit, 2) }} @lang('payments.credits')
									@endif
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="card @if(Settings::isDarkModeEnabled()) border-light @endif mb-3">
				<div class="card-header @if(Settings::isDarkModeEnabled()) border-light @endif ">
					<h3 class="card-title">@lang('payments.payment')</h3>
				</div>
				<div class="card-body">
					<div class="row">
						@foreach ($activePaymentGateways as $gateway)
							<div class="col-12 col-md-6">
								<div class="section-header">
									<h4>
										{{ Settings::getPaymentGatewayDisplayName($gateway) }}
									</h4>
									<hr>
								</div>
								<a href="/payment/review/{{ $gateway }}">
									<button type="button" class="btn btn-primary btn-block">
										@lang('payments.pay_by_1') {{ Settings::getPaymentGatewayDisplayName($gateway) }} @lang('payments.pay_by_2')
									</button>
								</a>
								<p><small>{{ Settings::getPaymentGatewayNote($gateway) }}</small></p>
							</div>
						@endforeach
						@if (Settings::isCreditEnabled())
							<div class="col-12 col-md-6">
								<div class="section-header">
									<h4>
									@lang('payments.credit')
									</h4>
									<hr>
								</div>
								<a href="/payment/review/credit">
									<button type="button" class="btn btn-primary btn-block">
									@lang('payments.pay_with_credit')
									</button>
								</a>
								<p><small>@lang('payments.credit_no_refund')</small></p>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection