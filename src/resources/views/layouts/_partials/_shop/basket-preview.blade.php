<div class="table-responsive">
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
					<td>
						@if ($item->price != null)
							£{{ $item->price }}
							@if ($item->price_credit != null && Settings::isCreditEnabled())
								/
							@endif
						@endif
						@if ($item->price_credit != null && Settings::isCreditEnabled())
							{{ $item->price_credit }} Credits
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
						£{{ $basket->total }}
						@if ($basket->total_credit != null && Settings::isCreditEnabled())
							/
						@endif
					@endif
					@if ($basket->total_credit != null && Settings::isCreditEnabled())
						{{ $basket->total_credit }} Credits
					@endif
				</td>
		</tbody>
	</table>
</div>