@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - Terms & Conditions')

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h1>@lang('terms.termsandconditions')</h1> 
	</div>
	<div class="page-header">
		<h3>@lang('terms.registrationtermsandconditions')</h3> 
	</div>
	{!! Settings::getRegistrationTermsAndConditions() !!}
	<div class="page-header">
		<h3>@lang('terms.purchaseandconditions')</h3> 
	</div>
	{!! Settings::getPurchaseTermsAndConditions() !!}
	
</div>

@endsection