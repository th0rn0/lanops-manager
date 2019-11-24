@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' Shop | Orders')

@section ('content')
			
<div class="container">
	<div class="page-header">
		<h1>
			Shop - Orders
		</h1>
	</div>
	@include ('layouts._partials._shop.navigation')
	@foreach ($orders as $order)
		{{ $order }}
	@endforeach
	<table class="table table-striped">
		<thead>
			<tr>
				<th>
					Purchase ID
				</th>
				<th>
					Status
				</th>
				<th>
					Method
				</th>
				<th>
					Time
				</th>
				<th>
					Basket
				</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($orders as $order)
				<tr>
					<td>
						{{ $order->purchase->id }}
					</td>
					<td>
						{{ $order->status }}
					</td>
					<td>
						{{ $order->purchase->getPurchaseType() }}
					</td>
					<td>
						{{  date('d-m-y H:i', strtotime($order->purchase->created_at)) }}
					</td>
					<td>
						@if ($order->purchase->order != null)
							@foreach ($order->purchase->order->items as $item)
								@if ($item->item)
									{{ $item->item->name }}
								@endif 
								 - x {{ $item->quantity }}
								 <br>
							 	@if ($item->price != null)
									{{ Settings::getCurrencySymbol() }}{{ $item->price * $item->quantity }}
									@if ($item->price_credit != null && Settings::isCreditEnabled())
										/
									@endif
								@endif
								@if ($item->price_credit != null && Settings::isCreditEnabled())
									{{ $item->price_credit * $item->quantity }} Credits
								@endif
								@if (!$loop->last)
									<hr>
								@endif
							@endforeach
						@endif
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>

@endsection
