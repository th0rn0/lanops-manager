@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - ' . __('gallery.gallery'))

@section ('content')

<div class="container">

	<div class="page-header">
		<h1>@lang('gallery.gallery')</h1> 
	</div>
	<div class="row">
		@foreach ($albums as $album)
			<div class="well well-sm col-sm-3 col-xs-6">
				<h4>{{ $album->name }}</h4>
				@if ($album->event)
					<h5>@lang('gallery.event') {{ $album->event->display_name }}</h5>
				@endif
				<p>{{ $album->description }}</p>
				<a href="/gallery/{{ $album->slug }}">
					@if (isset($album->album_cover_id) && trim($album->album_cover_id) != '')
						<img src="{{ $album->getAlbumCoverPath() }}" class="img img-responsive img-thumbnail"/>
					@else
						<img src="http://placehold.it/600x300" class="img img-responsive img-thumbnail"/>
					@endif
				</a>
			</div>
		@endforeach
	</div>

</div>

@endsection
