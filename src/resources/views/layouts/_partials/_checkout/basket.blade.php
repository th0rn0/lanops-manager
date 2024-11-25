<div class="table-responsive">
	<table class="table table-striped">
		<tbody>
			@foreach ($basket as $item)
				<tr>
					<td>
						<strong>{{ $item->event->display_name }}: {{ $item->name }}</strong>
					</td>
					<td class="text-right">
						x {{ $item->quantity }}
					</td>
					<td>
						@if ($item->price != null && $item->price != 0)
							{{ config('app.currency_symbol') }}{{ number_format($item->price, 2) }}
						@endif
					</td>
				</tr>
			@endforeach
			@if ($basket->referral_discount_total > 0)
				<tr>
					<td></td>
					<td class="text-right">
						<strong>Referral Discounts:</strong>
					</td>
					<td>
						-{{ config('app.currency_symbol') }}{{ number_format($basket->referral_discount_total, 2) }}
					</td>
				</tr>
			@endif
			<tr>
				<td></td>
				<td class="text-right">
					<strong>Total:</strong>
				</td>
				<td>
					@if ($basket->total != null)
						{{ config('app.currency_symbol') }}{{ number_format($basket->total, 2) }}
					@endif
				</td>
			</tr>
		</tbody>
	</table>
</div>