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
</div>

@endsection
