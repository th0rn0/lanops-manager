@extends ('layouts.default')

@section('page_title', Settings::getOrgName() . ' - ' . __('gallery.gallery'))

@include ('layouts._partials.slick_loader')

@section('content')


    <div class="container pt-1">
        <div class="pb-2 mt-4 mb-4 border-bottom">
            <h1>{{ $album->name }}</h1>

            @isset($album->event)
                <h4>From {{ $album->event->display_name }}</h4>
            @endisset

            @empty(!$album->description)
                <p>{{ $album->description }}</p>
            @endempty
        </div>

        <div class="center-align slider-for">
            @foreach ($album->images as $image)
                @if ($image->filetype == 0)
                    <picture>
                        <source srcset="{{ $image->path }}.webp" type="image/webp">
                        <source srcset="{{ $image->path }}" type="image/jpeg">
                        <img src="{{ $image->path }}" data-thumb="{{ $image->path }}"
                            alt="{{ $image->display_name ?? 'Image' }}" class="img-fluid">
                    </picture>
                @endif
            @endforeach
        </div>

        <div class="center-align margin">
            @foreach ($album->images as $image)
                @if ($image->filetype != 0)
                    <div class="row margin">
                        <div class="col-sm-3">
                            <a href="{{ $image->path }}"><i class="fas fa-file-download fa-7x"></i></a>
                        </div>
                        <div class="col-sm-3" style="display: flex; align-items: center;">
                            {{ $image->display_name }}
                        </div>
                        <div class="col-sm-6" style="display: flex; align-items: center;">
                            {{ $image->desc }}
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

@endsection
