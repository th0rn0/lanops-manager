@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - ' . __('matchmaking.matchmaking'))

@section ('content')

<div class="container">

	<div class="pb-2 mt-4 mb-4 border-bottom">
		<div class="row">
			<div class="col-sm">
				<h1>
				@lang('matchmaking.matchmaking')
				</h1>
			</div>
			<div class="col-sm mt-4">
				<a href="/matchmaking/" class="btn btn-success btn-sm btn-block float-right" data-toggle="modal" data-target="#addMatchModal">Add Match</a>
			</div>
		</div>
	</div>

	<!-- owned matches -->
	@if (!$ownedMatches->isEmpty())
		<div class="pb-2 mt-4 mb-4 border-bottom">
			<a name="ownedmatches"></a>
			<h3>@lang('matchmaking.ownedmatches')</h3>
		</div>
		<div class="row">
			@foreach ($ownedMatches as $match)				
					<div class="col-12 col-sm-6 col-md-3">
						<a href="/matchmaking/{{ $match->id }}" class="link-unstyled">
							<div class="card card-hover @if(Colors::isBodyDarkMode()) border-light @endif mb-3"">
								<div class="card-header @if(Colors::isBodyDarkMode()) border-light @endif">
								@if ($match->game && $match->game->image_thumbnail_path)
									
										<img class="img img-fluid rounded" src=""{{ $match->game->image_thumbnail_path }}" alt="{{ $match->game->name }}">
									
								@endif
								
									<h3>@lang('matchmaking.match'){{ $match->id }}</h3>
									<span class="small">
										@if ($match->status == 'COMPLETE')
											<span class="badge badge-success">@lang('matchmaking.ended')</span>
										@endif
										@if ($match->status == 'LIVE')
											<span class="badge badge-success">@lang('matchmaking.live')</span>
										@endif
										@if ($match->status != 'COMPLETE' && !$match->getMatchTeamPlayer(Auth::id()))
											<span class="badge badge-danger">@lang('matchmaking.notsignedup')</span>
										@endif
										@if ($match->status != 'COMPLETE' && $match->getMatchTeamPlayer(Auth::id()))
											<span class="badge badge-success">@lang('matchmaking.signedup')</span>
										@endif
										@if ( $match->owner_id == Auth::id())
										<span class="badge badge-success">@lang('matchmaking.matchowner')</span>
										@endif
										@if ( $match->getMatchTeamOwner(Auth::id()))
										<span class="badge badge-success">@lang('matchmaking.teamowner')</span>
										@endif
									</span>
								</div>
								<div class="card-body">									
									@if ($match->status != 'COMPLETE')
											<div>
												<strong>@lang('matchmaking.teamsizes') {{ $match->team_size }}</strong>
											</div>
											@if ($match->game)
												<div>
													<strong>@lang('matchmaking.game') {{ $match->game->name }}</strong>
												</div>
											@endif
											<div>
												<strong>@lang('matchmaking.teamcount') {{ $match->teams->count() }}</strong>
											</div>
									@endif
									<!-- // TODO - refactor-->
									@if ($match->status == 'COMPLETE' )
										@php

											$teams = $match->teams;
							
											if ($teams->count() < 3 )
											{
												$teams = $teams->sortByDesc('team_score')->take($teams->count());
											}
											else {
											$teams = $teams->sortByDesc('team_score')->take(3);
											}
										@endphp
										@foreach ($teams as $teamsentry)

													<h5 class="m-0">{{ $loop->iteration }} - {{ $teamsentry->name }} - {{ $teamsentry->team_score }}</h5>

										@endforeach
										<strong class="m-0 mt-1">
											@lang('matchmaking.teamcount') {{ $match->teams->count() }}
										</strong>
										<h5 class="m-0 mt-1">@lang('matchmaking.signupsclosed')</h5>
									
										
									@endif
								</div>
							</div>
						</a>
					</div>				
			@endforeach
		</div>
	@endif

	<!-- owned teams -->
	@if (!$ownedTeams->isEmpty())
		<div class="pb-2 mt-4 mb-4 border-bottom">
			<a name="ownedteams"></a>
			<h3>@lang('matchmaking.ownedteams')</h3>
		</div>
		<div class="row">
			@foreach ($ownedTeams as $team)				
					<div class="col-12 col-sm-6 col-md-3">
						<a href="/matchmaking/{{ $team->match->id }}" class="link-unstyled">

							<div class="card card-hover @if(Colors::isBodyDarkMode()) border-light @endif mb-3">
								<div class="card-header @if(Colors::isBodyDarkMode()) border-light @endif">
								@if ($team->match->game && $team->match->game->image_thumbnail_path)
										<img class="img img-fluid rounded" src=""{{ $team->match->game->image_thumbnail_path }}" alt="{{ $team->match->game->name }}">
								@endif
								
									<h3>@lang('matchmaking.match'){{ $team->match->id }}</h3>
									<span class="small">
										@if ($team->match->status == 'COMPLETE')
											<span class="badge badge-success">@lang('matchmaking.ended')</span>
										@endif
										@if ($team->match->status == 'LIVE')
											<span class="badge badge-success">@lang('matchmaking.live')</span>
										@endif
										@if ($team->match->status != 'COMPLETE' && !$team->match->getMatchTeamPlayer(Auth::id()))
											<span class="badge badge-danger">@lang('matchmaking.notsignedup')</span>
										@endif
										@if ($team->match->status != 'COMPLETE' && $team->match->getMatchTeamPlayer(Auth::id()))
											<span class="badge badge-success">@lang('matchmaking.signedup')</span>
										@endif
										@if ( $team->match->owner_id == Auth::id())
										<span class="badge badge-success">@lang('matchmaking.matchowner')</span>
										@endif
										@if ( $team->match->getMatchTeamOwner(Auth::id()))
										<span class="badge badge-success">@lang('matchmaking.teamowner')</span>
										@endif
									</span>
								</div>
								<div class="card-body">	
									@if ($team->match->status != 'COMPLETE')
											<div>
												<strong>@lang('matchmaking.teamsizes') {{ $team->match->team_size }}</strong>
											</div>
											@if ($team->match->game)
												<div>
													<strong>@lang('matchmaking.game') {{ $team->match->game->name }}</strong>
												</div>
											@endif
											<div>
												<strong>@lang('matchmaking.teamcount'){{ $team->match->teams->count() }}</strong>
											</div>
									@endif
									<!-- // TODO - refactor-->
									@if ($team->match->status == 'COMPLETE' )
										@php

											$teams = $team->match->teams;
							
											if ($teams->count() < 3 )
											{
												$teams = $teams->sortByDesc('team_score')->take($teams->count());
											}
											else {
											$teams = $teams->sortByDesc('team_score')->take(3);
											}
										@endphp
										@foreach ($teams as $teamsentry)

													<h5 class="m-0">{{ $loop->iteration }} - {{ $teamsentry->name }} - {{ $teamsentry->team_score }}</h5>

										@endforeach
										<strong class="m-0 mt-1">
											@lang('matchmaking.teamcount') {{ $team->match->teams->count() }}
										</strong>
										<h5 class="m-0 mt-1">@lang('matchmaking.signupsclosed')</h5>
									
									
									@endif
								</div>
							</div>
						</a>
					</div>				
			@endforeach
		</div>
	@endif

		<!-- owned matches -->
		@if (!$ownedMatches->isEmpty())
		<div class="pb-2 mt-4 mb-4 border-bottom">
			<a name="ownedmatches"></a>
			<h3>@lang('matchmaking.publicmatches')</h3>
		</div>
		<div class="row">
			@foreach ($openPublicMatches as $match)				
					<div class="col-12 col-sm-6 col-md-3">
						<a href="/matchmaking/{{ $match->id }}" class="link-unstyled">

							<div class="card card-hover @if(Colors::isBodyDarkMode()) border-light @endif mb-3">
								<div class="card-header @if(Colors::isBodyDarkMode()) border-light @endif">
							@if ($match->game && $match->game->image_thumbnail_path)
									<img class="img img-fluid rounded" src=""{{ $match->game->image_thumbnail_path }}" alt="{{ $match->game->name }}">
							@endif
								<h3>@lang('matchmaking.match'){{ $match->id }}</h3>
								<span class="small">
									@if ($match->status == 'COMPLETE')
										<span class="badge badge-success">@lang('matchmaking.ended')</span>
									@endif
									@if ($match->status == 'LIVE')
										<span class="badge badge-success">@lang('matchmaking.live')</span>
									@endif
									@if ($match->status != 'COMPLETE' && !$match->getMatchTeamPlayer(Auth::id()))
										<span class="badge badge-danger">@lang('matchmaking.notsignedup')</span>
									@endif
									@if ($match->status != 'COMPLETE' && $match->getMatchTeamPlayer(Auth::id()))
										<span class="badge badge-success">@lang('matchmaking.signedup')</span>
									@endif
									@if ( $match->owner_id == Auth::id())
									<span class="badge badge-success">@lang('matchmaking.matchowner')</span>
									@endif
									@if ( $match->getMatchTeamOwner(Auth::id()))
									<span class="badge badge-success">@lang('matchmaking.teamowner')</span>
									@endif
								</span>
								</div>
								<div class="card-body">
								@if ($match->status != 'COMPLETE')
										<div>
											<strong>@lang('matchmaking.teamsizes') {{ $match->team_size }}</strong>
										</div>
										@if ($match->game)
											 <div>
												<strong>@lang('matchmaking.game') {{ $match->game->name }}</strong>
											</div>
										@endif
										<div>
											<strong>@lang('matchmaking.teamcount') {{ $match->teams->count() }}</strong>
										</div>
								@endif
								<!-- // TODO - refactor-->
								@if ($match->status == 'COMPLETE' )
									@php

										$teams = $match->teams;
						
										if ($teams->count() < 3 )
										{
											$teams = $teams->sortByDesc('team_score')->take($teams->count());
										}
										else {
										$teams = $teams->sortByDesc('team_score')->take(3);
										}
									@endphp
									@foreach ($teams as $teamsentry)

												<h5 class="m-0">{{ $loop->iteration }} - {{ $teamsentry->name }} - {{ $teamsentry->team_score }}</h5>

									@endforeach
									<strong class="m-0 mt-1">
										@lang('matchmaking.teamcount') {{ $match->teams->count() }}
									</strong>
									<h5 class="m-0 mt-1">@lang('matchmaking.signupsclosed')</h5>


								@endif
							</div>
						</div>
					</div>				
			@endforeach
		</div>
	@endif


	<hr>

</div>

@endsection
