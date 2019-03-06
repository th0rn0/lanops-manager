@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' Events List')

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h1>
			{{ $news_article->title }}
		</h1> 
	</div>
	@include ('layouts._partials._news.long')
</div>

@endsection
