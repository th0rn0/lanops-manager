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
					<button type="submit" class="btn btn-default btn-block">Submit</button> 
				{{ Form::close() }}
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
								<th>Posted By</th>
								<th>Edit</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($news as $news_item)
								<tr>
									<td>
										{{ $news_item->title }}
									</td>
									<td>
										{{ $news_item->user->steamname }}
										{{ date('dd-MM-yy', strtotime($news_item->created_at)) }}
									</td>
									<td>
										edit me
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