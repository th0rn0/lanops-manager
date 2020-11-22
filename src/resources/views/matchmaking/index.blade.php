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
				@if(Settings::getSystemsMatchMakingMaxopenperuser() == 0 || count($currentUserOpenLivePendingDraftMatches) < Settings::getSystemsMatchMakingMaxopenperuser())
				<a href="/matchmaking/" class="btn btn-success btn-sm btn-block float-right" data-toggle="modal" data-target="#addMatchModal">Add Match</a>
				@endif
			</div>
		</div>
	</div>

	<!-- owned matches -->
	@if (!$ownedMatches->isEmpty())
		<div class="pb-2 mt-4 mb-4 border-bottom">
			<a name="ownedmatches"></a>
			<h3>@lang('matchmaking.ownedmatches')</h3>
		</div>
		<div class="card-deck">
			@foreach ($ownedMatches as $match)
				@include ('layouts._partials._matchmaking.card')
			@endforeach
		</div>
	@endif

	<!-- owned teams -->
	@if (!$ownedTeams->isEmpty())
		<div class="pb-2 mt-4 mb-4 border-bottom">
			<a name="ownedteams"></a>
			<h3>@lang('matchmaking.ownedteams')</h3>
		</div>
		<div class="card-deck">
			@foreach ($ownedTeams as $team)
				@php
					$match = $team->match;
				@endphp
				@include ('layouts._partials._matchmaking.card')
			@endforeach
		</div>
	@endif

		<!-- open public matches -->
		@if (!$openPublicMatches->isEmpty())
		<div class="pb-2 mt-4 mb-4 border-bottom">
			<a name="ownedmatches"></a>
			<h3>@lang('matchmaking.publicmatches')</h3>
		</div>
		<div class="card-deck">
			@foreach ($openPublicMatches as $match)
				@include ('layouts._partials._matchmaking.card')
			@endforeach
		</div>
	@endif

</div>


<!-- Modals -->

	<div class="modal fade" id="addMatchModal" tabindex="-1" role="dialog" aria-labelledby="addMatchModal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="addMatchModal">Add Match</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
					{{ Form::open(array('url'=>'/matchmaking/' )) }}
					<div class="form-group">
						{{ Form::label('game_id',__('matchmaking.game'),array('id'=>'','class'=>'')) }}
						{{
							Form::select(
								'game_id',
								Helpers::getMatchmakingGameSelectArray(),
								null,
								array(
									'id'    => 'game_id',
									'class' => 'form-control'
								)
							)
						}}
					</div>
					<div class="form-group">
						{{ Form::label('team1name',__('matchmaking.firstteamname'),array('id'=>'','class'=>'')) }}
						{{ Form::text('team1name',NULL,array('id'=>'team1name','class'=>'form-control')) }}
						<small>@lang('matchmaking.thisisyourteam')</small>
					</div>
					<div class="form-group">
						{{ Form::label('team_size',__('matchmaking.teamsize'),array('id'=>'','class'=>'')) }}
						{{
							Form::select(
								'team_size',
								array(
									'1v1' => '1v1',
									'2v2' => '2v2',
									'3v3' => '3v3',
									'4v4' => '4v4',
									'5v5' => '5v5',
									'6v6' => '6v6'
								),
								null,
								array(
									'id'    => 'team_size',
									'class' => 'form-control'
								)
							)
						}}
					</div>
					<div class="form-group">
						{{ Form::label('team_count',__('matchmaking.teamcounts'),array('id'=>'','class'=>'')) }}
						{{
							Form::number('team_count',
								2,
								array(
									'id'    => 'team_size',
									'class' => 'form-control'
								))
						}}
					</div>
					<div class="form-group">
						<div class="form-check">
								<label class="form-check-label">
									{{ Form::checkbox('ispublic', null, null, array('id'=>'ispublic')) }} is public (show match publicly for signup)
								</label>
						</div>
					</div>
					<button type="submit" class="btn btn-success btn-block">Submit</button>
				{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>


@endsection
