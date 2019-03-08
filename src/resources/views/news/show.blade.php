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
					</div>
				</div>
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
