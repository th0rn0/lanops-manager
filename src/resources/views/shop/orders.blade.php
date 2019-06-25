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
</div>

@endsection
