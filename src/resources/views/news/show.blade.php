@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' Events List')

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h1>
			{{ $news_article->title }}
		</h1> 
	</div>
	<div class="news-post">
		<!-- // TODO - add user account public pages -->
		{!! $news_article->article !!}
		<hr>
		<p class="news-post-meta">{{ date('F d, Y', strtotime($news_article->created_at)) }} by <a href="#">{{ $news_article->user->steamname }}</a></p>
		<span class="pull-right">Comments, tags, share buttons</span>
	</div>
</div>

@endsection
