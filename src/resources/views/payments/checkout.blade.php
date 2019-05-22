@extends ('layouts.default')

@section ('page_title', 'Checkout')

@section ('content')

<div class="container">
	<div class="page-header">
		<h1>
			Checkout
		</h1> 
	</div>
	<div class="row">
		<div class="col-xs-12 col-md-12">
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
										<td class="text-right">
											x {{ $item->quantity }}
										</td>
										<td>
											£{{ $item->price }}
										</td>
									</tr>
								@endforeach
								<tr>
									<td></td>
									<td class="text-right">
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
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Payment</h3>
				</div>
				<div class="panel-body">
					{{ Form::open(array('url'=>'/payment/review')) }}
						{{ Form::hidden('gateway', 'paypal') }}
						<button type="submit" class="btn btn-default btn-block">Pay by Paypal</button>
					{{ Form::close() }}
					<br>
					{{ Form::open(array('url'=>'/payment/review')) }}
						{{ Form::hidden('gateway', 'stripe') }}
						<button type="submit" class="btn btn-default btn-block">Pay by Card</button>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
</div>

@endsection