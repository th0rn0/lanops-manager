@extends ('layouts.default')

@section ('page_title', config('app.name') . ' - Information')

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h1>General Information</h1> 
	</div>
	<div class="page-header">
		<h3>What is a LAN Party</h3> 
	</div>
	@include ('layouts._partials._info.what-is-a-lan-party')
	<div class="page-header">
		<h3>What to Bring</h3> 
	</div>
	@include ('layouts._partials._info.what-to-bring')
	<div class="page-header">
		<h3>Enquires</h3> 
	</div>
	@include ('layouts._partials._info.enquires')
</div>

@endsection