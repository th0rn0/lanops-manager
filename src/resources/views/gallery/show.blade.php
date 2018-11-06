@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' Gallery')

@section ('content')


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<link  href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet"> <!-- 3 KB -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script> <!-- 16 KB -->

<div class="container">
	<div class="page-header">
		<h1>{{ $album->name }}</h1>
		@if ($album->event)
			<h4>From {{ $album->event->display_name }}</h4>
		@endif
	</div>
	<div class="fotorama" data-nav="thumbs" data-allowfullscreen="full">
		@foreach ($album->images as $image)
			<img src="{{ $image->path }}">
		@endforeach
	</div>
</div>

@endsection