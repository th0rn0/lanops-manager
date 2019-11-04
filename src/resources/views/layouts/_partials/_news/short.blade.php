<div class="news-post">
	<h2 class="news-post-title"><a href="/news/{{ $newsArticle->slug }}">{{ $newsArticle->title }}</a></h2>
	<br>
	{!! strip_tags(substr($newsArticle->article, strpos($newsArticle->article, "<p"), strpos($newsArticle->article, "</p>")+4)) !!}
	<br><br>
	<p><a href="/news/{{ $newsArticle->slug }}">Read More...</a></p>
	<hr>
	<div class="row">
		<div class="col-xs-12 col-sm-6">
			<div class="row">
				<div class="col-xs-12">
					Share:
					<a href="https://www.facebook.com/sharer/sharer.php?u={{ url('/news') }}/{{ $newsArticle->slug }}&t={{ $newsArticle->title }}" target="_blank">
						<img class="img img-responsive img-rounded news-post-share-button" src="/storage/images/main/social/facebook.png">
					</a>
					<a href="http://twitter.com/share?text={{ $newsArticle->title }}&url=http://{{ url('/news') }}/{{ $newsArticle->slug }}&hashtags={{ $newsArticle->getTags(',') }}" target="_blank">
						<img class="img img-responsive img-rounded news-post-share-button" src="/storage/images/main/social/twitter.png">
					</a>
				</div>
				<div class="col-xs-12">
					Tags:
					@foreach ($newsArticle->tags as $tag)
						<small><a href="{{ url('/news/tags')}}/{{ $tag->slug }}">{{ $tag->tag }}</a>,</small>
					@endforeach
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-6">
			<!-- // TODO - add user account public pages -->
			<p class="news-post-meta pull-right">{{ date('F d, Y', strtotime($newsArticle->created_at)) }} by <a href="#">{{ $newsArticle->user->username }}</a><span class="hidden-xs"> | Comments: {{ $newsArticle->comments->count() }}</span></p>
		</div>
	</div>
</div><br>