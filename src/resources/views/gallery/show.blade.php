@extends ('layouts.default')

@section ('page_title', config('app.name') . ' Gallery - ' . $album->name)

@section ('content')

<script src="https://rawcdn.githack.com/nextapps-de/spotlight/0.7.8/dist/spotlight.bundle.js"></script>

{{-- Album Header --}}
<div class="gallery-album-header">
    <div class="container">
        <a href="{{ url('/gallery') }}" class="gallery-album-back">&larr; All Albums</a>
        <h1 class="gallery-album-title">{{ $album->name }}</h1>
        @if ($album->event)
            <p class="gallery-album-meta">From {{ $album->event->display_name }}</p>
        @endif
    </div>
</div>

{{-- Photo Grid --}}
<div class="gallery-album-section">
    <div class="container">
        <div class="gallery-grid">
            @foreach ($album->getMedia('images') as $image)
                <div class="gallery-grid-item">
                    <a class="spotlight gallery-thumb" href="{{ $image->getUrl('optimized') }}">
                        <img src="{{ $image->getUrl('thumb') }}" alt="{{ $album->name }}">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
