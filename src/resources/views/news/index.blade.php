@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' News')

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h1>
			News
		</h1> 
	</div>
	@foreach ($news_articles as $news_article)
		@include ('layouts._partials._news.short')
	@endforeach
</div>

@endsection