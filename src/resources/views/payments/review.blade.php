@extends ('layouts.default')

@section ('page_title', 'Review Order')

@section ('content')

<div class="container">
	<div class="page-header">
		<h1>
			Confirm Order
		</h1> 
	</div>
	<div class="row">
		<div class="col-xs-12 col-md-8">
			{!! Settings::getPurchaseTermsAndConditions() !!}
			<hr>
			@if (!$nextEventFlag)
				<div class="alert alert-warning">
					<h5>Please be aware you are not purchasing tickets for our next event but instead a future event after that.</h5>
				</div>
			@endif
			<div class="alert alert-warning">
				<h5>By Clicking on Confirm you are agreeing to the Terms and Conditions as set by {!! Settings::getOrgName() !!}</h5>
			</div>
			{{ Form::open(array('url'=>'/payment/post')) }}
				<button class="btn btn-default">Confirm</button>
			{{ Form::close() }}
		</div>
		<div class="col-xs-12 col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Order Details</h3>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped">
							<tbody>
								@foreach ($basketItems as $item)
									<tr>
										<td>
											<strong>{{ $item->name }}</strong>
										</td>
										<td>
											x {{ $item->quantity }}
										</td>
										<td>
											£{{ $item->price }}
										</td>
									</tr>
								@endforeach
								<tr>
									<td></td>
									<td>
										<strong>Total:</strong>
									</td>
									<td>
										£{{ $basketTotal }}
									</td>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection