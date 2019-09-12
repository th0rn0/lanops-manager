@extends ('layouts.default')

@section ('page_title', 'Updating your Profile')

@section ('content')

	<div class="container">

		<div class="page-header">
			<h1>
				My Account
			</h1>
		</div>
		<div class="row">
			<!-- ACCOUNT DETAILS -->
			<div class="col-xs-12  col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Account Details</h3>
					</div>
					<div class="panel-body">
						{{ Form::open(array('url'=>'/account/' . $user->id )) }}
							<div class="row" style="display: flex; align-items: center;">
								<div class="col-md-2 col-sm-12">
									@if ($user->avatar != NULL)
										<img src="{{$user->avatar}}" class="img-responsive img-thumbnail"/>
									@endif
								</div> 
								<div class="col-md-10 col-sm-12">
									<div class="form-group">
										{{ Form::label('User Name','Name',array('id'=>'','class'=>'')) }}
										{{ Form::text('name', $user->username ,array('id'=>'name','class'=>'form-control', 'disabled' => 'disabled')) }}
									</div> 
									<div class="form-group">
										{{ Form::label('steamname','Steam Name',array('id'=>'','class'=>'')) }}
										{{ Form::text('steamname', $user->steamname ,array('id'=>'steamname','class'=>'form-control', 'disabled'=>'true')) }}
									</div>
								</div>
							</div>
						{{ Form::close() }}
					</div>
				</div>
				@if ($creditLogs)
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
									@foreach ($creditLogs->reverse() as $creditLog)
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
															{{ Settings::getCurrencySymbol() }}{{ $item->price * $item->quantity }}
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
							{{ $creditLogs->links() }}
						</div>
					</div>
				@endif
			</div>

			<!-- TICKETS -->
			<div class="col-sm-12 col-xs-12 col-md-6 col-lg-7">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Tickets</h3>
					</div>
					<div class="panel-body">
						@if (count($eventParticipants))
							@foreach ($eventParticipants as $participant)
								@include ('layouts._partials._tickets.index')
							@endforeach
						@else
							You currently have no tickets.
						@endif
					</div>
				</div>
			</div>

			<!-- PURCHASES -->
			<div class="col-sm-12 col-xs-12 col-md-6 col-lg-5">
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
									</tr>
								</thead>
								<tbody>
									@foreach ($purchases as $purchase)
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
															{{ Settings::getCurrencySymbol() }}{{ $item->price * $item->quantity }}
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
										</tr>
									@endforeach
								</tbody>
							</table>
							{{ $purchases->links() }}
						@else
							You have no purchases
						@endif
					</div>
				</div>
			</div>

			<!-- DANGER ZONE -->
			<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Danger Zone</h3>
					</div>
					<div class="panel-body">
						<button type="button" name="" value="" class="btn btn-danger hidden">Remove Steam Account</button>
						<button type="button" name="" value="" class="btn btn-danger hidden">Add Secondary Steam Account</button>
						<button type="button" name="" value="" class="btn btn-danger hidden">Add Twitch Account</button>
						<button type="button" name="" value="" class="btn btn-danger hidden">Remove Twitch Account</button>
						<button class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal">Delete Account</button>
					</div>
				</div>
			</div>
		</div>
		@include ('layouts._partials._gifts.modal')
	</div>

	<!-- Confirm Delete Modal -->
	<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="confirmDeleteModalLabel">Are you sure you want to Delete your Account?</h4>
				</div>
				{{ Form::open(array('url'=>'/account/delete/', 'id'=>'confirmDeleteFormModal')) }}
					<div class="modal-body">
						<div class="form-group">
							<p>Once it's gone... It's gone, puff... Aaaannnd it's gone!</p>
							<p><strong>All</strong> user records will be deleted.</p>
							<p><strong>All</strong> tickets for upcoming events will be forfeit.</p>
							<p><strong>All</strong> tickets gifted to you for upcoming will be forfeit.</p> 
							<p>Tickets gifted to you will <strong>not</strong> be transferred back to the gifter</p>
							<p>Refunds will <strong>not</strong> be given for any forfeit tickets.</p>
							<p><strong>By clicking Accept you are agreeing to these terms.</strong></p>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-success">Accept</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
					</div>
				{{ Form::close() }}
			</div>
		</div>
	</div>
	
@endsection