<div class="card mb-3">
	<div class="card-header">
		<h3 class="card-title">
			<a href="/shop/{{ $item->category->slug }}/{{ $item->slug }}">
				{{ $item->name }} @if (@$admin) <small> - Preview</small> @endif @if ($item->featured) <small> - Featured</small> @endif
			</a>
		</h3>
	</div>
	<div class="card-body">
		@if (@$admin)
			<a href="/admin/shop/{{ $item->category->slug }}/{{ $item->slug }}">
				<center>
					<img alt="{{ $item->name }}" style="max-height:230px !important;" class="img rounded img-fluid" src="{{ $item->getDefaultImageUrl() }}">
				</center>
			</a>
		@else
			<a href="/shop/{{ $item->category->slug }}/{{ $item->slug }}">
				<center>
					<img alt="{{ $item->name }}" style="max-height:230px !important;" class="img rounded img-fluid" src="{{ $item->getDefaultImageUrl() }}">
				</center>
			</a>
		@endif
	</div>
	<div class="card-footer">
		<p>
			@if ($item->price != null && $item->price != 0)
				{{ Settings::getCurrencySymbol() }}{{ $item->price }}
				@if ($item->price_credit != null && Settings::isCreditEnabled() && $item->price_credit != 0)
					/
				@endif
			@endif
			@if ($item->price_credit != null && Settings::isCreditEnabled() && $item->price_credit != 0)
				{{ $item->price_credit }} Credits
			@endif
		</p>
		<p>
			@if ($item->stock > 0)
				In Stock
			@else
				Out of Stock
			@endif
		</p>
	</div>
</div>