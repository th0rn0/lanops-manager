@extends ('layouts.default')

@section ('page_title', config('app.name') . ' - About Us')

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h1>About Us</h1> 
	</div>
	<div class="page-header">
		<h3>Who We Are</h3> 
	</div>
	@include ('layouts._partials._about.long')
	<div class="page-header">
		<h3>Our Aim</h3> 
	</div>
	@include ('layouts._partials._about.aim')
	<div class="page-header">
		<h3>The Who's Who</h3> 
	</div>
	@include ('layouts._partials._about.who')
</div>

@endsection