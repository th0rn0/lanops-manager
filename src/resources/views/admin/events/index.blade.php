@extends ('layouts.admin-default')

@section ('page_title', 'Events')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Events</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">
				Events
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-8">

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-th-list fa-fw"></i> Events
			</div>
			<div class="card-body">
				<div class="dataTable_wrapper">
					<table width="100%" class="table table-striped table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th>Name</th>
								<th>Start</th>
								<th>End</th>
								<th>Short Description</th>
								<th>Capacity</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($events as $event)
								<tr class="table-row" class="odd gradeX">
									<td>{{ $event->display_name }}</td>
									<td>{{ date('d-m-y H:i', strtotime($event->start)) }}</td>
									<td>{{ date('d-m-y H:i', strtotime($event->end)) }}</td>
									<td>{!! $event->desc_short !!}</td>
									<td class="center">{{ $event->capacity }} <small>Seats:{{ $event->getSeatingCapacity() }}</small></td>
									<td width="15%">
										<a href="/admin/events/{{ $event->slug }}"><button type="button" class="btn btn-primary btn-sm btn-block">Edit</button></a>
									</td>
									<td width="15%">
										{{ Form::open(array('url'=>'/admin/events/' . $event->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
											{{ Form::hidden('_method', 'DELETE') }}
											<button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
										{{ Form::close() }}
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					{{ $events->links() }}
				</div>
			</div>
		</div>

	</div>
	<div class="col-lg-4">

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-plus fa-fw"></i> Add Event
			</div>
			<div class="card-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/events/')) }}
						<div class="form-group">
							{{ Form::label('event_name','Event Name',array('id'=>'','class'=>'')) }}
							{{ Form::text('event_name', '',array('id'=>'event_name','class'=>'form-control')) }}
						</div>
						<div class="row">
							<div class="col-lg-6 col-sm-12 form-group">
								{{ Form::label('start_date','Start Date',array('id'=>'','class'=>'')) }}
								{{ Form::text('start_date', '',array('id'=>'start_date','class'=>'form-control')) }}
							</div>
							<div class="col-lg-6 col-sm-12 form-group">
								{{ Form::label('start_time','Start  Time',array('id'=>'','class'=>'')) }}
								{{ Form::text('start_time', '16:00',array('id'=>'start_time','class'=>'form-control')) }}
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-sm-12 form-group">
								{{ Form::label('end_date','End Date',array('id'=>'','class'=>'')) }}
								{{ Form::text('end_date', '',array('id'=>'end_date','class'=>'form-control')) }}
							</div>
							<div class="col-lg-6 col-sm-12 form-group">
								{{ Form::label('end_time','End Time',array('id'=>'','class'=>'')) }}
								{{ Form::text('end_time', '18:00',array('id'=>'end_time','class'=>'form-control')) }}
							</div>
						</div>
						<div class="form-group">
							{{ Form::label('desc_short','Short Description',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('desc_short', '',array('id'=>'desc_short','class'=>'form-control wysiwyg-editor-small', 'rows'=>'2')) }}
						</div>
						<div class="form-group">
							{{ Form::label('desc_long','Long Description',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('desc_long', '',array('id'=>'desc_long','class'=>'form-control wysiwyg-editor-small', 'rows'=>'2')) }}
						</div>
						<div class="form-group">
							{{ Form::label('essential_info','Essential Info',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('essential_info', '',array('id'=>'essential_info','class'=>'form-control wysiwyg-editor-small', 'rows'=>'2')) }}
						</div>
						<div class="form-group">
							{{ Form::label('event_live_info','Event Live Info',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('event_live_info', '',array('id'=>'event_live_info','class'=>'form-control wysiwyg-editor-small', 'rows'=>'2')) }}
						</div>
						<div class="row">
							<div class="col-lg-6 col-sm-12 form-group">
								{{ Form::label('capacity','Capacity',array('id'=>'','class'=>'')) }}
								{{ Form::text('capacity', '',array('id'=>'capacity','class'=>'form-control')) }}
							</div>
							<div class="col-lg-6 col-sm-12 form-group">
								{{ Form::label('venue','Venue',array('id'=>'','class'=>'')) }}
								{{ Form::select('venue', Helpers::getVenues(), null, array('id'=>'venue','class'=>'form-control')) }}
							</div>
						</div>
						<div class="form-check">
							<label class="form-check-label">
								{{ Form::checkbox('allow_spec','Y',true, array('id'=>'allow_spec')) }} Allow Spectators
							</label>
						</div>
						<div class="form-check">
								<label class="form-check-label">
									{{ Form::checkbox('online_event', null, false, array('id'=>'online_event')) }} Online Event (allow tournament registration and home redirection without being signed in to the event)
								</label>
						</div>

						@if ($eventTags)
							<div class="form-group">
								<label>Eventula Tags</label>
								<p><small>These are used and edited on the Eventula Hub site</small></p>
								<div class="row">
								 	@foreach ($eventTags as $eventTag)
								 		<div class="col-12 col-sm-6">
			                                <div class="form-check form-check-inline">
			                                    <input class="form-check-input" type="checkbox" value="{{ $eventTag->id }}" name="event_tags[{{ $eventTag->id }}]" id="event_tags[{{ $eventTag->id }}]">
			                                    <label class="form-check-label" for="event_tags[{{ $eventTag->id }}]">
			                                        {{ $eventTag->name }}
			                                    </label>
			                                </div>
			                            </div>
		                            @endforeach
		                        </div>
							</div>
						@endif
						<button type="submit" class="btn btn-success btn-block">Submit</button>
					{{ Form::close() }}
				</div>
			</div>
		</div>

	</div>
</div>

<!-- JavaScript-->
<script type="text/javascript">
	jQuery( function() {
		jQuery( "#start_date" ).datepicker();
		jQuery( "#end_date" ).datepicker();
	});
</script>

@endsection
