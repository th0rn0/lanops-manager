@extends ('layouts.default')

@section ('content')

<div class="hero" hidden>
	
	<div class="hero-information" style="height:30%">

		<div class="container">
		
			
		</div>
	</div>

</div>

<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
	<!-- Indicators -->
	<ol class="carousel-indicators">
		<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
		<li data-target="#carousel-example-generic" data-slide-to="1"></li>
	</ol>

	<!-- Wrapper for slides -->
	<div class="carousel-inner" role="listbox">
		<div class="item active">
			<img src="https://placehold.it/1920x400" alt="...">
			<div class="carousel-caption">
				Next Lan
		  	</div>
		</div>
		<div class="item">
			<img src="https://placehold.it/1920x400" alt="...">
			<div class="carousel-caption">
				...
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<h2>
				Next Event: {{ $nextEvent->display_name }} 
				<small>{{ max($nextEvent->getSeatingCapacity() - $nextEvent->eventParticipants->count(), 0) }} / {{ $nextEvent->getSeatingCapacity() }} Seats Remaining</small>
			</h2>
		</div>
		<div class="col-xs-12 col-sm-7">
			<h4>{{ $nextEvent->desc_short }}</h4>
			<p>{{ $nextEvent->desc_long }}</p>
		</div>
		<div class="col-xs-12 col-sm-5">
			<h4>When:</h4>
			<h5>{{ date('dS', strtotime($nextEvent->start)) }} - {{ date('dS', strtotime($nextEvent->end)) }} {{ date('F', strtotime($nextEvent->end)) }} {{ date('Y', strtotime($nextEvent->end)) }}</h5>
			<h4>Where:</h4>
			<h5>{{ $nextEvent->venue->display_name }}</h5>
			<p></p>
		</div>
	</div>
</div>
<div class="book-now  text-center hidden-xs">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h3>Want to get in on the action <a href="/events/{{ $nextEvent->slug }}" class="text-info">Book Now</a></h3>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-9">
			Latest News
		</div>
		<div class="col-xs-12 col-sm-3">
			asdasd
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
