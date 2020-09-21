@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - ' . __('news.news'))

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h1>
		@lang('news.news')
		</h1> 
	</div>
	@foreach ($newsArticles as $newsArticle)
		@include ('layouts._partials._news.short')
	@endforeach
	{{ $newsArticles->links() }}
</div>

@endsection
