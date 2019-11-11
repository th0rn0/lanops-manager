@extends ('layouts.admin-default')

@section ('page_title', 'Credit System')

@section ('content')
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Credit System</h1>
		<ol class="breadcrumb">
			<li class="active">
				Credit System
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	@if (!$isCreditEnabled)
		<div class="col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-info-circle fa-fw"></i> Credit is Currently Disabled...
				</div>
				<div class="panel-body">
					<p>The Credit System is used to award participants with credit they can use to buy things from the shop and events. It can be award for buying tickets, attending events, participanting/winning tournaments or manually assigned.</p>
					@if ($isCreditEnabled)
						{{ Form::open(array('url'=>'/admin/settings/credit/disable')) }}
							<button type="submit" class="btn btn-block btn-danger">Disable</button>
						{{ Form::close() }}
					@else
						{{ Form::open(array('url'=>'/admin/settings/credit/enable')) }}
							<button type="submit" class="btn btn-block btn-success">Enable</button>
						{{ Form::close() }}
					@endif
				</div>
			</div>
		</div>
	@else
		<div class="col-xs-12 col-sm-10">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-credit-card fa-fw"></i> Logs
				</div>
				<div class="panel-body">
					<table width="100%" class="table table-striped table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th>Action</th>
								<th>User</th>
								<th>Amount</th>
								<th>Item</th>
								<th>Reason</th>
								<th>Added By</th>
								<th>Timestamp</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($creditLogs->reverse() as $creditLog)
								<tr class="table-row" class="odd gradeX">
									<td>{{ $creditLog->action }}</td>
									<td>{{ $creditLog->user->username }}</td>
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
										@if ($creditLog->admin_id == null)
											System Automated
										@else
											{{ $creditLog->admin->username }}
										@endif
									</td>
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
		</div>
		<div class="col-xs-12 col-sm-2">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-credit-card fa-fw"></i> Add Credit
				</div>
				<div class="panel-body">
					{{ Form::open(array('url'=>'/admin/credit/edit')) }}
						<div class="form-group">
							{{ Form::label('user_id','User',array('id'=>'','class'=>'')) }}
							{{ Form::text('user_id', '',array('id'=>'user_id','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('amount','Amount',array('id'=>'','class'=>'')) }}
							{{ Form::number('amount', '',array('id'=>'amount','class'=>'form-control')) }}
						</div>
						<button class="btn btn-success btn-block">Submit</button>
					{{ Form::close() }}
				</div>  
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-credit-card fa-fw"></i> Automated Awards
				</div>
				<div class="panel-body">
					{{ Form::open(array('url'=>'/admin/credit/settings/')) }}
						<h4>Tournaments</h4>
						<hr>
						<div class="row">
							<div class="col-xs-12 col-sm-6">
								<div class="form-group">
									{{ Form::label('tournament_participation','Participation') }}
									{{ Form::number('tournament_participation', $creditAwardTournamentParticipation, array('id'=>'tournament_participation','class'=>'form-control'))}}
								</div>
								<div class="form-group">
									{{ Form::label('tournament_second','Second Place') }}
									{{ Form::number('tournament_second', $creditAwardTournamentSecond, array('id'=>'tournament_second','class'=>'form-control'))}}
								</div>
							</div>
							<div class="col-xs-12 col-sm-6">
								<div class="form-group">
									{{ Form::label('tournament_first','First Place') }}
									{{ Form::number('tournament_first', $creditAwardTournamentFirst, array('id'=>'tournament_first','class'=>'form-control'))}}
								</div>
								<div class="form-group">
									{{ Form::label('tournament_third','Third Place') }}
									{{ Form::number('tournament_third', $creditAwardTournamentThird, array('id'=>'tournament_third','class'=>'form-control'))}}
								</div>
							</div>
						</div>
						<h4>Registration</h4>
						<hr>
						<div class="form-group">
							{{ Form::label('registration_event','Event') }}
							{{ Form::number('registration_event', $creditAwardRegistrationEvent, array('id'=>'registration_event','class'=>'form-control'))}}
						</div>
						<div class="form-group">
							{{ Form::label('registration_site','Site') }}
							{{ Form::number('registration_site', $creditAwardRegistrationSite, array('id'=>'registration_site','class'=>'form-control'))}}
						</div>
						<button type="submit" class="btn btn-block btn-success">Submit</button>
					{{ Form::close() }}
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-info-circle fa-fw"></i> Enable/Disable
				</div>
				<div class="panel-body">
					<p>The Credit System is used to award participants with credit they can use to buy things from the shop and events. It can be award for buying tickets, attending events, participanting/winning tournaments or manually assigned.</p>
					@if ($isCreditEnabled)
						{{ Form::open(array('url'=>'/admin/settings/credit/disable')) }}
							<button type="submit" class="btn btn-block btn-danger">Disable</button>
						{{ Form::close() }}
					@else
						{{ Form::open(array('url'=>'/admin/settings/credit/enable')) }}
							<button type="submit" class="btn btn-block btn-success">Enable</button>
						{{ Form::close() }}
					@endif
				</div>
			</div>
		</div>
	@endif
</div>

@endsection