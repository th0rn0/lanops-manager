@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' Shop | ' . $item->name)

@section ('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<link  href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet"> <!-- 3 KB -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script> <!-- 16 KB -->

<div class="container">
	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h1>
			Shop - {{ $item->name }}
		</h1>
	</div>
	@include ('layouts._partials._shop.navigation')
	<div class="row">
		<div class="col-12 col-sm-4">
			<div class="fotorama" data-nav="thumbs" data-allowfullscreen="full">
				@if ($item->getDefaultImageUrl())
					<picture>
						<source srcset="{{ $item->getDefaultImageUrl() }}.webp" type="image/webp">
						<source srcset="{{ $item->getDefaultImageUrl() }}" type="image/jpeg">
						<img alt="{{ $item->name }}" src="{{ $item->getDefaultImageUrl() }}">
					</picture>
				@endif
				@foreach ($item->images as $image)
					@if (!$image->default)
						<picture>
							<source srcset="{{ $image->path }}.webp" type="image/webp">
							<source srcset="{{ $image->path }}" type="image/jpeg">
							<img alt="{{ $item->name }}" src="{{ $image->path }}">
						</picture>
					@endif
				@endforeach
			</div>
			<br><br>
		</div>
		<div class="col-12 col-sm-8">
			<h4>
				{{ $item->name }} - <small>@if($item->stock > 0) In Stock: {{ $item->stock }} @else Out of Stock @endif</small>
			</h4>
			<p>{!! $item->description !!}</p>
			<h5>
				@if ($item->price != null && $item->price != 0)
					{{ Settings::getCurrencySymbol() }}{{ $item->price }}
					@if ($item->price_credit != null && Settings::isCreditEnabled() && $item->price_credit != 0)
						/
					@endif
				@endif
				@if ($item->price_credit != null && Settings::isCreditEnabled() && $item->price_credit != 0)
					{{ $item->price_credit }} Credits
				@endif
			</h5>
			@if (Settings::getShopStatus() == 'OPEN')
				@if ($item->hasStockByItemId($item->id))
					{{ Form::open(array('url'=>'/shop/basket/')) }}
						<div class="mb-3">
							{{ Form::label('quantity','Quantity',array('id'=>'','class'=>'')) }}
							{{ Form::number('quantity', 1, array('id'=>'quantity','class'=>'form-control')) }}
						</div>
						{{ Form::hidden('shop_item_id', $item->id) }}
						<button type="submit" class="btn btn-primary btn-block">Add to Cart</button>
					{{ Form::close() }}
				@else
					<div class="alert alert-info">
						Not in Stock
					</div>
				@endif
			@else
				<div class="alert alert-info">
					Shop is Closed
				</div>
			@endif
		</div>
	</div>
</div>

@endsection
