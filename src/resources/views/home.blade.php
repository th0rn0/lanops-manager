@extends ('layouts.default')

@section ('content')

<div id="hero-carousel" class="carousel fade" data-ride="carousel" data-interval="8000">
	<!-- Wrapper for slides -->
	<div class="carousel-inner" role="listbox">
		@foreach ($sliderImages as $image)
			<div class="item @if ($loop->first) active @endif">
				<img class="hero-image" alt="{{ config('app.name') }} Banner" src="{{ $image }}">
			</div>
		@endforeach
	</div>
	<div class="hero-overlay hidden-sm hidden-xs">
		@if ($nextEventLan)
			<h3>Next LAN Event</h3>
			<h1>{{ $nextEventLan->display_name }}</h1>
			<h5>{{ date('dS', strtotime($nextEventLan->start)) }} - {{ date('dS', strtotime($nextEventLan->end)) }} {{ date('F', strtotime($nextEventLan->end)) }} {{ date('Y', strtotime($nextEventLan->end)) }}</h5>
			<a href="/events/{{ $nextEventLan->slug }}#tickets"><button class="btn btn-orange btn-lg">Book Now</button></a>
		@elseif ($nextEventTabletop)
			<h3>Next Event</h3>
			<h1>{{ $nextEvent->display_name }}</h1>
			<h5>{{ date('dS', strtotime($nextEvent->start)) }} - {{ date('dS', strtotime($nextEvent->end)) }} {{ date('F', strtotime($nextEvent->end)) }} {{ date('Y', strtotime($nextEvent->end)) }}</h5>
			<a href="/events/{{ $nextEvent->slug }}#tickets"><button class="btn btn-orange btn-lg">Book Now</button></a>
		@else
			<div>
				<h3>Next Event</h3>
				<h1>Coming soon</h1>
			</div>
		@endif
	</div>
</div>

<div class="container">
	<div class="row">

		@if ($nextEventLan)
			<div class="col-xs-12">
				<div class="page-header">
					<h3>
						{{ $nextEventLan->display_name }}
						@if (count($nextEventLan->seatingPlans) > 0)
							<small>{{ max($nextEventLan->getSeatingCapacity() - $nextEventLan->eventParticipants->count(), 0) }} / {{ $nextEventLan->getSeatingCapacity() }} Seats Remaining</small>
						@else 
							<small>{{ max($nextEventLan->capacity - $nextEventLan->eventParticipants->count(), 0) }} / {{ $nextEventLan->capacity }} Tickets Remaining</small>
						@endif
					</h3>
				</div>
			</div>
			<div class="col-xs-12 col-sm-9">
				<h4>{!! $nextEventLan->desc_short !!}</h4>
				<p>{!! $nextEventLan->desc_long !!}</p>
			</div>
			<div class="col-xs-12 col-sm-3">
				<h4>When:</h4>
				<h5>{{ date('dS', strtotime($nextEventLan->start)) }} - {{ date('dS', strtotime($nextEventLan->end)) }} {{ date('F', strtotime($nextEventLan->end)) }} {{ date('Y', strtotime($nextEventLan->end)) }}</h5>
				<h4>Where:</h4>
				<h5>{{ $nextEventLan->venue->display_name }}</h5>
				@if ($nextEventLan->tickets)
					<h4>Price:</h4>
					<h5>Tickets Start From {{ config('app.currency_symbol') }}{{ $nextEventLan->getCheapestTicket() }}</h5>
				@endif
			</div>
		@endif

		@if ($nextEventTabletop)
			@if ($nextEventLan)
		</hr>
			@endif
			<div class="col-xs-12">
				<div class="page-header">
					<h3>
						{{ $nextEventTabletop->display_name }}
						@if (count($nextEventTabletop->seatingPlans) > 0)
							<small>{{ max($nextEventTabletop->getSeatingCapacity() - $nextEventTabletop->eventParticipants->count(), 0) }} / {{ $nextEventTabletop->getSeatingCapacity() }} Seats Remaining</small>
						@else 
							<small>{{ max($nextEventTabletop->capacity - $nextEventTabletop->eventParticipants->count(), 0) }} / {{ $nextEventTabletop->capacity }} Tickets Remaining</small>
						@endif
					</h3>
				</div>
			</div>
			<div class="col-xs-12 col-sm-9">
				<h4>{!! $nextEventTabletop->desc_short !!}</h4>
				<p>{!! $nextEventTabletop->desc_long !!}</p>
			</div>
			<div class="col-xs-12 col-sm-3">
				<h4>When:</h4>
				<h5>{{ date('dS', strtotime($nextEventTabletop->start)) }} - {{ date('dS', strtotime($nextEventTabletop->end)) }} {{ date('F', strtotime($nextEventTabletop->end)) }} {{ date('Y', strtotime($nextEventTabletop->end)) }}</h5>
				<h4>Where:</h4>
				<h5>{{ $nextEventTabletop->venue->display_name }}</h5>
				@if ($nextEventTabletop->tickets)
					<h4>Price:</h4>
					<h5>Tickets Start From {{ config('app.currency_symbol') }}{{ $nextEventTabletop->getCheapestTicket() }}</h5>
				@endif
			</div>
		@endif

	</div>
</div>
<div class="book-now  text-center hidden-xs">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				@if ($nextEvent)
					<h3>Want to get in on the action <a href="/events/{{ $nextEvent->slug }}" class="text-info">Book Now</a></h3>
				@else
					<h3>Events Coming soon!</h3>
				@endif
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
			<div class="page-header">
				<h3>About {{ config('app.name') }}</h3>
			</div>
			@include ('layouts._partials._about.short')
		</div>
		<div class="hidden-xs hidden-sm hidden-md col-lg-4">
			<div class="page-header">
				<h3>Event Calendar</h3>
			</div>
			@if ( count($events) > 0 )
				<table class="table table-borderless">
					<tbody>
						@foreach ( $events as $event )
							@if ($event->start > \Carbon\Carbon::today() )
								<tr>
									<td>
										<a href="/events/{{ $event->slug }}">
											{{ $event->display_name }}
											@if ($event->status != 'PUBLISHED')
												- {{ $event->status }}
											@endif
										</a>
									</td>
									<td>
										<span class="pull-right">
											{{ date('dS', strtotime($event->start)) }} - {{ date('dS', strtotime($event->end)) }} {{ date('F', strtotime($event->end)) }} {{ date('Y', strtotime($event->end)) }}
										</span>
									</td>
								</tr>
							@endif
						@endforeach
					</tbody>
				</table>
			@else
				<div>Coming soon...</div>
			@endif
		</div>


		<div class="col-xs-12 col-sm-12">
			<div class="page-header">
				<h3>Latest News</h3>
			</div>
			@if (!$newsArticles->isEmpty())
				@foreach ($newsArticles as $newsArticle)
					@include ('layouts._partials._news.short')
				@endforeach
			@else
				<p>Nothing to see here...</p>
			@endif
		</div>
	</div>
</div>

<div class="about  section-padding  section-margin hidden">
	<div class="container">
		<div class="row">
			<div class="col-md-8  col-md-offset-2 text-center">
				<div class="text-center">
					<h2 class="section-heading  text-center">All About {{ config('app.name') }}</h2>
				</div>
				@include ('layouts._partials._about.short')
			</div>
		</div>
	</div>
</div>

@endsection
