@extends ('layouts.admin-default')

@section ('page_title', 'Purchases')

@section ('content')
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Purchases</h1>
		<ol class="breadcrumb">
			<li class="active">
				Purchases
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-credit-card fa-fw"></i> Purchases
			</div>
			<div class="panel-body">
				<table width="100%" class="table table-striped table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th>User</th>
							<th>Status</th>
							<th>Type</th>
							<th>Paypal Email</th>
							<th>Transaction ID</th>
							<th>Created At</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@foreach ($purchases->reverse() as $purchase)
							<tr class="table-row odd gradeX">
								<td>{{ $purchase->id }}</td>
								<td>{{ $purchase->user->steamname }}</td>
								<td>{{ $purchase->status }}</td>
								<td>{{ $purchase->type }}</td>
								<td>{{ $purchase->paypal_email }}</td>
								<td>{{ $purchase->transaction_id }}</td>
								<td>{{ $purchase->created_at }}</td>
								<td>
									<a href="/admin/purchases/{{ $purchase->id }}">
										<button class="btn btn-block btn-success btn-sm">
											View
										</button>
									</a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>  
		</div>
	</div>
</div>

@endsection