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
				@if ($comment->approved || (Auth::user() && Auth::user()->getAdmin()) || (Auth::user() && Auth::id() == $comment->user_id))
					@include ('layouts._partials._news.comment-warnings')
					@include ('layouts._partials._news.comment')
					@if (Auth::user() && $comment->reports->pluck('user_id')->contains(Auth::id()))
						<div class="alert alert-danger">
							You have Reported this comment!
						</div>
					@endif
					<hr>
					<br>
				@endif
			@endforeach
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4">
			<div class="page-header">
				<h3>
					Post a Comment
				</h3> 
			</div>
			@if (Auth::user())
				{{ Form::open(array('url'=>'/news/' . $news_article->slug . '/comments')) }}
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
