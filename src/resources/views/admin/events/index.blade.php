@extends ('layouts.admin-default')

@section ('page_title', 'Events')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">Events</h3>
		<ol class="breadcrumb">
			<li class="active">
				Events
			</li>
		</ol>  
	</div>
</div>

<div class="row">
	<div class="col-lg-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-th-list fa-fw"></i> Events
			</div>
			<div class="panel-body">
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
									<td>{{ $event->desc_short }}</td>
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

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-plus fa-fw"></i> Add Event
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
							{{ Form::text('desc_short', '',array('id'=>'desc_short','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('desc_long','Long Description',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('desc_long', '',array('id'=>'desc_long','class'=>'form-control', 'rows'=>'2')) }}
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
						<div class="checkbox">
							<label>
								{{ Form::checkbox('allow_spec','Y',true) }} Allow Spectators
							</label>
						</div>
						<button type="submit" class="btn btn-success btn-block">Submit</button>
					{{ Form::close() }}
				</div>
			</div>
		</div>

	</div>
</div>

@endsection
