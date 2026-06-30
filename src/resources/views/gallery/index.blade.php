@extends ('layouts.default')

@section ('page_title', config('app.name') . ' Gallery')

@section ('content')

<div class="gallery-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="section-heading">Gallery</h1>
            </div>
        </div>
        @if ($albums->isEmpty())
            <p class="text-muted">No albums yet — check back after the next event.</p>
        @else
            <div class="row">
                @foreach ($albums as $album)
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="gallery-card">
                            <a href="/gallery/{{ $album->slug }}" class="gallery-card-img">
                                <img src="{{ $album->getAlbumCoverImageUrl() }}" alt="{{ $album->name }}">
                            </a>
                            <div class="gallery-card-body">
                                <h4 class="gallery-card-title">{{ $album->name }}</h4>
                                @if ($album->event)
                                    <p class="gallery-card-event">{{ $album->event->display_name }}</p>
                                @endif
                                @if ($album->description)
                                    <p class="gallery-card-desc">{{ $album->description }}</p>
                                @endif
                            </div>
                            <div class="gallery-card-foot">
                                <a href="/gallery/{{ $album->slug }}" class="btn btn-orange btn-sm">View Album</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection
