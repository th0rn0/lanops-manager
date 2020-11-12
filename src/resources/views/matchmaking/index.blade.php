@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - ' . __('matchmaking.matchmaking'))

@section ('content')

<div class="container">

	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h1>
		@lang('matchmaking.matchmaking')
		</h1>
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
						<div class="thumbnail">
							@if ($match->game && $match->game->image_thumbnail_path)
								<a href="/matchmaking/{{ $match->id }}">
									<img class="img img-fluid rounded" src=""{{ $match->game->image_thumbnail_path }}" alt="{{ $match->game->name }}">
								</a>
							@endif
							<div class="caption">
								<a href="/matchmaking/{{ $match->id }}"><h3>@lang('matchmaking.match'){{ $match->id }}</h3></a>
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
								<hr>
								@if ($match->status != 'COMPLETE')
									<dl>
										<dt>
											@lang('matchmaking.teamsizes'):
										</dt>
										<dd>
											{{ $match->team_size }}
										</dd>
										@if ($match->game)
											 <dt>
												@lang('matchmaking.teamsizes'):
											</dt>
											<dd>
												{{ $match->game->name }}
											</dd>
										@endif
										<dt>
											@lang('matchmaking.teamcount'):
										</dt>
										<dd>
											{{ $match->teams->count() }}
										</dd>
									</dl>
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

												<h4>{{ $loop->iteration }} - {{ $teamsentry->name }} - {{ $teamsentry->team_score }}</h4>

									@endforeach
									<h4>@lang('matchmaking.signupsclosed')</h4>
								@endif
								@if ($match->status == 'COMPLETE')
									<strong>
										@lang('matchmaking.teamcount') {{ $match->teams->count() }}
									</strong>
								@endif
							</div>
						</div>
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
						<div class="thumbnail">
							@if ($team->match->game && $team->match->game->image_thumbnail_path)
								<a href="/matchmaking/{{ $team->match->id }}">
									<img class="img img-fluid rounded" src=""{{ $team->match->game->image_thumbnail_path }}" alt="{{ $team->match->game->name }}">
								</a>
							@endif
							<div class="caption">
								<a href="/matchmaking/{{ $team->match->id }}"><h3>@lang('matchmaking.match'){{ $team->match->id }}</h3></a>
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
								<hr>
								@if ($team->match->status != 'COMPLETE')
									<dl>
										<dt>
											@lang('matchmaking.teamsizes'):
										</dt>
										<dd>
											{{ $team->match->team_size }}
										</dd>
										@if ($team->match->game)
											 <dt>
												@lang('matchmaking.teamsizes'):
											</dt>
											<dd>
												{{ $team->match->game->name }}
											</dd>
										@endif
										<dt>
											@lang('matchmaking.teamcount'):
										</dt>
										<dd>
											{{ $team->match->teams->count() }}
										</dd>
									</dl>
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

												<h4>{{ $loop->iteration }} - {{ $teamsentry->name }} - {{ $teamsentry->team_score }}</h4>

									@endforeach
									<h4>@lang('matchmaking.signupsclosed')</h4>
								@endif
								@if ($team->match->status == 'COMPLETE')
									<strong>
										@lang('matchmaking.teamcount') {{ $team->match->teams->count() }}
									</strong>
								@endif
							</div>
						</div>
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
						<div class="thumbnail">
							@if ($match->game && $match->game->image_thumbnail_path)
								<a href="/matchmaking/{{ $match->id }}">
									<img class="img img-fluid rounded" src=""{{ $match->game->image_thumbnail_path }}" alt="{{ $match->game->name }}">
								</a>
							@endif
							<div class="caption">
								<a href="/matchmaking/{{ $match->id }}"><h3>@lang('matchmaking.match'){{ $match->id }}</h3></a>
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
								<hr>
								@if ($match->status != 'COMPLETE')
									<dl>
										<dt>
											@lang('matchmaking.teamsizes'):
										</dt>
										<dd>
											{{ $match->team_size }}
										</dd>
										@if ($match->game)
											 <dt>
												@lang('matchmaking.teamsizes'):
											</dt>
											<dd>
												{{ $match->game->name }}
											</dd>
										@endif
										<dt>
											@lang('matchmaking.teamcount'):
										</dt>
										<dd>
											{{ $match->teams->count() }}
										</dd>
									</dl>
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

												<h4>{{ $loop->iteration }} - {{ $teamsentry->name }} - {{ $teamsentry->team_score }}</h4>

									@endforeach
									<h4>@lang('matchmaking.signupsclosed')</h4>
								@endif
								@if ($match->status == 'COMPLETE')
									<strong>
										@lang('matchmaking.teamcount') {{ $match->teams->count() }}
									</strong>
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
