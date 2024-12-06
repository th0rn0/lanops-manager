@extends ('layouts.default')

@section ('page_title', config('app.name') . ' Tournaments')

@section ('content')
			
<div class="container">
	<div class="page-header">
		<h1>
			Tournaments
		</h1> 
	</div>
	@foreach ($activeTournaments as $tournament)
        @include('layouts._partials._tournaments.index')
	@endforeach
	<hr>
	<div class="page-header">
		<h3>
			Previous Tournaments
		</h3> 
	</div>
	@foreach ($completedTournaments as $tournament)
        @include('layouts._partials._tournaments.index')
	@endforeach
	{{ $completedTournaments->links() }}
</div>

@endsection
