@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - ' . __('matchmaking.matchmaking'))

@section ('content')

<div class="container">

	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h1>
		@lang('matchmaking.matchmaking')
		</h1>
	</div>
	@foreach ($matches as $match)
		{{ $match->id }} 
	@endforeach
	<hr>
	@foreach ($ownedMatches as $match)
	{{ $match->id }} 
@endforeach
</div>

@endsection
