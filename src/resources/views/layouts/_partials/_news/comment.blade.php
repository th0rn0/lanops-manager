<div class="row">
	<div class="col-xs-2">
		<img class="img-responsive img-rounded img-small news-post-comment-image" src="{{ $comment->user->avatar }}"/>
		<p class="news-post-comment-image-text">{{ $comment->user->steamname }}</p>
	</div>
	<div class="col-xs-10">
		<p>{{ $comment->comment }}</p>
		<span class="text-muted"><small>Posted on: {{ $comment->created_at }}</small></span>
		@if (Auth::user() && Auth::id() == $comment->user_id) 
			<a href="" onclick="editComment('{{ $comment->comment }}', '{{ $comment->id }}')" data-toggle="modal" data-target="#editCommentModal">Edit Comment</a> /
		@endif
		@if (Auth::user() && (Auth::user()->getAdmin() || $comment->user_id == Auth::id()))
			@php
				$postUrl = "";
			@endphp
			@if (Auth::user()->getAdmin())
				@php
					$postUrl = "/admin";
				@endphp
			@endif
			<a href="{{ $postUrl }}/news/{{ $comment->newsArticle->slug }}/comments/{{ $comment->id }}/delete">
				Delete Comment
			</a>
		@endif
		@if ($comment->approved && $comment->reviewed)
			@if (Auth::user() && !$comment->reports->pluck('user_id')->contains(Auth::id()) && $comment->user_id != Auth::id())
				@if ((Auth::user() && (Auth::user()->getAdmin() || $comment->user_id == Auth::id())) || (Auth::user() && Auth::id() == $comment->user_id)) 
					/
				@endif
				<a href="/news/{{ $newsArticle->slug }}/comments/{{ $comment->id }}/report">
					Report Comment
				</a>
			@endif
		@endif
	</div>
</div>


<!-- Edit Comment Modal -->
<div class="modal fade" id="editCommentModal" tabindex="-1" role="dialog" aria-labelledby="editCommentModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="editCommentModalLabel">Edit Comment</h4>
			</div>
			@if (Auth::user())
				{{ Form::open(array('url'=>'/news/' . $newsArticle->slug . '/comments', 'id'=>'edit_comment_modal_form')) }}
					<div class="modal-body">
						<div class="form-group">
							{{ Form::textarea('comment_modal', '',array('id'=>'comment_modal','class'=>'form-control', 'rows'=>'4', 'placeholder'=>'Post a Comment')) }}
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-success">Edit</button>
						</div>
					</div>
				{{ Form::close() }}
			@else
				<div class="modal-body">
					<p>Please log in to post a Comment</p>
				</div>
			@endif
		</div>
	</div>
</div>

<script>
	function editComment(comment, comment_id)
	{
		console.log(comment_id);
		$("#comment_modal").val(comment);
		$("#edit_comment_modal_form").prop('action', '/news/{{ $newsArticle->slug }}/comments/' + comment_id);
	}
</script>