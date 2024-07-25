@extends ('layouts.default')

@section ('page_title', config('app.name') . ' Gallery')

@section ('content')

<div class="container">

	<div class="page-header">
		<h1>Gallery</h1> 
	</div>
	<div class="row">
		@foreach ($albums as $album)
			<div class="well well-sm col-sm-3 col-xs-6">
				<h4>{{ $album->name }}</h4>
				@if ($album->event)
					<h5>Event: {{ $album->event->display_name }}</h5>
				@endif
				<p>{{ $album->description }}</p>
				<a href="/gallery/{{ $album->slug }}">
					<img src="{{ $album->getAlbumCoverImageUrl() }}" class="img img-responsive img-thumbnail"/>
				</a>
			</div>
		@endforeach
	</div>

</div>

@endsection
