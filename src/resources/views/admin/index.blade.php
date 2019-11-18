@extends ('layouts.admin-default')

@section ('page_title', 'Admin')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">Dashboard</h3>
		<ol class="breadcrumb">
			<li class="active">
				<i class="fa fa-dashboard"></i> Dashboard
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-comments fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge">{{ $comments->count() }}</div>
						<div>New Comments!</div>
					</div>
				</div>
			</div>
			<a href="/admin/news">
				<div class="panel-footer">
					<span class="pull-left">View Details</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-green">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-tasks fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
							<div class="huge">{{ $votes->count() }}</div>
							<div>New Votes!</div>
						</div>
					</div>
			</div>
			<a href="/admin/polls">
				<div class="panel-footer">
					<span class="pull-left">View Details</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-yellow">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-shopping-cart fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge">{{ $orders->count() }}</div>
						<div>New Orders!</div>
					</div>
				</div>
			</div>
			<a href="/admin/purchases">
				<div class="panel-footer">
					<span class="pull-left">View Details</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-red">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-support fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge">{{ $participants->count() }}</div>
						<div>New Participants!</div>
					</div>
				</div>
			</div>
			<a href="/admin/events">
				<div class="panel-footer">
					<span class="pull-left">View Details</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6 col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Ticket Sales
			</div>
			<div class="panel-body">
				<div id="ticket-breakdown"></div>
			</div>
		</div>	
	</div>

	<div class="col-md-6 col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Orders
			</div>
			<div class="panel-body">
				<div id="orders-breakdown"></div>
			</div>
		</div>	
	</div>

	<div class="col-md-6 col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Users
			</div>
			<div class="panel-body">
				<ul class="list-group">
					<li class="list-group-item list-group-item-info">Last Logged In: <span class="pull-right">{{ $userLastLoggedIn->username }} on {{ $userLastLoggedIn->last_login }}</span></li>
					<li class="list-group-item list-group-item-info">No. of Users: <span class="pull-right">{{ $userCount }}</span></li>
					<li class="list-group-item list-group-item-info">No. of Active Login Methods: <span class="pull-right">{{ count($activeLoginMethods) }}</span></li>
					@foreach ($supportedLoginMethods as $method)
						<li class="list-group-item @if (in_array($method, $activeLoginMethods)) list-group-item-success @else list-group-item-danger @endif">No. of {{ ucwords(str_replace('-', ' ', (str_replace('_', ' ' , $method)))) }} Accounts: <span class="pull-right">{{ $userLoginMethodCount[$method] }}</span></li>
					@endforeach
				</ul>
			</div>
		</div>	
	</div>

	<div class="col-md-6 col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Events
			</div>
			<div class="panel-body">
				<ul class="list-group">
					<li class="list-group-item list-group-item-info">Next Event: <span class="pull-right">{{ $nextEvent }}</span></li>
					<li class="list-group-item list-group-item-info">No of Events: <span class="pull-right">{{ $events->count() }}</span></li>
					<li class="list-group-item list-group-item-info">No of Attendees: <span class="pull-right">{{ $participantCount }}</span></li>
					<li class="list-group-item list-group-item-info">No of Tournaments: <span class="pull-right">{{ $tournamentCount }}</span></li>
					<li class="list-group-item list-group-item-info">No of Tournament Participants: <span class="pull-right">{{ $tournamentParticipantCount }}</span></li>
				</ul>
			</div>
		</div>	
	</div>

	<div class="col-md-6 col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Active Polls
			</div>
			<div class="panel-body">
				<ul class="list-group">
					@if ($activePolls->count() > 0)
						@foreach ($activePolls as $poll)
							<li class="list-group-item list-group-item-info">{{ $poll->name }}: <span class="pull-right">{{ $poll->getTotalVotes() }}</span></li>
						@endforeach
					@else
						<li class="list-group-item list-group-item-info">Nothing to see here...</li>
					@endif
				</ul>
			</div>
		</div>	
	</div>

	<div class="col-md-6 col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Features
			</div>
			<div class="panel-body">
				<ul class="list-group">
					<li class="list-group-item @if($shopEnabled) list-group-item-success @else list-group-item-danger @endif">Shop: <span class="pull-right">@if($shopEnabled) Enabled @else Disabled @endif </span></li>
					<li class="list-group-item @if($creditEnabled) list-group-item-success @else list-group-item-danger @endif">Credit: <span class="pull-right"> @if($creditEnabled) Enabled @else Disabled @endif </span></li>
					@foreach ($supportedLoginMethods as $method)
						<li class="list-group-item @if (in_array($method, $activeLoginMethods)) list-group-item-success @else list-group-item-danger @endif">{{ ucwords(str_replace('-', ' ', (str_replace('_', ' ' , $method)))) }} Login: <span class="pull-right"> @if (in_array($method, $activeLoginMethods)) Enabled @else Disabled @endif </span></li>
					@endforeach
					<li class="list-group-item @if ($facebookCallback != null) list-group-item-success @else list-group-item-danger @endif">Facebook News Link: <span class="pull-right"> @if ($facebookCallback != null) Active @else Inactive @endif </span></li>
					@foreach ($supportedPaymentGateways as $gateway)
						<li class="list-group-item @if (in_array($gateway, $activePaymentGateways)) list-group-item-success @else list-group-item-danger @endif">{{ ucwords(str_replace('-', ' ', (str_replace('_', ' ' , $gateway)))) }} Payment Gateway: <span class="pull-right"> @if (in_array($gateway, $activePaymentGateways)) Enabled @else Disabled @endif </span></li>
					@endforeach
				</ul>
			</div>
		</div>	
	</div>
</div>

<script>
	Morris.Bar({
		element: 'ticket-breakdown',
		data: [
			@foreach ($tickets as $ticket)
				{ y: '{{ $ticket->name }}', a: {{ $ticket->price * $ticket->participants()->count() }} },
			@endforeach
		],
		xkey: 'y',
		ykeys: ['a'],
		labels: ['Pounds']
	});
	Morris.Bar({
		element: 'orders-breakdown',
		data: [
			@foreach ($tickets as $ticket)
				{ y: '{{ $ticket->name }}', a: {{ $ticket->price * $ticket->participants()->count() }} },
			@endforeach
		],
		xkey: 'y',
		ykeys: ['a'],
		labels: ['Pounds']
	});
</script>

@endsection
