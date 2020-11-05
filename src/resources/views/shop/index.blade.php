@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' Shop')

@section ('content')

<div class="container">
	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h1>
			Shop
		</h1>
	</div>

	@include ('layouts._partials._shop.navigation')
	<div class="row">
		@if (Settings::getShopWelcomeMessage() != '' || Settings::getShopWelcomeMessage() != null)
			<div class="col-12">
				<h4>{{ Settings::getShopWelcomeMessage() }}</h4>
				<br>
			</div>
		@endif
		@foreach ($featuredItems as $item)
			<div class="col-12 col-sm-6 col-md-3">
				@include ('layouts._partials._shop.item-preview')
			</div>
		@endforeach
	</div>
</div>

@endsection
