@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' Shop | Cart')

@section ('content')
			
<div class="container">
	<div class="page-header">
		<h1>
			Shop - Cart
		</h1>
	</div>
	@include ('layouts._partials._shop.navigation')
	<div class="row">
		<div class="col-xs-12 col-md-12">
			<div class="table-responsive">
				@if (isset($cart) && strtolower($cart) != 'empty')
					<table class="table table-striped">
						<tbody>
							@foreach ($cart as $item)
								<tr>
									<td>
										<strong>{{ $item->name }}</strong>
									</td>
									<td class="text-right">
										x {{ $item->quantity }}
									</td>
									<td class="text-right">
										@if ($item->price_real != null)
											£{{ $item->price_real }}
											@if ($item->price_credit != null)
												/
											@endif
										@endif
										@if ($item->price_credit != null)
											{{ $item->price_credit }} Credits
										@endif
										Each
									</td>
									<td class="text-right">
										@if ($item->price_real != null)
											£{{ $item->price_real * $item->quantity }}
											@if ($item->price_credit != null)
												/
											@endif
										@endif
										@if ($item->price_credit != null)
											{{ $item->price_credit * $item->quantity }} Credits
										@endif
									</td>
								</tr>
							@endforeach
							<tr>
								<td></td>
								<td></td>
								<td>
									
								</td>
								<td class="text-right">
									<strong>Total:</strong>
									@if ($cart->total_real != 0)
										£{{ $cart->total_real }}
										@if ($cart->total_credit != 0)
											/
										@endif
									@endif
									@if ($cart->total_credit != 0)
										{{ $cart->total_credit }} Credits
									@endif
								</td>
							</tr>
						</tbody>
					</table>
				@else
					<p>Cart is Empty</p>
				@endif
			</div>
		</div>
	</div>
</div>

@endsection
