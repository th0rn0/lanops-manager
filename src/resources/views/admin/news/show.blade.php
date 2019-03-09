@extends ('layouts.admin-default')

@section ('page_title', 'News')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">News</h1>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/news/">News</a>
			</li>
			<li class="active">
				{{ $news_article->title }}
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-pencil fa-fw"></i> Edit {{ $news_article->title }}
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/news/' . $news_article->slug, 'files' => 'true')) }}
					<div class="form-group">
						{{ Form::label('title','Title',array('id'=>'','class'=>'')) }}
						{{ Form::text('title', $news_article->title ,array('id'=>'title','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('article','Article',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('article', $news_article->article, array('id'=>'','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<div class="form-group">
						{{ Form::label('tags','Tags',array('id'=>'','class'=>'')) }}<small> - Separate with a comma</small>
						{{ Form::text('tags', $news_article->getTags(), array('id'=>'', 'class'=>'form-control')) }}
					</div>
					<button type="submit" class="btn btn-default btn-block">Submit</button> 
				{{ Form::close() }}
				<hr>
				{{ Form::open(array('url'=>'/admin/news/' . $news_article->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
					{{ Form::hidden('_method', 'DELETE') }}
					<button type="submit" class="btn btn-danger btn-block">Delete</button>
				{{ Form::close() }}
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> Stats
			</div>
			<div class="panel-body">
				<!-- // TODO -->
				To do
			</div>  
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-comments fa-fw"></i> Comments
			</div>
			<div class="panel-body">
				@foreach ($news_article->comments->reverse() as $comment)
					@include ('layouts._partials._news.comment-warnings')
					@include ('layouts._partials._news.comment')
				@endforeach
			</div>  
		</div>
	</div>
</div>
 
@endsection