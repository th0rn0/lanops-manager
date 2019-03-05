<div class="news-post">
	<h2 class="news-post-title"><a href="/news/{{ $news_article->slug }}">{{ $news_article->title }}</a></h2>
	<!-- // TODO - add user account public pages -->
	<p class="news-post-meta">{{ date('F d, Y', strtotime($news_article->created_at)) }} by <a href="#">{{ $news_article->user->steamname }}</a></p>
	{!! $news_article->article !!}
	<a href="/news/{{ $news_article->slug }}">Read More...</a>
</div>