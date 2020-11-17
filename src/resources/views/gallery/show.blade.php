@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - ' . __('gallery.gallery'))

@section ('content')


<script src="/js/vendor.js"></script>
<link  href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet"> <!-- 3 KB -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script> <!-- 16 KB -->

<div class="container">
	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h1>{{ $album->name }}</h1>
		@if ($album->event)
			<h4>From {{ $album->event->display_name }}</h4>
		@endif
	</div>
	<div class="center-align fotorama" data-nav="thumbs" data-allowfullscreen="full">
		@foreach ($album->images as $image)
			@if ($image->filetype == 0)
				<picture>
					<source srcset="{{ $image->path  }}.webp" type="image/webp">
					<source srcset="{{ $image->path  }}" type="image/jpeg">
					<img src="{{ $image->path }}">
				</picture>
			@endif
		@endforeach
	</div>
	<div class="center-align margin" >
		@foreach ($album->images as $image)
			@if ($image->filetype != 0)
				<div class="row margin">
					<div class="col-sm-3">
						<a href ="{{ $image->path }}"><i class="fas fa-file-download fa-7x"></i></a>
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