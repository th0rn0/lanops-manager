@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - ' . __('gallery.gallery'))

@section ('content')

<div class="container">

	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h1>@lang('gallery.gallery')</h1>
	</div>
	<div class="row card-deck">
    @foreach ($albums as $album)
        <div class="align-items-stretch mb-4 col-sm-4 col-12 custom-gallery-col">
            <div class="card h-100 d-flex flex-column">
				<div class="image-container" style="height: 150px; overflow: hidden;">
                	<a href="/gallery/{{ $album->slug }}">
                	    @if (isset($album->album_cover_id) && trim($album->album_cover_id) != '')
                	        <picture class="mb-0">
                	            <source srcset="{{ $album->getAlbumCoverPath() }}.webp" type="image/webp">
                	            <source srcset="{{ $album->getAlbumCoverPath() }}" type="image/jpeg">
                	            <img src="{{ $album->getAlbumCoverPath() }}" class="card-img-top img-fluid img-thumbnail mb-0"/>
                	        </picture>
                	    @else
                	        <img src="http://placehold.it/600x300" class="card-img-top img-fluid img-thumbnail mb-0"/>
                	    @endif
                	</a>
				</div>
                <div class="card-body">
                    <h4 class="card-title">{{ $album->name }}</h4>
					<div class="card-subtitle mb-2 text-muted" style="min-height: 1.5em;">
                        @if ($album->event)
                            @lang('gallery.event') {{ $album->event->display_name }}
                        @endif
                    </div>
                    <p class="card-text">{{ $album->description }}</p>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    	<a href="/gallery/{{ $album->slug }}" class="btn btn-primary">@lang('gallery.view_album')</a>
						<span class="text-muted">@lang('gallery.date') {{ $album->created_at->format('d M Y') }}</span>
					</div>
            </div>
        </div>
    @endforeach
</div>




</div>

@endsection
