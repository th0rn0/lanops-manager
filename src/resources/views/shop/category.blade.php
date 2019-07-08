@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' Shop | ' . $category->name)

@section ('content')
			
<div class="container">
	<div class="page-header">
		<h1>
			Shop - {{ $category->name }}
		</h1>
	</div>
	@include ('layouts._partials._shop.navigation')
	{{ $categoryItems->links() }}
	<div class="row">
		@foreach ($categoryItems as $item)
			<div class="col-xs-12 col-sm-6 col-md-3">
				@include ('layouts._partials._shop.item-preview')
			</div>
		@endforeach
	</div>
	{{ $categoryItems->links() }}
</div>

@endsection
