@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - ' . __('legalnotice.legalnoticeandprivacy'))

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h2>@lang('legalnotice.legalnoticetitle')</h3> 
	</div>
	{!! Settings::getLegalNotice() !!}
	<div class="page-header">
		<h2>@lang('legalnotice.privacy')</h3> 
	</div>
	{!! Settings::getPrivacyPolicy() !!}
	
</div>

@endsection