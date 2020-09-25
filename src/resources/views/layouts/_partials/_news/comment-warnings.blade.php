@if (Auth::user() && Auth::user()->getAdmin())
	@if (!$comment->approved && !$comment->reviewed)
		<div class="alert alert-warning">
			@lang('layouts.comment_not_approved_yet')
			<span class="pull-right">
				<a href="/admin/news/{{ $newsArticle->slug }}/comments/{{ $comment->id }}/approve">@lang('layouts.comment_approve')</a> / 
				<a href="/admin/news/{{ $newsArticle->slug }}/comments/{{ $comment->id }}/reject">@lang('layouts.comment_reject')</a> / 
				<a href="/admin/news/{{ $newsArticle->slug }}/comments/{{ $comment->id }}/delete">@lang('layouts.comment_delete')</a>
			</span>
		</div>
	@endif
	@if (!$comment->approved && $comment->reviewed)
		<div class="alert alert-danger">
			@lang('layouts.comment_rejected')
			<span class="pull-right">
				<a href="/admin/news/{{ $comment->newsArticle->slug }}/comments/{{ $comment->id }}/approve">@lang('layouts.comment_approve')</a> / 
				<a href="/admin/news/{{ $comment->newsArticle->slug }}/comments/{{ $comment->id }}/delete">@lang('layouts.comment_delete')</a>
			</span>
		</div>
	@endif
	@if ($comment->hasReports())
		<div class="alert alert-danger">
			@lang('layouts.comment_reported')
			<span class="pull-right">
				<a href="/admin/news/">@lang('layouts.comment_view')</a>
			</span>
		</div>
	@endif
@endif
@if ((!$comment->approved && $comment->reviewed) && Auth::user() && Auth::id() == $comment->user_id)
	<div class="alert alert-danger">
		@lang('layouts.comment_rejected_by_admins')
		<span class="pull-right">
			<a href="/news/{{ $comment->newsArticle->slug }}/comments/{{ $comment->id }}/delete">@lang('layouts.comment_delete')</a>
		</span>
	</div>
@endif
@if ((!$comment->approved && !$comment->reviewed) && Auth::user() && Auth::id() == $comment->user_id)
	<div class="alert alert-warning">
		@lang('layouts.comment_not_reviewed_yet')
		<span class="pull-right">
			<a href="/news/{{ $comment->newsArticle->slug }}/comments/{{ $comment->id }}/delete">@lang('layouts.comment_delete')</a>
		</span>
	</div>
@endif