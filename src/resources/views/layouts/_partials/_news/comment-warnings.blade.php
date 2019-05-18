@if (Auth::user() && Auth::user()->getAdmin())
	@if (!$comment->approved && !$comment->reviewed)
		<div class="alert alert-warning">
			This comment has not been approved yet. Only Admins can see it.
			<span class="pull-right">
				<a href="/admin/news/{{ $newsArticle->slug }}/comments/{{ $comment->id }}/approve">Approve</a> / 
				<a href="/admin/news/{{ $newsArticle->slug }}/comments/{{ $comment->id }}/reject">Reject</a> / 
				<a href="/admin/news/{{ $newsArticle->slug }}/comments/{{ $comment->id }}/delete">Delete</a>
			</span>
		</div>
	@endif
	@if (!$comment->approved && $comment->reviewed)
		<div class="alert alert-danger">
			This comment has been Rejected! Only Admins can see it.
			<span class="pull-right">
				<a href="/admin/news/{{ $comment->newsArticle->slug }}/comments/{{ $comment->id }}/approve">Approve</a> / 
				<a href="/admin/news/{{ $comment->newsArticle->slug }}/comments/{{ $comment->id }}/delete">Delete</a>
			</span>
		</div>
	@endif
	@if ($comment->hasReports())
		<div class="alert alert-danger">
			This comment has been Reported.
			<span class="pull-right">
				<a href="/admin/news/">View</a>
			</span>
		</div>
	@endif
@endif
@if ((!$comment->approved && $comment->reviewed) && Auth::user() && Auth::id() == $comment->user_id)
	<div class="alert alert-danger">
		This comment Rejected by Admins.
		<span class="pull-right">
			<a href="/news/{{ $comment->newsArticle->slug }}/comments/{{ $comment->id }}/delete">Delete</a>
		</span>
	</div>
@endif
@if ((!$comment->approved && !$comment->reviewed) && Auth::user() && Auth::id() == $comment->user_id)
	<div class="alert alert-warning">
		This comment hasn't been reviewed by Admins yet.
		<span class="pull-right">
			<a href="/news/{{ $comment->newsArticle->slug }}/comments/{{ $comment->id }}/delete">Delete</a>
		</span>
	</div>
@endif