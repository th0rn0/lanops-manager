@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' Shop | Basket')

@section ('content')
			
<div class="container">
	<div class="page-header">
		<h1>
			Shop - Basket
		</h1>
	</div>
	@include ('layouts._partials._shop.navigation')
	<div class="row">
		<div class="col-xs-12 col-md-12">
			<div class="table-responsive">
				@if (isset($basket) && strtolower($basket) != 'empty')
					<table class="table table-striped">
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
										@if ($item->price != null)
											{{ Settings::getCurrencySymbol() }}{{ $item->price }}
											@if ($item->price_credit != null && Settings::isCreditEnabled())
												/
											@endif
										@endif
										@if ($item->price_credit != null && Settings::isCreditEnabled())
											{{ $item->price_credit }} Credits
										@endif
										Each
									</td>
									<td class="text-right">
										@if ($item->price != null)
											{{ Settings::getCurrencySymbol() }}{{ $item->price * $item->quantity }}
											@if ($item->price_credit != null && Settings::isCreditEnabled())
												/
											@endif
										@endif
										@if ($item->price_credit != null && Settings::isCreditEnabled())
											{{ $item->price_credit * $item->quantity }} Credits
										@endif
									</td>
									<td>
										{{ Form::open(array('url'=>'/shop/basket')) }}
											{{ Form::hidden('shop_item_id', $item->id) }}
											{{ Form::hidden('action', 'remove') }}
											<button type="submit" class="btn btn-success">X</button>
										{{ Form::close() }}
									</td>
								</tr>
							@endforeach
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td class="text-right">
									<strong>Total:</strong>
									@if ($basket->total != 0)
										{{ Settings::getCurrencySymbol() }}{{ $basket->total }}
										@if ($basket->total_credit != 0 && Settings::isCreditEnabled())
											/
										@endif
									@endif
									@if ($basket->total_credit != 0 && Settings::isCreditEnabled())
										{{ $basket->total_credit }} Credits
									@endif
								</td>
								<td>
								</td>
							</tr>
						</tbody>
					</table>
					<a href="/payment/checkout">
						<button type="button" class="btn btn-sm btn-success">Checkout</button>
					</a>
				@else
					<p>Basket is Empty</p>
				@endif
			</div>
		</div>
	</div>
</div>

@endsection
