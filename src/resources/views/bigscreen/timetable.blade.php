@extends ('layouts.bigscreen')

@section ('page_title', config('app.name') . ' - Big Screen - Timetable')

@section ('content')

<div class="container">
    <!-- TIMETABLE -->
	@if (!$event->timetables->isEmpty())
		<div class="page-header">
			<a name="timetable"></a>
			<h3>Timetable</h3>
		</div>
		@foreach ($event->timetables as $timetable)
			@if (strtoupper($timetable->status) == 'DRAFT')
				<h4>DRAFT</h4>
			@endif
			<h4>{{ $timetable->name }}</h4>
			<table class="table table-striped">
				<thead>
					<th>
						Time
					</th>
					<th>
						Game
					</th>
					<th>
						Description
					</th>
				</thead>
				<tbody>
					@foreach ($timetable->data as $slot)
						@if ($slot->name != NULL && $slot->desc != NULL)
							<tr>
								<td>
									{{ date("D", strtotime($slot->start_time)) }} - {{ date("H:i", strtotime($slot->start_time)) }}
								</td>
								<td>
									{{ $slot->name }}
								</td>
								<td>
									{{ $slot->desc }}
								</td>
							</tr>
						@endif
					@endforeach
				</tbody>
			</table>
		@endforeach
	@endif
</div>
@endsection
