@extends ('layouts.admin.default')

@section ('page_title', 'Tickets - ' . $event->display_name)

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Tickets - {{ $ticket->name }}</h1>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/events/">Events</a>
			</li>
			<li>
				<a href="/admin/events/{{ $event->slug }}">{{ $event->display_name }}</a>
			</li>
			<li>
				<a href="/admin/events/{{ $event->slug }}/tickets">Tickets</a>
			</li>
			<li class="active">
				{{ $ticket->name }}
			</li>
		</ol>
	</div>
</div>

@include ('layouts._partials._admin._event.dashMini')

<div class="row">
	<div class="col-lg-6">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-pencil fa-fw"></i> Edit
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tickets/' . $ticket->id)) }}
					@if (isset($ticket) && !$ticket->participants->isEmpty()) @php $price_lock = true; @endphp @endif

					@include ('layouts._partials._admin._event._tickets.form')
				{{ Form::close() }}
			</div>
		</div>

	</div>
	<div class="col-lg-6">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-bar-chart-o fa-fw"></i> Statistics
			</div>
			<div class="panel-body">
				<div class="list-group">
					Chart me
					<h4>Money Made</h4>
					<p>£{{ $ticket->participants()->count() * $ticket->price }}</p>
					<h4>Purchases</h4>
					<p>{{ $ticket->participants()->count() }}</p>
					@if ($ticket->quantity > 0)
						<h4>Quantity</h4> 
						<p>{{ $ticket->quantity }}</p>
					@endif
				</div>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-user fa-fw"></i> Purchases
			</div>
			<div class="panel-body">
				<div class="list-group">
					@foreach ($event->eventParticipants as $participant)
						@if ($participant->ticket_id == $ticket->id)
							<a href="/admin/events/{{ $event->slug }}/participants/{{ $participant->id }}" class="list-group-item">
								<i class="fa fa-comment fa-fw"></i> {{ $participant->user->username_nice}} - {{ $participant->user->steamname}}
								<span class="pull-right text-muted small">
									<em>
										{{ date('d-m-Y H:i', strtotime($participant->created_at)) }}
									</em>
								</span>
							</a>
						@endif
					@endforeach
				</div>
			</div>
		</div>

	</div>
</div>

@endsection