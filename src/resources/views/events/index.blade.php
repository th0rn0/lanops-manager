@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' Tournaments List')

@section ('content')
			
<div class="container">
	<div class="page-header">
		<h1>
			Events
		</h1> 
	</div>
	@foreach ($events as $event)
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong><a href="/events/{{ $event->slug }}">{{ $event->display_name }}</a></strong>
			</div>
			<div class="panel-body">
				<h5>{{ $event->desc_short }}</h5>
				<p>{{ $event->desc_long }}</p>
				<p class="bg-success  padding">Start: {{ date('H:i d-m-Y', strtotime($event->start)) }}</p>
				<p class="bg-danger  padding">End: {{ date('H:i d-m-Y', strtotime($event->end)) }}</p>
				<p class="bg-info  padding">Seating Capacity: {{ $event->getSeatingCapacity() }}</p>
			</div>
		</div>
	@endforeach
</div>

@endsection
