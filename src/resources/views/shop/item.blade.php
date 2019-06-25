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
			<button class="btn btn-success">Add to Cart</button>
		</div>
	</div>
</div>

@endsection
