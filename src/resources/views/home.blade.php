@extends ('layouts.default')

@section ('content')

<div id="hero-carousel" class="carousel slide carousel-fade" data-ride="carousel" data-interval="5000">
	<!-- Wrapper for slides -->
	<div class="carousel-inner" role="listbox">
		<div class="item active">
			<img class="hero-image" src="/storage/images/main/slider/5.png">
		</div>
		<div class="item">
			<img class="hero-image" src="/storage/images/main/slider/3.png">
		</div>
		<div class="item">
			<img class="hero-image" src="/storage/images/main/slider/2.png">
		</div>
		<div class="item">
			<img class="hero-image" src="/storage/images/main/slider/1.png">
		</div>
		<div class="item">
			<img class="hero-image" src="/storage/images/main/slider/4.png">
		</div>
	</div>
	<div class="hero-overlay hidden-xs">
			@if ($next_event)
				<h3>Next Event</h3>
				<h1>{{ $next_event->display_name }}</h1>
				<h5>{{ date('dS', strtotime($next_event->start)) }} - {{ date('dS', strtotime($next_event->end)) }} {{ date('F', strtotime($next_event->end)) }} {{ date('Y', strtotime($next_event->end)) }}</h5>
				<a href="/events/{{ $next_event->slug }}#information"><button class="btn btn-primary">More Info</button></a>
				<a href="/events/{{ $next_event->slug }}#tickets"><button class="btn btn-primary">Book Now</button></a>
			@endif
		</div>
	</div>
	
</div>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div class="page-header">
				<h3>About {{ Settings::getOrgName() }}</h3>
			</div>
			<p>{!! Settings::getAboutShort() !!}</p>
		</div>
		@if ($next_event)
			<div class="col-xs-12">
				<div class="page-header">
					<h3>
						Next Event: {{ $next_event->display_name }} 
						<small>{{ max($next_event->getSeatingCapacity() - $next_event->eventParticipants->count(), 0) }} / {{ $next_event->getSeatingCapacity() }} Seats Remaining</small>
					</h3>
				</div>
			</div>
			<div class="col-xs-12 col-sm-9">
				<h4>{{ $next_event->desc_short }}</h4>
				<p>{{ $next_event->desc_long }}</p>
			</div>
			<div class="col-xs-12 col-sm-3">
				<h4>When:</h4>
				<h5>{{ date('dS', strtotime($next_event->start)) }} - {{ date('dS', strtotime($next_event->end)) }} {{ date('F', strtotime($next_event->end)) }} {{ date('Y', strtotime($next_event->end)) }}</h5>
				<h4>Where:</h4>
				<h5>{{ $next_event->venue->display_name }}</h5>
				@if ($next_event->tickets)
					<h4>Price:</h4>
					<h5>Tickets Start From £{{ $next_event->getCheapestTicket() }}</h5>
				@endif
			</div>
		@else
			<div class="col-xs-12">
				<h2>No New Events</h2>
			</div>
		@endif
	</div>
</div>
<div class="book-now  text-center hidden-xs">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				@if ($next_event)
					<h3>Want to get in on the action <a href="/events/{{ $next_event->slug }}" class="text-info">Book Now</a></h3>
				@else
					<h3>No New Events</h3>
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

			@foreach ($news_articles as $news_article)
				<div class="news-post">
					<h2 class="news-post-title">{{ $news_article->title }}</h2>
					<!-- // TODO - add user account public pages -->
					<p class="news-post-meta">{{ date('F d, Y', strtotime($news_article->created_at)) }} by <a href="#">{{ $news_article->user->steamname }}</a></p>
					{!! $news_article->article !!}
				</div>
			@endforeach

		</div>
		<div class="col-xs-12 col-sm-3">
			<div class="page-header">
				<h3>The {{ Settings::getOrgName() }} Fam</h3>
			</div>
			@if (count($top_attendees) > 0)
				<div class="page-header">
					<h5>Top 5 Attendees</h5>
				</div>
				@foreach ($top_attendees as $attendee)
					<div class="row">
						<div class="col-xs-12 col-sm-3">
							<img class="img-rounded img-responsive" src="{{ $attendee->avatar }}">
						</div>
						<div class="col-xs-12 col-sm-9">
							<p>
								{{ $attendee->steamname }}<br>
								<small> {{ $attendee->event_count }} Events Attended</small>
							</p>
						</div>
					</div>
				@endforeach
			@endif
			@if (count($top_winners) > 0)
				<div class="page-header">
					<h5>Top 5 Winners</h5>
				</div>
				@foreach ($top_winners as $winner)
					<div class="row">
						<div class="col-xs-12 col-sm-3">
							<img class="img-rounded img-responsive" src="{{ $winner->avatar }}">
						</div>
						<div class="col-xs-12 col-sm-9">
							<p>
								{{ $winner->steamname }}<br>
								<small> {{ $winner->win_count }} Wins</small>
							</p>
						</div>
					</div>
				@endforeach
			@endif
		</div>
	</div>
</div>

<div class="about  section-padding  section-margin hidden">
	<div class="container">
		<div class="row">
			<div class="col-md-8  col-md-offset-2 text-center">
				<div class="text-center">
					<h2 class="section-heading  text-center">All About {{ Settings::getOrgName() }}</h2>
				</div>
				{!! Settings::getAboutShort() !!}
			</div>
		</div>
	</div>
</div>

@endsection
