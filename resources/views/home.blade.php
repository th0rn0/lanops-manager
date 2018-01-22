@extends ('layouts.default')

@section ('content')

<div class="hero">
	
	<div class="hero-information" style="height:30%">

		<div class="container">
		
			@if ( count($events) >= 1)
				@foreach ( $events as $event )
					<div class="col-xs-12 col-sm-6 col-lg-4">
						<div class="hero-information__pre-title">
							Next LAN
						</div>
						<div class="hero-information__title">
							<h1>{{ $event->display_name }}</h1>
						</div>

						<div class="hero-information__from-date">
							<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Start Date: {{ date("d-m-Y H:i", strtotime($event->start)) }}				
						</div>

						<div class="hero-information__to-date">
							<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> End Date: {{ date("d-m-Y H:i", strtotime($event->end)) }}
						</div>
						<a href="/events/{{$event->slug}}#purchaseTickets">
							<button type="button" class="btn btn-primary btn-lg">
			        			Book Now!
			        		</button>
			      		</a>
					</div>
					<div class="col-xs-12 col-sm-6 col-lg-4 col-lg-push-4 hidden-xs">
						<div class="hero-information__pre-title">
							<h1>2018 Dates</h1>
						</div>
						<br>
						<div class="hero-information__from-date">
							<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> March 16th - 18th				
						</div>
						<div class="hero-information__from-date">
							<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> May 18th - 20th		
						</div>
						<div class="hero-information__from-date">
							<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> September 7th - 9th				
						</div>
						<div class="hero-information__from-date">
							<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> November 9th - 11th			
						</div>
					</div>
				@endforeach
			@else
				<div class="hero-information__title">
					<h2>There are currently no events.</h2>
				</div>
			@endif
		</div>
	</div>
</div>
<div class="book-now  text-center hidden-xs">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				@foreach ( $events as $event )
					<h3>Want to get in on the action <a href="/events/{{$event->slug}}" class="text-info">Book Now</a></h3>
				@endforeach
			</div>
		</div>
	</div>
</div>

<div class="stats  section-padding  section-margin">
	<div class="container">
		<div class="row">
			<div class="col-md-4  text-center">
				<div class="stats-number">
					{{ Helpers::getEventTotal() }}
				</div>
				<hr />
				<div class="stats-title">
					LANs we've hosted
				</div>
			</div>

			<div class="col-md-4  text-center">
				<div class="stats-number">
					{{ Helpers::getEventParticipantTotal() }}
				</div>
				<hr />
				<div class="stats-title">
					GAMERs we've entertained
				</div>
			</div>

			<div class="col-md-4  text-center">
				<div class="stats-number">
					A LOT
				</div>
				<hr />
				<div class="stats-title">
					PIZZAs we've ordered
				</div>
			</div>
		</div>
	</div>
</div>

<div class="about  section-padding  section-margin">
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
