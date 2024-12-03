@extends ('layouts.admin-default')

@section ('page_title', 'Participants - ' . $event->display_name . ' | ' . config('app.name') . ' Admin')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">Participants</h3>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/events/">Events</a>
			</li>
			<li>
				<a href="/admin/events/{{ $event->slug }}">{{ $event->display_name }}</a> 
			</li>
			<li class="active">
				Participants
			</li>
		</ol>
	</div>
</div>

@include ('layouts._partials._admin._event.dashMini')

<div class="row">
	<div class="col-lg-12">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> All Participants
				<a href="/admin/events/{{ $event->slug }}/tickets#freebies" class="btn btn-info btn-xs pull-right">Freebies</a>
			</div>
			<div class="panel-body">
				<div class="dataTable_wrapper">
					<table width="100%" class="table table-striped table-hover" id="seating_table">
						<thead>
							<tr>
								<th>User</th>
								<th>Name</th>
								<th>Seat</th>
								<th>Ticket</th>
								<th>Paypal Email</th>
								<th>Referralsadsa Code Used</th>
								<th>Referral Discount Redeemed</th>
								<th>Free/Staff/Gift</th>
								<th>Signed in</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							 @foreach ($participants as $participant)
								<tr class="odd gradeX">
									<td>
										{{ $participant->user->username }}
										@if ($participant->user->steamid)
											<br><span class="text-muted"><small>Steam: {{ $participant->user->steamname }}</small></span>
										@endif
									</td>
									<td>{{ $participant->user->firstname }} {{ $participant->user->surname }}</td>
									<td>
										@if (isset($participant->seat)) {{ $participant->seat->seat }} @endif
									</td>
									<td>
										@if ($participant->free) Free @endif
										@if ($participant->ticket) {{ $participant->ticket->name }} @endif
									</td>
									<td>
										@if ($participant->purchase) {{ $participant->purchase->paypal_email }} @endif
									</td>
									<td>
										@if ($participant->purchase->referral_code_user_id)
											Yes
										@else
											No
										@endif
									</td>
									<td>
										@if ($participant->purchase->referral_discount_total > 0)
											Yes
										@else
											No
										@endif
									</td>
									<td>
										@if ($participant->free)
											<strong>Free</strong>
											<small>Assigned by: {{ $participant->getAssignedByUser()->username }}</small>
										@elseif ($participant->staff)
											<strong>Staff</strong>
											<small>Assigned by: {{ $participant->getAssignedByUser()->username }}</small>
										@elseif ($participant->gift)
											<strong>Gift</strong>
											<small>Assigned by: {{ $participant->getGiftedByUser()->username }}</small>
										@endif
									</td>
									<td>
										@if ($participant->signed_in)
											Yes
										@else
											No
										@endif
									</td>
									<td width="10%">
										<a href="/admin/events/{{ $event->slug }}/participants/{{ $participant->id }}">
											<button type="button" class="btn btn-primary btn-sm btn-block">Edit</button>
										</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					{{ $participants->links() }}
				</div>
			</div>
		</div>

	</div>
</div>

@endsection