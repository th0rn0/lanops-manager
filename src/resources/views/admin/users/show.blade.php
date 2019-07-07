@extends ('layouts.admin-default')

@section ('page_title', 'Users - View '. $user->username)

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">{{ $user->username }} <small>{{ $user->steamname }}</small></h1>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/users/">Users</a>
			</li>
			<li class="active">
				{{ $user->username }}
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-sm-12 col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> User
			</div>
			<div class="panel-body">
				<div class="media">
  					<div class="media-left">
						<img class="media-object" src="{{ $user->avatar }}">
			  		</div>
  					<div class="media-body">
						<ul class="list-group">
							<li class="list-group-item">Username: {{ $user->username }}</li>
							<li class="list-group-item">Steam Name: {{ $user->steamname }}</li>
							<li class="list-group-item">Name: {{ $user->firstname }} {{ $user->surname }}</li>
							<li class="list-group-item">Admin: @if ($user->admin) Yes @else No @endif</li>
						</ul>
  					</div>
  				</div>
			</div>  
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Purchases</h3>
			</div>
			<div class="panel-body">
				@if (count($user->purchases))
					<table class="table table-striped">
						<thead>
							<tr>
								<th>
									ID
								</th>
								<th>
									Method
								</th>
								<th>
									Time
								</th>
								<th>
									Basket
								</th>
								<th>
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($user->purchases as $purchase)
								<tr>
									<td>
										{{ $purchase->id }}
									</td>
									<td>
										{{ $purchase->getPurchaseType() }}
									</td>
									<td>
										{{  date('d-m-y H:i', strtotime($purchase->created_at)) }}
									</td>
									<td>
										@if (!$purchase->participants->isEmpty())
											@foreach ($purchase->participants as $participant)
												{{ $participant->event->display_name }} - {{ $participant->ticket->name }}
												@if (!$loop->last)
													<hr>
												@endif
											@endforeach
										@elseif ($purchase->order != null)
											@foreach ($purchase->order->items as $item)
												@if ($item->item)
													{{ $item->item->name }}
												@endif 
												 - x {{ $item->quantity }}
												 <br>
											 	@if ($item->price != null)
													£{{ $item->price * $item->quantity }}
													@if ($item->price_credit != null && Settings::isCreditEnabled())
														/
													@endif
												@endif
												@if ($item->price_credit != null && Settings::isCreditEnabled())
													{{ $item->price_credit * $item->quantity }} Credits
												@endif
												@if (!$loop->last)
													<hr>
												@endif
											@endforeach
										@endif
									</td>
									<td>
										<a href="/admin/purchases/{{ $purchase->id }}">
											<button class="btn btn-sm btn-block btn-success">View</button>
										</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				@else
					You have no purchases
				@endif
			</div>
		</div>
	</div>
	<div class="col-sm-12 col-lg-6">
		@if (Settings::isCreditEnabled())
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-users fa-fw"></i> Add Credit
				</div>
				<div class="panel-body">
					{{ Form::open(array('url'=>'/admin/credit/edit')) }}
						<div class="form-group">
							{{ Form::hidden('user_id', $user->id) }}
							{{ Form::label('amount','Amount',array('id'=>'','class'=>'')) }}
							{{ Form::number('amount', '',array('id'=>'amount','class'=>'form-control')) }}
						</div>
						<button type="submit" class="btn btn-block btn-success">Submit</button>
					{{ Form::close() }}
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Credit - {{ $user->credit_total }}</h3>
				</div>
				<div class="panel-body">
					<table width="100%" class="table table-striped table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th>Action</th>
								<th>Amount</th>
								<th>Item</th>
								<th>Reason</th>
								<th>Timestamp</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($user->creditLogs->reverse() as $creditLog)
							<tr class="table-row" class="odd gradeX">
								<td>{{ $creditLog->action }}</td>
								<td>{{ $creditLog->amount }}</td>
								<td>
									@if (strtolower($creditLog->action) == 'buy')
										@if (!$creditLog->purchase->participants->isEmpty())
											@foreach ($creditLog->purchase->participants as $participant)
												{{ $participant->event->display_name }} - {{ $participant->ticket->name }}
												@if (!$loop->last)
													<hr>
												@endif
											@endforeach
										@elseif ($creditLog->purchase->order != null)
											@foreach ($creditLog->purchase->order->items as $item)
												@if ($item->item)
													{{ $item->item->name }}
												@endif 
												 - x {{ $item->quantity }}
												 <br>
											 	@if ($item->price != null)
													£{{ $item->price * $item->quantity }}
													@if ($item->price_credit != null && Settings::isCreditEnabled())
														/
													@endif
												@endif
												@if ($item->price_credit != null && Settings::isCreditEnabled())
													{{ $item->price_credit * $item->quantity }} Credits
												@endif
												@if (!$loop->last)
													<hr>
												@endif
											@endforeach
										@endif
									@endif
								</td>
								<td>{{ $creditLog->reason }}</td>
								<td>
									{{ $creditLog->updated_at }}
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		@endif
	</div>
</div>
 
@endsection