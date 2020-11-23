@extends ('layouts.default')

@section ('page_title', __('payments.delivery_details'))

@section ('content')

<div class="container">
	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h1>
			@lang('payments.delivery_details')
		</h1>
	</div>
	<div class="row">
		<div class="col-12 col-md-8">

			{{ Form::open(array('url'=>'/payment/post')) }}
				<div class="form-group">
					<select id="delivery_type" name="delivery_type" class="form-control" onchange="showOptions()">
						<option>@lang('payments.delivery_select_type')</option>
						<option value="event">@lang('payments.delivery_to_event')</option>
						<option value="shipping">@lang('payments.delivery_to_me')</option>
					</select>
				</div>
				<div id="event" class="d-none">
					<h4>@lang('payments.delivery_will_deliver_to_event')</h4>
				</div>
				<div id="shipping" class="d-none">
					<div class="row">
						<div class="form-group col-sm-6 col-12">
							{{ Form::label('shipping_first_name', __('payments.firstname'), array('id'=>'','class'=>'')) }}
							{{ Form::text('shipping_first_name', '', array('id'=>'shipping_first_name','class'=>'form-control')) }}
						</div>
						<div class="form-group col-sm-6 col-12">
							{{ Form::label('shipping_last_name', __('payments.lastname'), array('id'=>'','class'=>'')) }}
							{{ Form::text('shipping_last_name', '', array('id'=>'shipping_last_name','class'=>'form-control')) }}
						</div>
					</div>
					<div class="form-group">
						{{ Form::label('shipping_address_1', __('payments.shipping_address_1'), array('id'=>'','class'=>'')) }}
						{{ Form::text('shipping_address_1', '', array('id'=>'shipping_address_1','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('shipping_address_2', __('payments.shipping_address_2'), array('id'=>'','class'=>'')) }}
						{{ Form::text('shipping_address_2', '', array('id'=>'shipping_address_2','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('shipping_country', __('payments.shipping_country'), array('id'=>'','class'=>'')) }}
						{{ Form::text('shipping_country', '', array('id'=>'shipping_country','class'=>'form-control')) }}
					</div>
					<div class="row">
						<div class="form-group col-sm-6 col-12">
							{{ Form::label('shipping_postcode', __('payments.shipping_postcode'), array('id'=>'','class'=>'')) }}
							{{ Form::text('shipping_postcode', '', array('id'=>'shipping_postcode','class'=>'form-control')) }}
						</div>
						<div class="form-group col-sm-6 col-12">
							{{ Form::label('shipping_state', __('payments.shipping_state'), array('id'=>'','class'=>'')) }}
							{{ Form::text('shipping_state', '', array('id'=>'shipping_state','class'=>'form-control')) }}
						</div>
					</div>
					<p><small>@lang('payments.delivery_required_fields)</small></p>
				</div>
				{{ Form::hidden('gateway', $paymentGateway) }}
				<button id="continue" class="btn btn-primary btn-block d-none">@lang('payments.delivery_continue)</button>
			{{ Form::close() }}
		</div>
		<div class="col-12 col-md-4">
			<div class="card mb-3">
				<div class="card-header ">
					<h3 class="card-title">@lang('payments.order_details)</h3>
				</div>
				<div class="card-body">
					@include ('layouts._partials._shop.basket-preview')
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	function showOptions() {
	    value = document.getElementById("delivery_type").value;
    	jQuery("#shipping").addClass("d-none");
    	jQuery("#event").addClass("d-none");
    	jQuery("#continue").addClass("d-none");
	    if (value == 'event') {
			jQuery("#event").removeClass("d-none");
			jQuery("#continue").removeClass("d-none");
	    } else if (value == 'shipping') {
	    	jQuery("#shipping").removeClass("d-none");
			jQuery("#continue").removeClass("d-none");
	    }
	}
</script>
@endsection