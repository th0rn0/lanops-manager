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
	<div class="col-xs-12 col-sm-10">
		@if ($isCreditEnabled)
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-credit-card fa-fw"></i> Credit System
				</div>
				<div class="panel-body">
					{{ Form::open(array('url'=>'/admin/credit/add')) }}
						<div class="form-group">
							{{ Form::label('event_name','Event Name',array('id'=>'','class'=>'')) }}
							{{ Form::text('event_name', '',array('id'=>'event_name','class'=>'form-control')) }}
						</div>
					{{ Form::close() }}
				</div>  
			</div>
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
									<td>{{ $creditLog->user->steamname }}</td>
									<td>{{ $creditLog->amount }}</td>
									<td></td>
									<td>{{ $creditLog->reason }}</td>
									<td>
										@if ($creditLog->admin_id == null)
											Automated
										@else
											{{ $creditLog->admin->steamname }}
										@endif
									</td>
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
	<div class="col-xs-12 col-sm-2">
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
</div>

@endsection