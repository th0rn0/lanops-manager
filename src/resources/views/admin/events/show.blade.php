@extends ('layouts.admin-default')

@section ('page_title', $event->display_name)

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">{{ $event->display_name }}</h1>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/events/">Events</a>
			</li>
			<li class="active">
				{{ $event->display_name }}
			</li>
		</ol>
	</div>
</div>

@include ('layouts._partials._admin._event.dashMini')

<div class="row">
	<div class="col-lg-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Settings
			</div>
			<div class="panel-body">
				@if ($errors->any())
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
				{{ Form::open(array('url'=>'/admin/events/' . $event->slug, 'files' => 'true')) }}
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="form-group">
								{{ Form::label('event_name','Name',array('id'=>'','class'=>'')) }}
								{{ Form::text('event_name',$event->display_name,array('id'=>'event_name','class'=>'form-control')) }}
							</div>
							<div class="row">
								<div class="col-md-6 col-xs-12">
									<div class="row">
										<div class="col-md-6 col-xs-12">
											<div class="form-group">
												{{ Form::label('capacity','Capacity',array('id'=>'','class'=>'')) }}
												{{ Form::text('capacity',$event->capacity,array('id'=>'capacity','class'=>'form-control')) }}
											</div>
										</div>
										<div class="col-md-6 col-xs-12">
											<div class="form-group">
												{{ Form::label('seating_cap','Seating Capacity',array('id'=>'','class'=>'')) }}
												{{ Form::text('seating_cap',$event->getSeatingCapacity(),array('id'=>'seating_cap','class'=>'form-control', 'disabled'=>'true')) }}
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-xs-12">
									<div class="form-group">
										{{ Form::label('event_status','Status',array('id'=>'','class'=>'')) }}
										{{ 
											Form::select(
												'status',
												array(
													'draft'=>'Draft',
													'preview'=>'Preview',
													'published'=>'Published',
													'private' => 'Private'
												),
												strtolower($event->status),
												array(
													'id'=>'status',
													'class'=>'form-control'
												)
											)
										}}
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-12">
							<div class="form-group">
								{{ Form::label('start','Start Date',array('id'=>'','class'=>'')) }}
								{{ Form::text('start',$event->start,array('id'=>'start','class'=>'form-control', 'disabled' => 'true')) }}
							</div>
							<div class="form-group">
								{{ Form::label('end','End Date',array('id'=>'','class'=>'')) }}
								{{ Form::text('end',$event->end,array('id'=>'end','class'=>'form-control', 'disabled' => 'true')) }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8 col-sm-12">
							<div class="form-group">
								{{ Form::label('desc_short','Short Description',array('id'=>'','class'=>'')) }}
								{{ Form::text('desc_short', $event->desc_short,array('id'=>'desc_short','class'=>'form-control')) }}
							</div>
							<div class="form-group">
								{{ Form::label('desc_long','Long Description',array('id'=>'','class'=>'')) }}
								{{ Form::textarea('desc_long',$event->desc_long,array('id'=>'desc_long','class'=>'form-control', 'rows' => '4')) }}
							</div>
						</div>
						<div class="col-md-4 col-sm-12">
							<strong>Venue</strong>
							<address>
								@if ($event->venue->display_name) {{ $event->venue->display_name }}<br> @endif
								@if ($event->venue->address_1) {{ $event->venue->address_1 }}<br> @endif
								@if ($event->venue->address_2) {{ $event->venue->address_2 }}<br> @endif
								@if ($event->venue->address_street) {{ $event->venue->address_street }}<br> @endif
								@if ($event->venue->address_city) {{ $event->venue->address_city }}<br> @endif
								@if ($event->venue->address_postcode) {{ $event->venue->address_postcode }}<br> @endif
								@if ($event->venue->address_country) {{ $event->venue->address_country }}<br> @endif
							</address>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<div class="form-group">
								{{ Form::label('essential_info','Essential Info',array('id'=>'','class'=>'')) }}
								{{ Form::textarea('essential_info',$event->essential_info,array('id'=>'essential_info','class'=>'form-control wysiwyg-editor')) }}
								<small>This will show on the event home page</small>
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
				<hr>
				{{ Form::open(array('url'=>'/admin/events/' . $event->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
					{{ Form::hidden('_method', 'DELETE') }}
					<button type="submit" class="btn btn-danger">Delete</button>
				{{ Form::close() }}
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-info fa-fw"></i> Event Information
				<a href="#" class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#addEventInformationModal">Add Event Information</a>
			</div>
			<div class="panel-body">
				@if ($event->information->count() != 0)
					@foreach ($event->information as $section)
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion" href="#collaspe-{{ $section->id }}" aria-expanded="false" class="collapsed">{{ $section->title }}</a>
									<div class="pull-right">
										{{ Form::open(array('url' => '/admin/information/' . $section->id)) }}
											{{ Form::hidden('_method', 'DELETE') }}
											{{ Form::submit('Delete', array('class' => 'btn btn-danger btn-xs pull-right', 'style' => 'margin-top:-2px;')) }}
										{{ Form::close() }}
									</div>
								</h4>
							</div>
							<div id="collaspe-{{ $section->id }}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
								<div class="panel-body">
									{{ Form::open(array('url'=>'/admin/information/' . $section->id, 'files' => 'true')) }}
										<div class="row">
											<div class="col-md-6 col-sm-12">
												<div class="form-group">
													{{ Form::label('title','Title',array('id'=>'','class'=>'')) }}
													{{ Form::text('title', $section->title,array('id'=>'title','class'=>'form-control')) }}
												</div>
											</div>
											<div class="col-md-6 col-sm-12">
												<div class="form-group">
													{{ Form::label('image','Image',array('id'=>'','class'=>'')) }}
													{{ Form::file('image',array('id'=>'image','class'=>'form-control')) }}
												</div>
											</div>
										</div>
										
										<div class="form-group">
											{{ Form::label('text','Text',array('id'=>'','class'=>'')) }}
											{{ Form::textarea('text', $section->text,array('id'=>'text','class'=>'form-control', 'rows' => '4')) }}
										</div>
										<div class="row">
											<div class="form-group col-lg-6">
												{{ Form::label('preview','Image Preview',array('id'=>'','class'=>'')) }}
												@if(isset($section->image_path))
													<center>
														<img class="img-responsive img-thumbnail" src="{{ $section->image_path }}" />
													</center>
												@endif
											</div>  
										</div>
										SECTION LISTING PRIORITY HERE<br>
										<button type="submit" class="btn btn-default">Update</button>
									{{ Form::close() }}
								</div>
							</div>
						</div>
					@endforeach
				@else
					<h4>None</h4>
				@endif
			</div>
		</div>

	</div>
	<div class="col-lg-4">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-bullhorn fa-fw"></i> Announcements
				<a href="#" class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#addAnnouncementModal">Add Announcement</a>
			</div>
			<div class="panel-body">
				@if ($event->announcements->count() != 0)
					<div class="list-group">
						@foreach ($event->announcements as $announcement)
							<a href="#" class="list-group-item" data-toggle="modal" onclick="editAnnouncement('{{$announcement->id}}', '{{$announcement->message}}')" data-target="#editAnnouncementModal">
								<i class="fa fa-comment fa-fw"></i> {{ $announcement->message }}
							</a>
						@endforeach
					</div>
				@else
					<h4>None</h4>
				@endif
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-ticket fa-fw"></i> Tickets
				<a href="/admin/events/{{ $event->slug }}/tickets" style="margin-left:3px;" class="btn btn-info btn-xs pull-right">All Tickets</a>
				<a href="#" class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#addTicketModal">Add Ticket</a>
			</div>
			<div class="panel-body">
				@if ($event->tickets->count() != 0)
					<div class="list-group">
						@foreach ($event->tickets as $ticket)
							<a href="/admin/events/{{ $event->slug }}/tickets/{{ $ticket->id }}" class="list-group-item">
								<i class="fa fa-pencil fa-fw"></i> {{ $ticket->name }} - £{{ $ticket->price }}
								<span class="pull-right text-muted small">
									@if($ticket->quantity != 0)
										<em>{{ $ticket->participants()->count() }} / {{ $ticket->quantity }}</em>
									@else
										<em>Unlimited</em>
									@endif
								</span>
							</a>
						@endforeach
					</div>
				@else
					<h4>None</h4>
				@endif
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-list fa-fw"></i> Polls
			</div>
			<div class="panel-body">
				@if (!$event->polls->isEmpty())
					<div class="list-group">
						@foreach ($event->polls as $poll)
							<a href="/admin/polls/{{ $poll->slug }}" class="list-group-item">
								<i class="fa fa-pencil fa-fw"></i> {{ $poll->name }}
							</a>
						@endforeach
					</div>
				@else
					<h4>None</h4>
				@endif
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-user fa-fw"></i> Attendees <small>Last Ten Signups</small>
				<a href="/admin/events/{{ $event->slug }}/participants" style="margin-left:3px;" class="btn btn-info btn-xs pull-right">All Attendees</a>
				<a href="/admin/events/{{ $event->slug }}/tickets#freebies" class="btn btn-info btn-xs pull-right">Freebies</a>
			</div>
			<div class="panel-body">
				@if (!$event->eventParticipants->isEmpty())
					<div class="list-group">
						@foreach ($event->eventParticipants as $participant)
							<a href="/admin/events/{{ $event->slug }}/participants/{{ $participant->id }}" class="list-group-item">
								<i class="fa fa-comment fa-fw"></i> {{ $participant->user->steamname}} - {{ $participant->user->username}}
								<span class="pull-right text-muted small">
									<em>{{ date('d-m-y H:i', strtotime($participant->created_at)) }}</em>
								</span>
							</a>
						@endforeach
					</div>
				@else
					<h4>None</h4>
				@endif
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-suitcase fa-fw"></i> Sponsors
				<a href="#" class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#addSponsorModal">Add Sponsor</a>
			</div>
			<div class="panel-body">
				@if (!$event->sponsors->isEmpty())
					<div class="list-group">
						@foreach ($event->sponsors as $sponsor)
							<a href="/admin/tickets/{{ $ticket->id }}" class="list-group-item">
								<i class="fa fa-pencil fa-fw"></i> {{ $sponsor->name }} - {{ ucwords($sponsor->website) }}
								<img class="img-responsive img-thumbnail" src="{{ $sponsor->image_path }}" />
							</a>
						@endforeach
					</div>
				@else
					<h4>None</h4>
				@endif
			</div>
		</div>

	</div>
</div>

<!-- Modals -->
	
<div class="modal fade" id="addTicketModal" tabindex="-1" role="dialog" aria-labelledby="addTicketModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="addTicketModalLabel">Add Ticket</h4>
			</div>
			<div class="modal-body">
				{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tickets')) }}
					@include('layouts._partials._admin._event._tickets.form', ['empty' => true])
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$( function() {
		$( "#sale_start_date" ).datepicker();
		$( "#sale_end_date" ).datepicker();
	});
</script>

<div class="modal fade" id="addSponsorModal" tabindex="-1" role="dialog" aria-labelledby="addSponsorModalModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="addSponsorModalLabel">Add Sponsor</h4>
			</div>
			{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/sponsors', 'files' => 'true')) }}
				<div class="modal-body">
					@if ($errors->any())
					  	<div class="alert alert-danger">
					        <ul>
					          	@foreach ($errors->all() as $error)
					            	<li>{{ $error }}</li>
					          	@endforeach
					        </ul>
					  	</div>
					@endif
					<div class="form-group">
						{{ Form::label('sponsor_name','Sponsor Name',array('id'=>'','class'=>'')) }}
						{{ Form::text('sponsor_name',NULL,array('id'=>'sponsor_name','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('sponsor_website','Sponsor Website',array('id'=>'','class'=>'')) }}
						{{ Form::text('sponsor_website',NULL,array('id'=>'sponsor_website','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('sponsor_image','Sponsor Image',array('id'=>'','class'=>'')) }}
						{{ Form::file('sponsor_image',array('id'=>'sponsor_image','class'=>'form-control')) }}
					</div>
					<button type="submit" name="action" value="add_sponsor" class="btn btn-default">Submit</button>
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>

<div class="modal fade" id="addAnnouncementModal" tabindex="-1" role="dialog" aria-labelledby="addAnnouncementModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="announcementModalLabel">Add Announcement</h4>
			</div>
			<div class="modal-body">
				@if ($errors->any())
				  	<div class="alert alert-danger">
				        <ul>
				          	@foreach ($errors->all() as $error)
				            	<li>{{ $error }}</li>
				          	@endforeach
				        </ul>
				  	</div>
				@endif
				{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/announcements', 'files' => 'true')) }}
					<div class="form-group">
						{{ Form::label('message','Message',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('message', NULL,array('id'=>'message','class'=>'form-control', 'rows' => '2')) }}
					</div>
					<button type="submit" class="btn btn-default btn-block">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editAnnouncementModal" tabindex="-1" role="dialog" aria-labelledby="editAnnouncementModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="editAnnouncementModalLabel">Edit Announcement</h4>
			</div>
			<div class="modal-body">
				@if ($errors->any())
				  	<div class="alert alert-danger">
				        <ul>
				          	@foreach ($errors->all() as $error)
				            	<li>{{ $error }}</li>
				          	@endforeach
				        </ul>
				  	</div>
				@endif
				{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/announcements', 'files' => 'true', 'id' => 'editAnnouncementForm')) }}
					<div class="form-group">
						{{ Form::label('message','Message',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('message', NULL,array('id'=>'edit_announcement','class'=>'form-control', 'rows' => '2')) }}
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-default btn-block">Submit</button>
					</div>
				{{ Form::close() }}
				{{ Form::open(array('method'  => 'delete', 'url'=>'/admin/events/' . $event->slug . '/announcements', 'id'=>'deleteAnnouncementForm', 'files' => 'true')) }}
					<button type="submit" class="btn btn-danger btn-block">Delete</button>
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>
<script>
	function editAnnouncement(announcement_id, message)
	{
		$("#editAnnouncementForm").prop('action', '/admin/events/{{ $event->slug }}/announcements/' + announcement_id);
		$("#deleteAnnouncementForm").prop('action', '/admin/events/{{ $event->slug }}/announcements/' + announcement_id);
		$('#edit_announcement').val(message);
	}
</script>

<div class="modal fade" id="addEventInformationModal" tabindex="-1" role="dialog" aria-labelledby="addEventInformationModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="addEventInformationModal">Add Event Information</h4>
			</div>
			<div class="modal-body">
				{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/information', 'files' => 'true')) }}
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="form-group">
								{{ Form::label('title','Title',array('id'=>'','class'=>'')) }}
								{{ Form::text('title',NULL,array('id'=>'title','class'=>'form-control')) }}
							</div>
						</div>
						<div class="col-md-6 col-sm-12">
							<div class="form-group">
								{{ Form::label('image','Image',array('id'=>'','class'=>'')) }}
								{{ Form::file('image',array('id'=>'image','class'=>'form-control')) }}
							</div>
						</div>
					</div>
					<div class="form-group">
						{{ Form::label('text','Text',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('text', NULL,array('id'=>'text','class'=>'form-control', 'rows' => '4')) }}
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>

@endsection
