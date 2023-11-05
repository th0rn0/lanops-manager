@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - Terms & Conditions')

@section ('content')

<div class="container pt-1">

	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h1>@lang('terms.termsandconditions')</h1>
	</div>
	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h3>@lang('terms.registrationtermsandconditions')</h3>
	</div>
	{!! Settings::getRegistrationTermsAndConditions() !!}
	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h3>@lang('terms.purchaseandconditions')</h3>
	</div>
	{!! Settings::getPurchaseTermsAndConditions() !!}

</div>

@endsection