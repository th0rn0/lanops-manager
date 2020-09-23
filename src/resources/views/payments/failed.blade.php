@extends ('layouts.default')

@section ('page_title', __('payments.payment_failed'))

@section ('content')

<div class="container">
	<div class="page-header">
		<h1>
		@lang('payments.payment_failed')
		</h1> 
	</div>  
	<div class="row">
		<div class="col-xs-12">
			<p>@lang('payments.payment_failed_text1')</p>
			<p>@lang('payments.payment_failed_text2')</p>
			<p>@lang('payments.payment_failed_text3')</p>
		</div>
	</div>
</div>

@endsection
