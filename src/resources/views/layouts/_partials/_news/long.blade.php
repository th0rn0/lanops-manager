<div class="news-post">
	{!! $news_article->article !!}
	<hr>
	<div class="row">
		<div class="col-xs-12 col-sm-8">
			<div class="row">
				<div class="col-xs-2">
					Share:
				</div>
				<div class="col-xs-10">
					<a href="https://www.facebook.com/sharer/sharer.php?u={{ url('/news') }}/{{ $news_article->slug }}&t={{ $news_article->title }}" target="_blank">
						<img class="img img-responsive img-rounded news-post-share-button" src="/storage/images/main/social/facebook.png">
					</a>
					<a href="http://twitter.com/share?text={{ $news_article->title }}&url=http://{{ url('/news') }}/{{ $news_article->slug }}&hashtags={{ $news_article->getTags(',') }}" target="_blank">
						<img class="img img-responsive img-rounded news-post-share-button" src="/storage/images/main/social/twitter.png">
					</a>
				</div>
				<div class="col-xs-2">
					Tags:
				</div>
				<div class="col-xs-10">
					@foreach ($news_article->tags as $tag)
						<a href="{{ url('/news/tags')}}/{{ $tag->slug }}">{{ $tag->tag }}</a>,
					@endforeach
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-4">
			<!-- // TODO - add user account public pages -->
			<p class="news-post-meta pull-right">{{ date('F d, Y', strtotime($news_article->created_at)) }} by <a href="#">{{ $news_article->user->steamname }}</a></p>
		</div>
	</div>
</div>