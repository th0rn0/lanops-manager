@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - About Us')

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h1>@lang('about.aboutus')</h1> 
	</div>
	<div class="page-header">
		<h3>@lang('about.whoeweare')</h3> 
	</div>
	{!! Settings::getAboutMain() !!}
	<div class="page-header">
		<h3>@lang('about.ouraim')</h3> 
	</div>
	{!! Settings::getAboutOurAim() !!}
	<div class="page-header">
		<h3>@lang('about.whoswho')</h3> 
	</div>
	{!! Settings::getAboutWho() !!}

</div>

@endsection