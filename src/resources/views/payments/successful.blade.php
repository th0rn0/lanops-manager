@extends ('layouts.default')

@section ('page_title', __('payments.payment_successful'))

@section ('content')

<div class="container">
	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h1>
			@lang('payments.thank_you')
		</h1>
	</div>
	<div class="row">
		@if ($type == 'tickets')
			<div class="col-12 col-md-8">
				<h3>@lang('payments.tickets_active')</h3>
				<h4>@lang('payments.payment_successful_text1') <a href="/events/{{ $purchase->participants{0}->event->slug }}/#seating">@lang('payments.payment_successful_linktext1')</a></h4>
				<h4>@lang('payments.payment_successful_text2') <a href="/events/{{ $purchase->participants{0}->event->slug }}/#tournaments">@lang('payments.payment_successful_linktext2')</a></h4>
				<p><strong>@lang('payments.purchase_id')</strong> {{ $purchase->id }}</p>
				<p><strong>@lang('payments.payment_method')</strong> {{ $purchase->getPurchaseType() }}</p>
				<h3>@lang('payments.tickets')</h3>
				<hr>
				<div class="row">
					@foreach ($purchase->participants as $participant)
						<div class="col-lg-4 col-sm-6 col-12 text-center">
							<h5>{{ $participant->event->display_name }}</h5>
							<h5>{{ $participant->ticket->name }}</h5>
							<img class="img img-fluid" src="/{{ $participant->qrcode }}"/>
						</div>
					@endforeach
				</div>
			</div>
			<div class="col-12 col-md-4">
				<div class="card @if(Settings::isDarkModeEnabled()) border-light @endif mb-3">
					<div class="card-header @if(Settings::isDarkModeEnabled()) border-light @endif ">
						<h3 class="card-title">@lang('payments.order_details')</h3>
					</div>
					<div class="card-body">
						<table class="table table-striped table-responsive">
							<tbody>
								@php ($total = 0)
								@foreach ($basket as $item)
									<tr>
										<td>
											<strong>{{ $item->name }}</strong>
										</td>
										<td>
											x {{ $item->quantity }}
										</td>
										<td>
											{{ Settings::getCurrencySymbol() }}{{ $item->price }}
										</td>
									</tr>
								@endforeach
								<tr>
									<td></td>
									<td>
										<strong>Total:</strong>
									</td>
									<td>
										{{ Settings::getCurrencySymbol() }}{{ $basket->total }}
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		@elseif ($type == 'shop')
			<div class="col-12 col-md-12">
				<h3>@lang('payments.order_successful')</h3>
				<h4></h4>
				<p><strong>@lang('payments.purchase_id')</strong> {{ $purchase->id }}</p>
				<p><strong>@lang('payments.payment_method')</strong> {{ $purchase->getPurchaseType() }}</p>
				<h3>@lang('payments.items')</h3>
				<hr>
				<div class="row">
					@foreach ($basket as $item)
						<div class="col-lg-3 col-sm-4 col-12 text-center">
							<h5>{{ $item->name }}</h5>
							<h5>@lang('payments.quantity') {{ $item->quantity }}</h5>
							<h5>
								@if ($item->price != null && $item->price != 0)
									{{ Settings::getCurrencySymbol() }}{{ $item->price * $item->quantity }}
									@if ($item->price_credit != null && Settings::isCreditEnabled() && $item->price_credit != 0)
										/
									@endif
								@endif
								@if ($item->price_credit != null && Settings::isCreditEnabled() && $item->price_credit != 0)
									{{ $item->price_credit * $item->quantity }} @lang('payments.credits')
								@endif
							</h5>
							<img class="img img-fluid rounded" src="{{ $item->getDefaultImageUrl() }}"/>
						</div>
					@endforeach
				</div>
			</div>
		@endif
	</div>
</div>

@endsection