<div class="row">
	<div class="col-xs-2">
		<img class="img-responsive img-rounded img-small news-post-comment-image" src="{{ $comment->user->avatar }}"/>
		<p class="news-post-comment-image-text">{{ $comment->user->steamname }}</p>
	</div>
	<div class="col-xs-10">
		<p>{{ $comment->comment }}</p>
		<span class="text-muted"><small>Posted on: {{ $comment->created_at }}</small></span>
		@if (Auth::user() && Auth::user()->getAdmin())
			{{ Form::open(array('url'=>'/admin/news/' . $news_article->slug . '/comments/' . $comment->id)) }}
				{{ Form::hidden('_method', 'DELETE') }}
				<button type="submit" class="btn btn-sm btn-danger">Delete Comment</button>
			{{ Form::close() }}
		@endif
		@if (Auth::user() && Auth::id() == $comment->user_id) 
			<button type="submit" class="btn btn-sm btn-danger">Edit Comment</button>
		@endif
		@if (Auth::user())
			<button type="submit" class="btn btn-sm btn-danger">Report Comment</button>
		@endif
	</div>
</div>