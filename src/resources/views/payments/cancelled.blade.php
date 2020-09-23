@extends ('layouts.default')

@section ('page_title', __('payments.payment_cancelled'))

@section ('content')

<div class="container">
	<div class="page-header">
		<h1>
		@lang('payments.payment_cancelled')
		</h1> 
	</div>  
	<div class="row">
		<div class="col-xs-12">
			<p>@lang('payments.payment_cancelled_info1')</p>
			<p>@lang('payments.payment_cancelled_info2')</p>
		</div>
	</div>
</div>

@endsection
