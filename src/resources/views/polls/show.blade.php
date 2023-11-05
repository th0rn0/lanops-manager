@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' ' . $poll->name)

@section ('content')

<div class="container pt-1">

	<div class="pb-2 mt-4 mb-4 border-bottom">
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
