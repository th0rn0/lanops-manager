@extends ('layouts.admin-default')

@section ('page_title', 'Seating Plans - ' . $event->display_name)

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Seating Plans - {{ $seatingPlan->name }}</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="/admin/events/">Events</a>
			</li>
			<li class="breadcrumb-item">
				<a href="/admin/events/{{ $event->slug }}">{{ $event->display_name }}</a>
			</li>
			<li class="breadcrumb-item">
				<a href="/admin/events/{{ $event->slug }}/seating">Seating Plans</a>
			</li>
			<li class="breadcrumb-item active">
				{{ $seatingPlan->name }}
			</li>
		</ol>
	</div>
</div>

@include ('layouts._partials._admin._event.dashMini')

<div class="row">
	<div class="col-lg-8">

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-desktop fa-fw"></i> Seating Plan Preview
			</div>
			<div class="card-body">
				<table class="table table-responsive">

					<?php
					$headers = explode(',', $seatingPlan->headers);
					$headers = array_combine(range(1, count($headers)), $headers);
					?>

					@for ($row = 1; $row <= $seatingPlan->rows; $row++)

						<tr>
							<td>
								<h4><strong>ROW {{ucwords($headers[$row])}}</strong></h4>
							</td>
							@for ($column = 1; $column <= $seatingPlan->columns; $column++)
								<td style="padding-top:14px;">
									@if($event->getSeat($seatingPlan->id, ucwords($headers[$row]) . $column))
									@foreach($seatingPlan->seats as $seat)
									<?php
									if ($seat->seat == (ucwords($headers[$row]) . $column)) {
										$status = $seat->status;
									}
									if ($seat->seat == (ucwords($headers[$row]) . $column) && isset($seat->eventParticipant)) {
										$username = $seat->eventParticipant->user->username;
										$participant_id = $seat->eventParticipant->id;
									}
									?>
									@endforeach
									@if($status == 'ACTIVE' && isset($seat->eventParticipant))
									<button class="btn btn-success btn-sm" onclick="editSeating('{{ ucwords($headers[$row]) . $column }}', '{{ $username }}', '{{ $participant_id }}', '{{ $status }}')" data-bs-toggle="modal" data-bs-target="#editSeatingModal">
										{{ ucwords($headers[$row]) . $column }} - {{ $username }}
									</button>
									@else
									<button class="btn btn-danger btn-sm" onclick="editSeating('{{ ucwords($headers[$row]) . $column }}', null, null, '{{ $status }}')" data-bs-toggle="modal" data-bs-target="#editSeatingModal">
										{{ ucwords($headers[$row]) . $column }} - Inactive
									</button>
									@endif
									@else
									<button class="btn btn-primary btn-sm" onclick="editSeating('{{ ucwords($headers[$row]) . $column }}', null, null, 'ACTIVE')" data-bs-toggle="modal" data-bs-target="#editSeatingModal">
										{{ ucwords($headers[$row]) . $column }} - Empty
									</button>
									@endif
								</td>
								@endfor
						</tr>
						@endfor
				</table>
			</div>
		</div>
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-th fa-fw"></i> Seating Plan
			</div>
			<div class="card-body">
				<div class="dataTable_wrapper">
					<table width="100%" class="table table-striped table-hover" id="seating_table">
						<thead>
							<tr>
								<th></th>
								<th>Seat Number</th>
								<th>User</th>
								<th>Name</th>
								<th>Ticket ID</th>
								<th>Purchase ID</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($seats as $seat)
							@if (isset($seat->eventParticipant))
							<tr class="odd gradeX">
								<td></td>
								<td>{{ ucwords($seat->seat) }}</td>
								<td>
									{{ $seat->eventParticipant->user->username }}
									@if ($seat->eventParticipant->user->steamid)
									<br><span class="text-muted"><small>Steam: {{ $seat->eventParticipant->user->steamname }}</small></span>
									@endif
								</td>
								<td>{{ $seat->eventParticipant->user->firstname }} {{ $seat->eventParticipant->user->surname }}</td>
								<td>
									@if(empty($seat->eventParticipant->ticket_id))
									Free
									@else
									{{ $seat->eventParticipant->ticket_id }}
									@endif
								</td>
								<td>
									@if(empty($seat->eventParticipant->purchase_id))
									Free
									@else
									{{ $seat->eventParticipant->purchase_id }}
									@endif
								</td>
								<td width="10%">
									<button type="button" class="btn btn-primary btn-sm btn-block" onclick="editSeating('{{ ucwords($seat->seat) }}', '{{ $seat->eventParticipant->user->username }}', '{{ $seat->eventParticipant->id }}', '{{ $seat->status }}')" data-bs-toggle="modal" data-bs-target="#editSeatingModal">Edit</button>
								</td>
							</tr>
							@endif
							@endforeach
						</tbody>
					</table>
					{{ $seats->links() }}
				</div>
			</div>
		</div>

	</div>
	<div class="col-lg-4">

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-wrench fa-fw"></i> Settings
			</div>
			<div class="card-body">
				{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/seating/' . $seatingPlan->slug, 'files' => 'true')) }}
				<div class="row">
					<div class="col-lg-12 col-sm-12 mb-3">
						{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
						{{ Form::text('name', $seatingPlan->name ,array('id'=>'name','class'=>'form-control')) }}
					</div>
					<div class="col-lg-12 col-sm-12 mb-3">
						{{ Form::label('name_short','Short Name',array('id'=>'','class'=>'')) }}
						{{ Form::text('name_short', $seatingPlan->name_short ,array('id'=>'name_short','class'=>'form-control')) }}
						<small>For display on Attendance Lists</small>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-lg-12 col-sm-12 mb-3">
						{{ Form::label('event_status','Status',array('id'=>'','class'=>'')) }}
						{{
									Form::select(
										'status',
										array(
											'draft'=>'Draft',
											'preview'=>'Preview',
											'published'=>'Published',
										),
										strtolower($seatingPlan->status),
										array(
											'id'=>'status',
											'class'=>'form-control'
										)
									)
								}}
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-sm-12 mb-3">
						{{ Form::label('columns','Columns',array('id'=>'','class'=>'')) }}
						{{ Form::text('columns', $seatingPlan->columns ,array('id'=>'columns','class'=>'form-control')) }}
					</div>
					<div class="col-lg-6 col-sm-12 mb-3">
						{{ Form::label('rows','Rows',array('id'=>'','class'=>'')) }}
						{{ Form::text('rows', $seatingPlan->rows ,array('id'=>'rows','class'=>'form-control')) }}
					</div>
				</div>
				<div class="mb-3">
					{{ Form::label('image','Seating Plan Image',array('id'=>'','class'=>'')) }}
					{{ Form::file('image',array('id'=>'image','class'=>'form-control')) }}
				</div>
				<div class="mb-3">
					<div class="form-check">
						<label class="form-check-label">
							@if ($seatingPlan->locked)
							{{ Form::checkbox('locked', 1, true, array('id'=>'locked'))}} Lock Seating
							@else
							{{ Form::checkbox('locked', 1, false, array('id'=>'locked'))}} Lock Seating
							@endif
						</label>
					</div>
				</div>
				<button type="submit" class="btn btn-success btn-block">Submit</button>
				@if ($seatingPlan->image_path)
				<hr>
				<h4>Image Preview</h4>
				<picture>
					<source dsrcset="{{ $seatingPlan->image_path }}.webp" type="image/webp">
					<source srcset="{{ $seatingPlan->image_path }}" type="image/jpeg">
					<img alt="{{ $seatingPlan->name }} Layout" class="img img-fluid" src="{{ $seatingPlan->image_path }}">
				</picture>
				@endif
				{{ Form::close() }}
			</div>
		</div>

	</div>
</div>

<!-- Modals -->
<div class="modal fade" id="editSeatingModal" tabindex="-1" role="dialog" aria-labelledby="editSeatingModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="editSeatingModalLabel">Edit Seating</h4>
				<button type="button" class="btn-close text-decoration-none" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body">
				{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/seating/' . $seatingPlan->slug . '/seat')) }}
				<div class="mb-3">
					{{ Form::label('seat_number_modal','Seat Number',array('id'=>'','class'=>'')) }}
					{{ Form::text('seat_number_modal', null, array('id'=>'seat_number_modal','class'=>'form-control')) }}
				</div>
				<div class="mb-3">
					{{ Form::label('participant_select_modal','Participant',array('id'=>'','class'=>'')) }}
					{{ Form::select('participant_select_modal', $event->getParticipants(), null, array('id'=>'participant_select_modal','class'=>'form-control')) }}
				</div>
				<div class="mb-3">
					{{ Form::label('seat_status_select_modal','Seat status',array('id'=>'','class'=>'')) }}
					{{ Form::select(
							'seat_status_select_modal',
							array(
								'ACTIVE'=>'Active',
								'INACTIVE'=>'Inactive'),
							'Active',
							array('id'=>'seat_status_select_modal','class'=>'form-control')) }}
				</div>
				{{ Form::hidden('participant_id_modal', null, array('id'=>'participant_id_modal','class'=>'form-control')) }}
				{{ Form::hidden('event_id_modal', null, array('id'=>'event_id_modal','class'=>'form-control')) }}

				<a href="" id="participant_link">
					<button type="button" class="btn btn-secondary btn-block">Go to Participant</button>
				</a>
				<br>
				<button type="submit" class="btn btn-success btn-block">Save Changes</button>
				{{ Form::close() }}
				{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/seating/' . $seatingPlan->slug . '/seat', 'id'=>'clear_seat_form')) }}
				<hr>
				{{ Form::hidden('_method', 'DELETE') }}
				{{ Form::hidden('seat_number', null, array('id'=>'seat_number')) }}
				<button type="submit" class="btn btn-danger btn-block">Clear Seat</button>
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>

<!-- JavaScript-->
<script>
	function editSeating(seat, username = null, participant_id = null, seat_status = null) {
		seat = seat.trim();
		jQuery("#seat_number_modal").val(seat);
		jQuery("#seat_number").val(seat);
		jQuery("#seat_status_select_modal").val(seat_status);
		var orginal_participant_id = jQuery("#participant_id_modal").val();
		//Reset all inputs
		jQuery("#seat_number_modal").prop('readonly', '');
		jQuery("#participant_link").prop('href', '');
		jQuery("#participant_link").hide();
		jQuery("#clear_seat_form").hide();
		jQuery("#participant_select_modal").val('');
		jQuery("#participant_id_modal").val('');
		jQuery('#participant_select_modal option[value="' + orginal_participant_id + '"]').attr("selected", false);

		jQuery("#seat_number_modal").prop('readonly', 'readonly');
		if (username != null) {
			//we have a user - Fill in extra
			jQuery("#participant_link").prop('href', '/admin/events/{{ $event->slug }}/participants/' + participant_id);
			jQuery("#participant_link").show();
			jQuery("#clear_seat_form").show();
			jQuery('#participant_select_modal').val(participant_id);
		}
		if (seat_status == 'INACTIVE') {
			//we have an inactive seat status - show opportunity to clear seat
			jQuery("#clear_seat_form").show();
		}
	}
</script>

@endsection