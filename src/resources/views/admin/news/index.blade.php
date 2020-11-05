@extends ('layouts.admin-default')

@section ('page_title', 'News')

@section ('content')
<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">News</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">
				News
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-12 col-sm-6">
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-plus fa-fw"></i> Post News
			</div>
			<div class="card-body">
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
					@if ($facebookLinked)
						<div class="form-group">
							{{ Form::checkbox('post_to_facebook', null, true, array('id'=>'post_to_facebook')) }} Post to facebook?
						</div>
					@endif
					<button type="submit" class="btn btn-success btn-block">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-comments fa-fw"></i> Comments
			</div>
			<div class="card-body">
				<p>To Approve:</p>
				@if (!$commentsToApprove->isEmpty())
					@foreach ($commentsToApprove->reverse() as $comment)
						@if (!$comment->reviewed && !$comment->approved)
							<div class="alert alert-warning">
								{{ $comment->comment }}
								<span class="float-right">
									<small>{{ $comment->user->username }} </small>
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
				@if (!$commentsReported->isEmpty())
					@foreach ($commentsReported->reverse() as $report)
						<div class="alert alert-danger">
							{{ $report->newsComment->comment }} - Reported by: {{ $report->user->username }}
							<span class="float-right">
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
	<div class="col-12 col-sm-6">
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-users fa-fw"></i> News
			</div>
			<div class="card-body">
				<table class="table table-striped table-hover table-responsive">
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
						@foreach ($newsArticles->reverse() as $newsArticle)
							<tr>
								<td>
									{{ $newsArticle->title }}
								</td>
								<td>
									{{ $newsArticle->user->username }}
								</td>
								<td>
									{{ $newsArticle->created_at }}
								</td>
								<td width="15%">
									<a href="/admin/news/{{ $newsArticle->slug }}"><button type="button" class="btn btn-primary btn-sm btn-block">Edit</button></a>
								</td>
								<td width="15%">
									{{ Form::open(array('url'=>'/admin/news/' . $newsArticle->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
										{{ Form::hidden('_method', 'DELETE') }}
										<button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
									{{ Form::close() }}
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				{{ $newsArticles->links() }}
			</div>
		</div>

	</div>
</div>

@endsection