@extends ('layouts.default')

@section ('content')

<div id="hero-carousel" class="carousel fade" data-ride="carousel" data-interval="8000">
	<!-- Wrapper for slides -->
	<div class="carousel-inner" role="listbox">
		@foreach ($sliderImages as $image)
			<div class="item @if ($loop->first) active @endif">
				<img class="hero-image" alt="{{ config('app.name') }} Banner" src="{{ $image->path }}">
			</div>
		@endforeach
	</div>
	<div class="hero-overlay hidden-xs">
			@if ($nextEvent)
				<div>
					<h3>Next Event</h3>
					<h1>{{ $nextEvent->display_name }}</h1>
					<h5>{{ date('dS', strtotime($nextEvent->start)) }} - {{ date('dS', strtotime($nextEvent->end)) }} {{ date('F', strtotime($nextEvent->end)) }} {{ date('Y', strtotime($nextEvent->end)) }}</h5>
					<a href="/events/{{ $nextEvent->slug }}#tickets"><button class="btn btn-orange btn-lg">Book Now</button></a>
				</div>
			@else
				<div>
					<h3>Next Event</h3>
					<h1>Coming soon</h1>
				</div>
			@endif
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
		@if ($nextEvent)
			<div class="col-xs-12">
				<div class="page-header">
					<h3>
						{{ $nextEvent->display_name }}
						@if (count($nextEvent->seatingPlans) > 0)
							<small>{{ max($nextEvent->getSeatingCapacity() - $nextEvent->eventParticipants->count(), 0) }} / {{ $nextEvent->getSeatingCapacity() }} Seats Remaining</small>
						@else 
							<small>{{ max($nextEvent->capacity - $nextEvent->eventParticipants->count(), 0) }} / {{ $nextEvent->capacity }} Tickets Remaining</small>
						@endif
					</h3>
				</div>
			</div>
			<div class="col-xs-12 col-sm-9">
				<h4>{!! $nextEvent->desc_short !!}</h4>
				<p>{!! $nextEvent->desc_long !!}</p>
			</div>
			<div class="col-xs-12 col-sm-3">
				<h4>When:</h4>
				<h5>{{ date('dS', strtotime($nextEvent->start)) }} - {{ date('dS', strtotime($nextEvent->end)) }} {{ date('F', strtotime($nextEvent->end)) }} {{ date('Y', strtotime($nextEvent->end)) }}</h5>
				<h4>Where:</h4>
				<h5>{{ $nextEvent->venue->display_name }}</h5>
				@if ($nextEvent->tickets)
					<h4>Price:</h4>
					<h5>Tickets Start From {{ config('app.currency_symbol') }}{{ $nextEvent->getCheapestTicket() }}</h5>
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
		<div class="col-xs-12 col-sm-9">
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
		<div class="col-xs-12 col-sm-3">
			<div class="page-header">
				<h3>The {{ config('app.name') }} Fam</h3>
			</div>
			@if (config('app.discord_id'))			
				<iframe class="hidden-md" src="https://discordapp.com/widget?id={{ config('app.discord_id') }}&theme=light" width="100%" height="1000" allowtransparency="true" frameborder="0"></iframe>
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
