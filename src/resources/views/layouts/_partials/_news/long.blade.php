<div class="news-post">
	{!! $newsArticle->article !!}
	<hr>
	<div class="row">
		<div class="col-xs-12 col-sm-8">
			<div class="row">
				<div class="col-xs-2">
					Share:
				</div>
				<div class="col-xs-10">
					<a href="https://www.facebook.com/sharer/sharer.php?u={{ url('/news') }}/{{ $newsArticle->slug }}&t={{ $newsArticle->title }}" target="_blank">
						<img class="img img-responsive img-rounded news-post-share-button" src="/storage/images/main/social/facebook.png">
					</a>
					<a href="http://twitter.com/share?text={{ $newsArticle->title }}&url=http://{{ url('/news') }}/{{ $newsArticle->slug }}&hashtags={{ $newsArticle->getTags(',') }}" target="_blank">
						<img class="img img-responsive img-rounded news-post-share-button" src="/storage/images/main/social/twitter.png">
					</a>
				</div>
				<div class="col-xs-2">
					Tags:
				</div>
				<div class="col-xs-10">
					@foreach ($newsArticle->tags as $tag)
						<a href="{{ url('/news/tags')}}/{{ $tag->slug }}">{{ $tag->tag }}</a>,
					@endforeach
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-4">
			<!-- // TODO - add user account public pages -->
			<p class="news-post-meta pull-right">{{ date('F d, Y', strtotime($newsArticle->created_at)) }} by <a href="#">{{ $newsArticle->user->steamname }}</a></p>
		</div>
	</div>
</div>