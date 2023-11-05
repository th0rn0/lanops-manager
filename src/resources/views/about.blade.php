@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - About Us')

@section ('content')

<div class="container pt-1">

	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h1>@lang('about.aboutus')</h1>
	</div>
	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h3>@lang('about.whoeweare')</h3>
	</div>
	{!! Settings::getAboutMain() !!}
	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h3>@lang('about.ouraim')</h3>
	</div>
	{!! Settings::getAboutOurAim() !!}
	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h3>@lang('about.whoswho')</h3>
	</div>
	{!! Settings::getAboutWho() !!}

</div>

@endsection