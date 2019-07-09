@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' Shop | ' . $item->name)

@section ('content')
			
<div class="container">
	<div class="page-header">
		<h1>
			Shop - {{ $item->name }}
		</h1>
	</div>
	@include ('layouts._partials._shop.navigation')
	<div class="row">
		<div class="col-xs-12 col-sm-4">
			<img class="img img-thumbnail img-responsive" src="{{ $item->getDefaultImageUrl() }}">
		</div>
		<div class="col-xs-12 col-sm-8">
			<h4>
				{{ $item->name }} - <small>@if($item->stock > 0) In Stock: {{ $item->stock }} @else Out of Stock @endif</small>
			</h4>
			<p>{{ $item->description }}</p>
			<h5>
				@if ($item->price != null)
					£{{ $item->price }}
					@if ($item->price_credit != null && Settings::isCreditEnabled())
						/
					@endif
				@endif
				@if ($item->price_credit != null && Settings::isCreditEnabled())
					{{ $item->price_credit }} Credits
				@endif
			</h5>
			@if ($item->hasStockByItemId($item->id))
				{{ Form::open(array('url'=>'/shop/basket/')) }}
					<div class="form-group">
						{{ Form::label('quantity','Quantity',array('id'=>'','class'=>'')) }}
						{{ Form::number('quantity', 1, array('id'=>'quantity','class'=>'form-control')) }}
					</div>
					{{ Form::hidden('shop_item_id', $item->id) }}
					<button type="submit" class="btn btn-success">Add to Cart</button>
				{{ Form::close() }}
			@else
				<div class="alert alert-info">
					Not in Stock
				</div>
			@endif	
		</div>
	</div>
</div>

@endsection
