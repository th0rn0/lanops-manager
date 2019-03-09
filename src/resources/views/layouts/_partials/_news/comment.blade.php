<div class="row">
	<div class="col-xs-2">
		<img class="img-responsive img-rounded img-small news-post-comment-image" src="{{ $comment->user->avatar }}"/>
		<p class="news-post-comment-image-text">{{ $comment->user->steamname }}</p>
	</div>
	<div class="col-xs-10">
		<p>{{ $comment->comment }}</p>
		<span class="text-muted"><small>Posted on: {{ $comment->created_at }}</small></span>
		@if (Auth::user() && Auth::id() == $comment->user_id) 
			<a href="">Edit Comment</a> /
		@endif
		@if (Auth::user() && (Auth::user()->getAdmin() || $comment->user_id == Auth::id()))
			@php
				$post_url = "";
			@endphp
			@if (Auth::user()->getAdmin())
				@php
					$post_url = "/admin";
				@endphp
			@endif
			<a href="{{ $post_url }}/news/{{ $comment->newsArticle->slug }}/comments/{{ $comment->id }}/delete">
				Delete Comment
			</a>
		@endif
		@if ($comment->approved && $comment->reviewed)
			@if (Auth::user() && !$comment->reports->pluck('user_id')->contains(Auth::id()) && $comment->user_id != Auth::id())
				@if ((Auth::user() && (Auth::user()->getAdmin() || $comment->user_id == Auth::id())) || (Auth::user() && Auth::id() == $comment->user_id)) 
					/
				@endif
				<a href="/news/{{ $news_article->slug }}/comments/{{ $comment->id }}/report">
					Report Comment
				</a>
			@endif
		@endif
	</div>
</div>