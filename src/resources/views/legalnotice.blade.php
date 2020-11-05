@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - ' . __('legalnotice.legalnoticeandprivacy'))

@section ('content')

<div class="container">

	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h2>@lang('legalnotice.legalnoticetitle')</h3>
	</div>
	{!! Settings::getLegalNotice() !!}
	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h2>@lang('legalnotice.privacy')</h3>
	</div>
	{!! Settings::getPrivacyPolicy() !!}

</div>

@endsection