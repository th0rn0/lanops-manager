@extends ('layouts.default')

@section ('page_title', config('app.name') . ' Gallery')

@section ('content')

<script src="https://rawcdn.githack.com/nextapps-de/spotlight/0.7.8/dist/spotlight.bundle.js"></script>

<div class="container">
	<div class="page-header">
		<h1>{{ $album->name }}</h1>
		@if ($album->event)
			<h4>From {{ $album->event->display_name }}</h4>
		@endif
	</div>
	<div class="center-align">
		<div class="row">
			@foreach ($album->getMedia('images') as $image)
				<div class="col-xs-6 col-md-3">
					<a class="spotlight thumbnail" href="{{ $image->getUrl('optimized') }}">
						<img src="{{ $image->getUrl('thumb') }}">
					</a>
				</div>
			@endforeach
		</div>
	</div>
</div>

@endsection