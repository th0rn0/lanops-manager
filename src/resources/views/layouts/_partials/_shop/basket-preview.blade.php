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
				<td>
					@if ($item->price != null && $item->price != 0)
						{{ Settings::getCurrencySymbol() }}{{ number_format($item->price, 2) }}
						@if ($item->price_credit != null && Settings::isCreditEnabled() && $item->price_credit != 0)
							/
						@endif
					@endif
					@if ($item->price_credit != null && Settings::isCreditEnabled() && $item->price_credit != 0)
						{{ number_format($item->price_credit, 2) }} Credits
					@endif
				</td>
			</tr>
		@endforeach
		<tr>
			<td></td>
			<td class="text-right">
				<strong>Total:</strong>
			</td>
			<td>
				@if ($basket->total != null)
					{{ Settings::getCurrencySymbol() }}{{ number_format($basket->total, 2) }}
					@if ($basket->total_credit != null && Settings::isCreditEnabled() && $item->price_credit != 0)
						/
					@endif
				@endif
				@if ($basket->total_credit != null && Settings::isCreditEnabled() && $item->price_credit != 0)
					{{ number_format($basket->total_credit, 2) }} Credits
				@endif
			</td>
	</tbody>
</table>