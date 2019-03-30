@extends ('layouts.admin-default')

@section ('page_title', 'News')

@section ('content')
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">News</h1>
		<ol class="breadcrumb">
			<li class="active">
				News
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-plus fa-fw"></i> Post News
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/news/', 'files' => 'true')) }}
					<div class="form-group">
						{{ Form::label('title','Title',array('id'=>'','class'=>'')) }}
						{{ Form::text('title', NULL ,array('id'=>'title','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('article','Article',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('article', NULL,array('id'=>'','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<div class="form-group">
						{{ Form::label('tags','Tags',array('id'=>'','class'=>'')) }}<small> - Separate with a comma</small>
						{{ Form::text('tags', '', array('id'=>'', 'class'=>'form-control')) }}
					</div>
					@if ($facebook_linked)
						<div class="form-group">
							{{ Form::checkbox('post_to_facebook', true, array('id'=>'post_to_facebook','class'=>'form-control')) }} Post to facebook?
						</div>
					@endif
					<button type="submit" class="btn btn-default btn-block">Submit</button> 
				{{ Form::close() }}
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-comments fa-fw"></i> Comments
			</div>
			<div class="panel-body">
				<p>To Approve:</p>
				@if (!$comments_to_approve->isEmpty())
					@foreach ($comments_to_approve->reverse() as $comment)
						@if (!$comment->reviewed && !$comment->approved)
							<div class="alert alert-warning">
								{{ $comment->comment }}
								<span class="pull-right">
									<small>{{ $comment->user->steamname }} </small>
									<a href="/admin/news/{{ $comment->newsArticle->slug }}/comments/{{ $comment->id }}/approve">Approve</a> / 
									<a href="/admin/news/{{ $comment->newsArticle->slug }}/comments/{{ $comment->id }}/reject">Reject</a>
								</span>
							</div>
						@endif
					@endforeach
				@else
					<div class="alert alert-success">
						No Comments to Approve!
					</div>
				@endif
				<p>Reported:</p>
				@if (!$comments_reported->isEmpty())
					@foreach ($comments_reported->reverse() as $report)
						<div class="alert alert-danger">
							{{ $report->newsComment->comment }} - Reported by: {{ $report->user->steamname }}
							<span class="pull-right">
								<a href="/admin/news/{{ $report->newsComment->newsArticle->slug }}/comments/{{ $report->newsComment->id }}/reports/{{ $report->id }}/delete">Ignore</a> / 
								<a href="/admin/news/{{ $report->newsComment->newsArticle->slug }}/comments/{{ $report->newsComment->id }}/delete">Delete</a>
							</span>
						</div>
					@endforeach
				@else
					<div class="alert alert-success">
						No Reported Comments!
					</div>
				@endif
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> News
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>Title</th>
								<th>By</th>
								<th>Date</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($news_articles->reverse() as $news_article)
								<tr>
									<td>
										{{ $news_article->title }}
									</td>
									<td>
										{{ $news_article->user->steamname }}
									</td>
									<td>
										{{ $news_article->created_at }}
									</td>
									<td width="15%">
										<a href="/admin/news/{{ $news_article->slug }}"><button type="button" class="btn btn-primary btn-sm btn-block">Edit</button></a>
									</td>
									<td width="15%">
										{{ Form::open(array('url'=>'/admin/news/' . $news_article->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
											{{ Form::hidden('_method', 'DELETE') }}
											<button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
										{{ Form::close() }}
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>  
		</div>
		
	</div>
</div>
 
@endsection