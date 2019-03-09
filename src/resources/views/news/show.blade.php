@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' ' . $news_article->title)

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h1>
			{{ $news_article->title }}
		</h1> 
	</div>
	@include ('layouts._partials._news.long')

	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-8">
			<div class="page-header">
				<h3>
					Comments
				</h3> 
			</div>
			@foreach ($news_article->comments->reverse() as $comment)
				@if (Auth::user() && Auth::user()->getAdmin())
					@if (!$comment->approved && !$comment->reviewed)
						<div class="alert alert-warning">This comment has not been approved yet. Only Admins can see it. APPROVE/DENY/DELETE BUTTON HERE</div>
					@endif
					@if (!$comment->approved && $comment->reviewed)
						<div class="alert alert-danger">
							This comment has not been approved! Only Admins can see it. DELETE BUTTON HERE
							@if (Auth::user() && Auth::user()->getAdmin())
								{{ Form::open(array('url'=>'/admin/news/' . $news_article->slug . '/comments/' . $comment->id)) }}
									{{ Form::hidden('_method', 'DELETE') }}
									<button type="submit" class="btn btn-sm btn-danger">Delete Comment</button>
								{{ Form::close() }}
							@endif
						</div>
					@endif
				@endif
				@include ('layouts._partials._news.comment')
				<hr>
				<br>
			@endforeach
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4">
			<div class="page-header">
				<h3>
					Post a Comment
				</h3> 
			</div>
			@if (Auth::user())
				{{ Form::open(array('url'=>'/news/' . $news_article->slug . '/comment')) }}
					<div class="form-group">
						{{ Form::textarea('comment', '',array('id'=>'comment','class'=>'form-control', 'rows'=>'4', 'placeholder'=>'Post a Comment')) }}
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
			@else
				<p>Please log in to post a Comment</p>
			@endif
		</div>
	</div>
</div>

@endsection
