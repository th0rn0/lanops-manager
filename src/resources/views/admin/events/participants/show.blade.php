@extends ('layouts.admin-default')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Events - {{ $event->display_name }} - Participant - {{ $participant->user->username }}</h1>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/events/">Events</a>
			</li>
			<li>
				<a href="/admin/events/{{ $event->slug }}">{{ $event->display_name }}</a> 
			</li>
			<li>
				<a href="/admin/events/{{ $event->slug }}/participants">Participants</a>
			</li>
			<li class="active">
				{{ $participant->user->username }}
			</li>
		</ol>
	</div>
</div>

@include ('layouts._partials._admin._event.dashMini')

<div class="row">
	<div class="col-lg-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				Edit Participant
			</div>
			<div class="panel-body">
				@if (!empty($_POST))
					Successfully Posted
				@endif
				<div class="dataTable_wrapper">
					<table width="100%" class="table table-striped table-hover" id="seating_table">
						<thead>
							<tr>
								<th>User</th>
								<th>Name</th>
								<th>Seat</th>
								<th>Ticket</th>
								<th>Paypal Email</th>
								<th>Gift</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									{{ $participant->user->username }}
									@if ($participant->user->steamid)
										<br><span class="text-muted"><small>Steam: {{ $participant->user->steamname }}</small></span>
									@endif
								</td>
								<td>{{ $participant->user->firstname }} {{ $participant->user->surname }}</td>
								<td>@if($participant->seat) {{ $participant->seat->seat }} @endif</td>
								<td>
									@if ($participant->ticket)
										{{ $participant->ticket->name }}
									@else
										No Ticket Bought / Free
									@endif
								</td>
								<td>@if ($participant->purchase) {{ $participant->purchase->paypal_email }} @endif</td>
								<td>
									@if ($participant->gift)
										<strong>Yes</strong>
										<small>Assigned by: {{ $participant->getGiftedByUser()->username }}</small>
									@else
										No
									@endif
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

	</div>
	<div class="col-lg-4">

		<div class="panel panel-default">
			<div class="panel-heading">
				More Editing
			</div>
			<div class="panel-body">
				@if ($participant->signed_in)
					<h4>User is signed in at present at the event</h4>
				@endif
				{{ Form::label('','Ticket',array('id'=>'','class'=>'')) }}
				@if ($participant->ticket)
					<p>{{ $participant->ticket->name }}</p>
				@else
					<p>No Ticket Bought / Free</p>
				@endif
				@if ($participant->purchase)
					{{ Form::label('','Purchase Info',array('id'=>'','class'=>'')) }}
					<p><a href="/admin/purchases/{{ $participant->purchase->id }}">{{ $participant->purchase->type }}</a></p>
					@if ($participant->purchase->paypal_email)
						<p>{{ $participant->purchase->paypal_email }}</p>
					@endif
				@endif
				@if (!$participant->signed_in)
					{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/participants/' . $participant->id . '/transfer')) }}
						<div class="form-group">
							{{ Form::label('event_id','Transfer to event',array('id'=>'','class'=>'')) }}
							{{ 
								Form::select(
									'event_id',
									Helpers::getEventNames('DESC', 0, true),
									'',
									array(
										'id'=>'event_id',
										'class'=>'form-control'
									)
								)
							}}
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-default btn-block">Transfer</button>
						</div>
					{{ Form::close() }}
					{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/participants/' . $participant->id . '/signin')) }}
						<div class="form-group">
							<button type="submit" class="btn btn-success btn-block">Sign in</button>
						</div>
					{{ Form::close() }}
					<hr>
					<div class="form-group">
						<button type="submit" class="btn btn-danger btn-block" disabled>Refund - <small>Coming soon</small></button>
					</div>
				@endif
			</div>
		</div>

	</div>
</div>

@endsection