@extends('layouts.admin.default')

@section('page_title', 'Timetables - ' . $event->display_name)

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Timetables - {{ $timetable->name }}</h1>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/events/">Events</a>
			</li>
			<li>
				<a href="/admin/events/{{ $event->slug }}">{{ $event->display_name }}</a> 
			</li>
			<li>
				<a href="/admin/events/{{ $event->slug }}/timetables">Timetables</a>
			</li>
			<li class="active">
				{{ $timetable->name }}
			</li>
		</ol>
	</div>
</div>

@include('layouts._partials._admin._event.dashMini')

<div class="row">
	<div class="col-lg-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Timeslots
			</div>
			<div class="panel-body">
				<table width="100%" class="table table-striped table-hover" id="timetable_table">
					<thead>
						<tr>
							<th>Time</th>
							<th>Game</th>
							<th>Desc</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@foreach($timetable->data as $slot)
							<tr class="odd gradeX">
								<td>{{ date("D", strtotime($slot->start_time)) }} - {{ date("H:i", strtotime($slot->start_time)) }}</td>
								@if($slot->name == NULL && $slot->desc == NULL)
									<td>EMPTY</td>
									<td>EMPTY</td>
								@else
									<td>{{ $slot->name }}</td>
									<td>{{ $slot->desc }}</td>
								@endif
								<td width="10%">
									<button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal" onclick="editTimeSlot('{{ $slot->id }}', '{{ $slot->start_time }}', '{{ $slot->name }}', '{{ $slot->desc }}')" data-target="#editTimeSlotModal">Edit</button>
							</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>

	
	</div>
	<div class="col-lg-4">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-plus fa-fw"></i> Add New Slot
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/timetables/' . $timetable->slug . '/data')) }}
					@if ($errors->any())
					  	<div class="alert alert-danger">
					        <ul>
					          	@foreach ($errors->all() as $error)
					            	<li>{{ $error }}</li>
					          	@endforeach
					        </ul>
					  	</div>
					@endif
					<div class="row">
						<div class="form-group col-lg-6">
							{{ Form::label('game','Game',array('id'=>'','class'=>'')) }}
							{{ Form::text('game', NULL ,array('id'=>'game','class'=>'form-control')) }}
						</div> 
						 <div class="form-group col-lg-6">
							{{ Form::label('start','Start',array('id'=>'','class'=>'')) }}
							{{ Form::select('start', $timetable->getAvailableTimes(), null, array('id'=>'start','class'=>'form-control')) }}
						</div>
						<div class="form-group col-lg-12">
							{{ Form::label('desc','Description',array('id'=>'','class'=>'')) }}
							{{ Form::text('desc', NULL ,array('id'=>'desc','class'=>'form-control')) }}
						</div> 
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Settings
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/timetables/' . $timetable->slug)) }}
					@if ($errors->any())
					  	<div class="alert alert-danger">
					        <ul>
					          	@foreach ($errors->all() as $error)
					            	<li>{{ $error }}</li>
					          	@endforeach
					        </ul>
					  	</div>
					@endif
					<div class="row">
						<div class="form-group col-lg-6">
							{{ Form::label('name','Name',array('id'=>'','class'=>'')) }}
							{{ Form::text('name', $timetable->name ,array('id'=>'name','class'=>'form-control')) }}
						</div>
						<div class="form-group col-lg-6">
							{{ Form::label('status','Status',array('id'=>'','class'=>'')) }}
							{{ 
								Form::select(
									'status',
									array(
										'draft'=>'Draft',
										'published'=>'Published',
									),
									$event->status,
									array(
										'id'=>'status',
										'class'=>'form-control'
									)
								)
							}}
						</div>
					</div>
					<div class="form-group">
						<div class="checkbox">
							<label>
								@if($timetable->primary)
									{{ Form::checkbox('primary', 1, true)}} Primary Timetable
								@else
									{{ Form::checkbox('primary', 1)}} Primary Timetable
								@endif
							</label>
						</div>
					</div>  
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
				<hr>
				{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/timetables/' . $timetable->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
					{{ Form::hidden('_method', 'DELETE') }}
					<button type="submit" class="btn btn-danger">Delete</button>
				{{ Form::close() }}
			</div>
		</div>
	
	</div>
</div>

<!-- Modals -->

<div class="modal fade" id="editTimeSlotModal" tabindex="-1" role="dialog" aria-labelledby="editTimeSlotModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="editTimeSlotModal">Edit Time Slot</h4>
			</div>
			<div class="modal-body">
				{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/timetables/' . $timetable->slug . '/data', 'id'=>'editTimeSlotForm')) }}
					<div class="row">
						<div class="form-group col-lg-6">
							{{ Form::label('game','Game',array('id'=>'','class'=>'')) }}
							{{ Form::text('game', NULL ,array('id'=>'editTimetableGame','class'=>'form-control')) }}
						</div> 
						 <div class="form-group col-lg-6">
							{{ Form::label('start','Start',array('id'=>'','class'=>'')) }}
							{{ Form::select('start', $timetable->getAvailableTimes(), null, array('id'=>'editTimetableStart','class'=>'form-control')) }}
						</div>
						<div class="form-group col-lg-12">
							{{ Form::label('desc','Description',array('id'=>'','class'=>'')) }}
							{{ Form::text('desc', NULL ,array('id'=>'editTimetableDesc','class'=>'form-control')) }}
						</div> 
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>
<script>
	function editTimeSlot(id, timestamp, name, desc)
	{
		$("#editTimeSlotForm").prop('action', '/admin/events/{{ $event->slug }}/timetables/{{ $timetable->slug }}/data/' + id);
		$('#editTimetableStart').val(timestamp);
		$('#editTimetableGame').val(name);
		$('#editTimetableDesc').val(desc);
	}
</script>

@endsection
