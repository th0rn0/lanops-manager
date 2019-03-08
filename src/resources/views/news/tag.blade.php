@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' News Tag: ' . $tag)

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h1>
			Tagged News: {{ $tag }}
		</h1> 
	</div>
	@foreach ($news_articles as $news_article)
		@include ('layouts._partials._news.short')
	@endforeach
</div>

@endsection
