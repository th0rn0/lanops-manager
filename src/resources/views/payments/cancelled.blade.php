@extends ('layouts.default')

@section ('page_title', __('payments.payment_cancelled'))

@section ('content')

<div class="container pt-1">
	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h1>
		@lang('payments.payment_cancelled')
		</h1>
	</div>
	<div class="row">
		<div class="col-12">
			<p>@lang('payments.payment_cancelled_info1')</p>
			<p>@lang('payments.payment_cancelled_info2')</p>
		</div>
	</div>
</div>

@endsection
