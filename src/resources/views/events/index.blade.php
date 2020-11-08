@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - Events')

@section ('content')

<div class="container">
	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h1>
			Events
		</h1>
	</div>
	@foreach ($events as $event)
		<div class="card @if(Settings::isDarkModeEnabled()) border-light @endif mb-3">
			<div class="card-header @if(Settings::isDarkModeEnabled()) border-light @endif ">
				<strong><a href="/events/{{ $event->slug }}">{{ $event->display_name }}</a></strong>
			</div>
			<div class="card-body">
				<h5>{!! $event->desc_short !!}</h5>
				<p>{!! $event->essential_info !!}</p>
				<p class="bg-success  padding">@lang('events.start'): {{ date('H:i d-m-Y', strtotime($event->start)) }}</p>
				<p class="bg-danger  padding">@lang('events.end'): {{ date('H:i d-m-Y', strtotime($event->end)) }}</p>
				<p class="bg-info  padding">@lang('events.seatingcapacity'): {{ $event->getSeatingCapacity() }}</p>
			</div>
		</div>
	@endforeach
</div>

@endsection
