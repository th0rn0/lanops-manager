@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . __('news.news_tag') . $tag)

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h1>
			@lang('news.tagged_news') {{ $tag }}
		</h1> 
	</div>
	@foreach ($newsArticles as $newsArticle)
		@include ('layouts._partials._news.short')
	@endforeach
</div>

@endsection
