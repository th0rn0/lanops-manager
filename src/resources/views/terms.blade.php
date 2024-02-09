@extends ('layouts.default')

@section ('page_title', config('app.name') . ' - Terms & Conditions')

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h1>Terms & Conditions</h1> 
	</div>
	<div class="page-header">
		<h3>Registration Terms & Conditions</h3> 
	</div>
	@include ('layouts._partials._terms.registration')
	<div class="page-header">
		<h3>Purchase Terms & Conditions</h3> 
	</div>
	@include ('layouts._partials._terms.purchase')
</div>

@endsection