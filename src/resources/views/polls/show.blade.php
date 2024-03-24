@extends ('layouts.default')

@section ('page_title', config('app.name') . ' ' . $poll->name)

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h1>
			{{ $poll->name }}
			@if ($poll->status != 'PUBLISHED')
				<small> - {{ $poll->status }}</small>
			@endif
			@if ($poll->hasEnded())
				<small> - Voting has Ended</small>
			@endif
		</h1>
		@if (!empty($poll->description))
			<p>{{ $poll->description }}</p>
		@endif
	</div>
	@include ('layouts._partials._polls.votes')
</div>

@endsection
