@extends ('layouts.default')

@section ('page_title', config('app.name') . ' News Tag: ' . $tag)

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h1>
			Tagged News: {{ $tag }}
		</h1> 
	</div>
	@foreach ($newsArticles as $newsArticle)
		@include ('layouts._partials._news.short')
	@endforeach
</div>

@endsection
