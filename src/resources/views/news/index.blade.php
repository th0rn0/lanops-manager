@extends ('layouts.default')

@section ('page_title', config('app.name') . ' News')

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h1>
			News
		</h1> 
	</div>
	@foreach ($newsArticles as $newsArticle)
		@include ('layouts._partials._news.short')
	@endforeach
	{{ $newsArticles->links() }}
</div>

@endsection
