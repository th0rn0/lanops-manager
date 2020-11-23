@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - ' . $event->display_name)

@section ('content')

<div class="container">

	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h1>Welcome to {{ $event->display_name }}!</h1>
	</div>
	<div class="text-center">
		<nav class="navbar navbar-dark bg-dark navbar-expand-md navbar-events" style="z-index: 1;">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggler collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="navbar-toggler-icon"></span>
					</button>
				</div>
				<div id="navbar" class="navbar-collapse collapse" style="text-align:center;">
					<ul class="navbar-nav" style="display: inline-block; float: none;">
						<!--<li style="font-size:15px; font-weight:bold;"><a href="#food">Food Orders</a></li>-->
						<li class="nav-item" style="font-size:15px; font-weight:bold;"><a class="nav-link" href="#event">@lang('events.eventinfo')</a></li>
						<li class="nav-item" style="font-size:15px; font-weight:bold;"><a class="nav-link" href="#server">@lang('events.server')</a></li>
						<li class="nav-item" style="font-size:15px; font-weight:bold;"><a class="nav-link" href="#seating">@lang('events.seating')</a></li>
						<li class="nav-item" style="font-size:15px; font-weight:bold;"><a class="nav-link" href="#attendees">@lang('events.attendees')</a></li>
						@if (!$event->tournaments->isEmpty())
							<li class="nav-item" style="font-size:15px; font-weight:bold;"><a class="nav-link" href="#tournaments">@lang('events.tournaments')</a></li>
						@endif
						<li class="nav-item" style="font-size:15px; font-weight:bold;"><a class="nav-link" href="#information">@lang('events.essentialinfo')</a></li>
					</ul>
				</div>
			</div>
		</nav>
	</div>
	<!-- SIGN IN TO EVENT -->
	@if (!$signedIn)
		@lang('events.plssignin')
	@endif

	<!-- EVENT SPONSORS -->
	@if (!$event->sponsors->isEmpty())
		<div class="pb-2 mt-4 mb-4 border-bottom">
			<a name="sponsors"></a>
			<h3>@lang('events.eventsponsoredby', ['event', $event->display_name])</h3>
		</div>
		@foreach ($event->sponsors as $sponsor)
			<a href="{{$sponsor->website}}">
				<picture>
					<source srcset="{{ $sponsor->image_path }}.webp" type="image/webp">
					<source srcset="{{ $sponsor->image_path }}" type="image/jpeg">
					<img alt="{{ $sponsor->website}}" class="img-fluid rounded" src="{{ $sponsor->image_path }}"/>
				</picture>
			</a>
		@endforeach
	@endif

	<!-- ESSENTIAL INFORMATION -->
	<div class="row">
		<div class="col-lg-6 col-md-6 col-12">
			<div class="pb-2 mt-4 mb-4 border-bottom">
				<a name="information"></a>
				<h3>@lang('events.essentialinfo')</h3>
			</div>
			{!! $event->event_live_info !!}
		</div>
		<div class="col-lg-6 col-md-6 col-12">
			<div class="pb-2 mt-4 mb-4 border-bottom">
				<a name="announcements"></a>
				<h3>@lang('events.announcements')</h3>
			</div>
			@if ($event->announcements->isEmpty())
				<div class="alert alert-info"><strong>@lang('events.noannouncements')</strong></div>
			@else
				@foreach ($event->announcements as $announcement)
					<div class="alert alert-info">{{ $announcement->message }}</div>
				@endforeach
			@endif
		</div>
	</div>

	<!-- TIMETABLE -->
	@if (!$event->timetables->isEmpty())
		<div class="pb-2 mt-4 mb-4 border-bottom">
			<a name="timetable"></a>
			<h3>@lang('events.timetable')</h3>
		</div>
		@foreach ($event->timetables as $timetable)
			@if (strtoupper($timetable->status) == 'DRAFT')
				<h4>DRAFT</h4>
			@endif
			<h4>{{ $timetable->name }}</h4>
			<table class="table table-striped">
				<thead>
					<th>
						@lang('events.time')
					</th>
					<th>
						@lang('events.game')
					</th>
					<th>
						@lang('events.description')
					</th>
				</thead>
				<tbody>
					@foreach ($timetable->data as $slot)
						@if ($slot->name != NULL)
							<tr>
								<td>
									{{ date("D", strtotime($slot->start_time)) }} - {{ date("H:i", strtotime($slot->start_time)) }}
								</td>
								<td>
									{{ $slot->name }}
								</td>
								<td>
									@if ($slot->desc != NULL)
										{{ $slot->desc }}
									@endif
								</td>
							</tr>
						@endif
					@endforeach
				</tbody>
			</table>
		@endforeach
	@endif

	<!-- Server -->
	@if(Settings::getTeamspeakLink() != "" || Settings::getMumbleLink() != "" || Settings::getFacebookLink() != "" || Settings::getDiscordLink() != "" || Settings::getSteamLink() != "" || Settings::getRedditLink() != "" || Settings::getTwitterLink() != "" || !empty($gameServerList))
		<div class="pb-2 mt-4 mb-4 border-bottom">
			<a name="server"></a>
			<h3>@lang('events.server')</h3>
		</div>
		<div class="row">
					@if (Settings::getTeamspeakLink() != "")
						<div class="col-6 col-lg-4">
							<a href="ts3server://{{ Settings::getTeamspeakLink() }}?nickname={{ $user->username }}" ><i class="fab fa-teamspeak fa-2x margin"></i>@lang('home.servers_teamspeak')</a>
						</div>
					@endif

					@if (Settings::getMumbleLink() != "")
						<div class="col-6 col-lg-4">
							<a href="mumble://{{ $user->username }}{{ chr(64) }}{{ Settings::getMumbleLink() }}" width="100%" >
								<img class="margin" src="https://www.mumble.info/css/mumble.svg" alt="Mumble Logo" width="28" height="28">@lang('home.servers_mumble')
							</a>
						</div>
					@endif
					@if (Settings::getFacebookLink() != "")
						<div class="col-6 col-lg-4">
							<a target="_blank" rel="noreferrer" href="{{ Settings::getFacebookLink() }}"><i class="fab fa-facebook fa-2x margin"></i>@lang('home.servers_facebook')</a>
						</div>
					@endif
					@if (Settings::getDiscordLink() != "")
						<div class="col-6 col-lg-4">
							<a target="_blank" rel="noreferrer" href="{{ Settings::getDiscordLink() }}"><i class="fab fa-discord fa-2x margin"></i>@lang('home.servers_discord')</a>
						</div>
					@endif
					@if (Settings::getSteamLink() != "")
						<div class="col-6 col-lg-4">
							<a target="_blank" rel="noreferrer" href="{{ Settings::getSteamLink() }}"><i class="fab fa-steam fa-2x margin"></i>@lang('home.servers_steam')</a>
						</div>
					@endif
					@if (Settings::getRedditLink() != "")
					<div class="col-6 col-lg-4">
						<a target="_blank" rel="noreferrer" href="{{ Settings::getRedditLink() }}"><i class="fab fa-reddit fa-2x margin"></i>@lang('home.servers_reddit')</a>
					</div>
					@endif
					@if (Settings::getTwitterLink() != "")
					<div class="col-6 col-lg-4">
						<a target="_blank" rel="noreferrer" href="{{ Settings::getTwitterLink() }}"><i class="fab fa-twitter fa-2x margin"></i>@lang('home.servers_twitter')</a>
					</div>
					@endif
		</div>

			@if ( !empty($gameServerList) )
				<script>
					function updateStatus(id ,serverStatus){

						if(serverStatus.info == false)
						{
							jQuery(id + "_map").html( "-" );
							jQuery(id + "_players").html( "-" );
						}else
						{
							jQuery(id + "_map").html( serverStatus.info.Map );
							jQuery(id + "_players").html( serverStatus.info.Players );
						}
					}
				</script>
				<div class="row top30">
					@foreach ($gameServerList as $game => $gameServers)
						<div class="col-12 col-sm-6 col-md-4">
							<div class="card mb-3">
								<div class="card-header ">
									<div class="row text-center block-center">
										<picture>
											<source srcset="{{ $gameServers[0]->game->image_thumbnail_path }}.webp" type="image/webp">
											<source srcset="{{ $gameServers[0]->game->image_thumbnail_path }}" type="image/jpeg">
											<img src="{{ $gameServers[0]->game->image_thumbnail_path }}" class="img img-fluid rounded img-contain margin-top margin-bottom width=100em" height="100em">
										</picture>
										<strong class="margin">{{ $gameServers[0]->game->name }}</strong>
									</div>
								</div>
								<div class="card-body">
											@php
												$counter = 0;
											@endphp
											@foreach ($gameServers as $gameServer)
												@php
												$availableParameters = new \stdClass();
												$availableParameters->game = $gameServer->game;
												$availableParameters->gameServer = $gameServer;
												$counter++;
												@endphp
														@if ($counter > 1)
															<hr>
														@endif

														<h3>#{{$counter}} - {{ $gameServer->name }}</h3>
														<script>

															document.addEventListener("DOMContentLoaded", function(event) {

																$.get( '/games/{{ $gameServer->game->slug }}/gameservers/{{ $gameServer->slug }}/status', function( data ) {
																	var serverStatus = JSON.parse(data);
																	updateStatus('#serverstatus_{{ $gameServer->id }}', serverStatus);
																});
																var start = new Date;

																setInterval(function() {
																	$.get( '/games/{{ $gameServer->game->slug }}/gameservers/{{ $gameServer->slug }}/status', function( data ) {
																		var serverStatus = JSON.parse(data);
																		updateStatus('#serverstatus_{{ $gameServer->id }}', serverStatus);
																	});
																}, 30000);
															});
														</script>
														<div id="serverstatus_{{ $gameServer->id }}">
															<div><i class="fas fa-map-marked-alt margin"></i><strong>Map: </strong><span id="serverstatus_{{ $gameServer->id }}_map"></span></div>
															<div><i class="fas fa-users margin"></i><strong>Players: </strong><span id="serverstatus_{{ $gameServer->id }}_players"></span></div>
														</div>
														@if($gameServer->game->connect_game_url)
														<a class="btn btn-primary btn-block" id="connectGameUrl" href="{{ Helpers::resolveServerCommandParameters($gameServer->game->connect_game_url, NULL, $availableParameters) }}" role="button">Join Game</a>
														@endif
														@if($gameServer->game->connect_game_command)
															<div class="input-group" style="width: 100%">
																<input class="form-control" id="connectGameCommand{{ $availableParameters->gameServer->id }}" type="text" readonly value="{{ Helpers::resolveServerCommandParameters($gameServer->game->connect_game_command, NULL, $availableParameters) }}">
																<span class="input-group-btn">
																<button class="btn btn-primary" type="button" onclick="copyToClipBoard('connectGameCommand{{$availableParameters->gameServer->id}}')"><i class="fas fa-external-link-alt"></i></button>
															</div>
														@endif
														@if($gameServer->game->connect_stream_url && $gameServer->stream_port != 0)
															<a class="btn btn-primary btn-block" href="{{ Helpers::resolveServerCommandParameters($gameServer->game->connect_stream_url, NULL, $availableParameters) }}" role="button">Join Stream</a>
														@endif
											@endforeach
								</div>
							</div>
						</div>
					@endforeach
				</div>

				<script>
					function copyToClipBoard(inputId) {
						/* Get the text field */
						var copyText = document.getElementById(inputId);

						/* Select the text field */
						copyText.select();
						copyText.setSelectionRange(0, 99999); /*For mobile devices*/

						/* Copy the text inside the text field */
						document.execCommand("copy");
					}
				</script>

			@endif
	@endif

	<!-- TOURNAMENTS -->
	@if (!$event->tournaments->isEmpty())
		<div class="pb-2 mt-4 mb-4 border-bottom">
			<a name="tournaments"></a>
			<h3>@lang('events.tournaments')</h3>
		</div>
		<div class="row">
			@foreach ($event->tournaments as $tournament)
				@if ($tournament->status != 'DRAFT')
					<div class="col-12 col-sm-6 col-md-3">
						<div class="thumbnail">
							@if ($tournament->game && $tournament->game->image_thumbnail_path)
								<a href="/events/{{ $event->slug }}/tournaments/{{ $tournament->slug }}">
									<picture>
										<source srcset="{{ $tournament->game->image_thumbnail_path }}.webp" type="image/webp">
										<source srcset="{{ $tournament->game->image_thumbnail_path }}" type="image/jpeg">
										<img class="img img-fluid rounded" src="{{ $tournament->game->image_thumbnail_path }}" alt="{{ $tournament->game->name }}">
									</picture>
								</a>
							@endif
							<div class="caption">
								<a href="/events/{{ $event->slug }}/tournaments/{{ $tournament->slug }}"><h3>{{ $tournament->name }}</h3></a>
								<span class="small">
									@if ($tournament->status == 'COMPLETE')
										<span class="badge badge-success">@lang('events.ended')</span>
									@endif
									@if ($tournament->status == 'LIVE')
										<span class="badge badge-success">@lang('events.live')</span>
									@endif
									@if ($tournament->status != 'COMPLETE' && !$tournament->getParticipant($user->active_event_participant->id))
										<span class="badge badge-danger">@lang('events.notsignedup')</span>
									@endif
									@if ($tournament->status != 'COMPLETE' && $tournament->getParticipant($user->active_event_participant->id))
										<span class="badge badge-success">@lang('events.signedup')</span>
									@endif
								</span>
								<hr>
								@if ($tournament->status != 'COMPLETE')
									<dl>
										<dt>
											@lang('events.teamsizes'):
										</dt>
										<dd>
											{{ $tournament->team_size }}
										</dd>
										@if ($tournament->game)
											 <dt>
												@lang('events.teamsizes'):
											</dt>
											<dd>
												{{ $tournament->game->name }}
											</dd>
										@endif
										<dt>
											@lang('events.format'):
										</dt>
										<dd>
											{{ $tournament->format }}
										</dd>
									</dl>
								@endif
								<!-- // TODO - refactor-->
								@if ($tournament->status == 'COMPLETE' && $tournament->format != 'list')
									@php
										if ($tournament->team_size != '1v1') {
											$tournamentParticipants = $tournament->tournamentTeams;
										}
										if ($tournament->team_size == '1v1') {
											$tournamentParticipants = $tournament->tournamentParticipants;
										}
										$tournamentParticipants = $tournamentParticipants->sortBy('final_rank');
									@endphp
									@foreach ($tournamentParticipants as $tournamentParticipant)
										@if ($tournamentParticipant->final_rank == 1)
											@if ($tournament->team_size == '1v1')
												<h2>{{ Helpers::getChallongeRankFormat($tournamentParticipant->final_rank) }} - {{ $tournamentParticipant->eventParticipant->user->username }}</h2>
											@else
												<h2>{{ Helpers::getChallongeRankFormat($tournamentParticipant->final_rank) }} - {{ $tournamentParticipant->name }}</h2>
											@endif
										@endif
										@if ($tournamentParticipant->final_rank == 2)
											@if ($tournament->team_size == '1v1')
												<h3>{{ Helpers::getChallongeRankFormat($tournamentParticipant->final_rank) }} - {{ $tournamentParticipant->eventParticipant->user->username }}</h3>
											@else
												<h3>{{ Helpers::getChallongeRankFormat($tournamentParticipant->final_rank) }} - {{ $tournamentParticipant->name }}</h3>
											@endif
										@endif
										@if ($tournamentParticipant->final_rank != 2 && $tournamentParticipant->final_rank != 1)
											@if ($tournament->team_size == '1v1')
												<h4>{{ Helpers::getChallongeRankFormat($tournamentParticipant->final_rank) }} - {{ $tournamentParticipant->eventParticipant->user->username }}</h4>
											@else
												<h4>{{ Helpers::getChallongeRankFormat($tournamentParticipant->final_rank) }} - {{ $tournamentParticipant->name }}</h4>
											@endif
										@endif
									@endforeach
									<h4>@lang('events.signupsclosed')</h4>
								@endif
								<strong>
									{{ $tournament->tournamentParticipants->count() }} @lang('events.signups')
								</strong>
							</div>
						</div>
					</div>
				@endif
			@endforeach
		</div>
	@endif

	<!-- ATTENDEES -->
	<div class="pb-2 mt-4 mb-4 border-bottom">
		<a name="attendees"></a>
		<h3>@lang('events.attendees')</h3>
	</div>
	<table class="table table-striped">
		<thead>
			<th width="7%">
			</th>
			<th>
				@lang('events.steamname')
			</th>
			<th>
				@lang('events.name')
			</th>
			<th>
				@lang('events.seat')
			</th>
		</thead>
		<tbody>
			@foreach ($event->eventParticipants as $participant)
			<tr>
				<td>
					<img class="img-fluid rounded" style="max-width: 70%;" alt="{{ $participant->user->username }}'s Avatar" src="{{ $participant->user->avatar }}">
				</td>
				<td style="vertical-align: middle;">
					{{ $participant->user->username }}
					@if ($participant->user->steamid)
						- <span class="text-muted"><small>Steam: {{ $participant->user->steamname }}</small></span>
					@endif
				</td>
				<td style="vertical-align: middle;">
					{{ $participant->user->firstname }}
				</td>
				<td style="vertical-align: middle;">
					@if ($participant->seat)
						{{ $participant->seat->seat }}
					@else
						@lang('events.notseated')
					@endif
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>

	<!-- SEATING -->
	@if (!$event->seatingPlans->isEmpty())
		<div class="pb-2 mt-4 mb-4 border-bottom">
			<a name="seating"></a>
			<h3>@lang('events.seatingplans') <small>- {{ $event->getSeatingCapacity() - $event->getSeatedCount() }} / {{ $event->getSeatingCapacity() }} @lang('events.seatsremaining')</small></h3>
		</div>
		<div class="card-group" id="accordion" role="tablist" aria-multiselectable="true">
			@foreach ($event->seatingPlans as $seatingPlan)
				<div class="card mb-3">
					<div class="card-header " role="tab" id="headingOne">
						<h4 class="card-title">
							<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_{{ $seatingPlan->slug }}" aria-expanded="true" aria-controls="collapse_{{ $seatingPlan->slug }}">
								{{ $seatingPlan->name }} <small>- @lang('events.numofseatedseats')</small>
							</a>
						</h4>
					</div>
					<div id="collapse_{{ $seatingPlan->slug }}" class="collapse" role="tabpanel" aria-labelledby="collaspe_{{ $seatingPlan->slug }}">
						<div class="card-body">
							<div class="text-center ">
								<table class="table table-responsive">
									<thead>
										<tr>
										<?php
											$headers = explode(',', $seatingPlan->headers);
											$headers = array_combine(range(1, count($headers)), $headers);
										?>
										@for ($column = 1; $column <= $seatingPlan->columns; $column++)
											<th class="text-center"><h4><strong>@lang('events.row') {{ucwords($headers[$column])}}</strong></h4></th>
										@endfor
										</tr>
									 </thead>
									<tbody>
										@for ($row = $seatingPlan->rows; $row > 0; $row--)
											<tr>
												@for ($column = 1; $column <= $seatingPlan->columns; $column++)
													<td style="padding-top:14px;">
														@if ($event->getSeat($seatingPlan->id, ucwords($headers[$column]) . $row))
															@if ($seatingPlan->locked)
																<button class="btn btn-success btn-sm" disabled>
																	{{ ucwords($headers[$column]) . $row }} - {{ $event->getSeat($seatingPlan->id, ucwords($headers[$column] . $row))->eventParticipant->user->username }}
																</button>
															@else
																<button class="btn btn-success btn-sm" disabled>
																	{{ ucwords($headers[$column]) . $row }} - {{ $event->getSeat($seatingPlan->id, ucwords($headers[$column] . $row))->eventParticipant->user->username }}
																</button>
															@endif
														@else
															@if ($seatingPlan->locked)
																<button class="btn btn-primary btn-sm" disabled>
																	{{ ucwords($headers[$column]) . $row }} - @lang('events.empty')
																</button>
															@else
																@if (Auth::user() && $event->getEventParticipant() && $event->getEventParticipant()->ticket->seatable)
																	<button
																		class="btn btn-primary btn-sm"
																		onclick="pickSeat(
																			'{{ $seatingPlan->id }}',
																			'{{ ucwords($headers[$column]) . $row }}'
																		)"
																		data-toggle="modal"
																		data-target="#pickSeatModal"
																	>
																		{{ ucwords($headers[$column]) . $row }} - @lang('events.empty')
																	</button>
																@else
																	<button class="btn btn-primary btn-sm" disabled>
																		{{ ucwords($headers[$column]) . $row }} - @lang('events.empty')
																	</button>
																@endif
															@endif
														@endif
													</td>
												@endfor
											</tr>
										@endfor
									</tbody>
								</table>
								@if ($seatingPlan->locked)
									<p class="text-center"><strong>@lang('events.seatingplanlocked')</strong></p>
								@endif
							</div>
							<hr>
							<div class="row" style="display: flex; align-items: center;">
								<div class="col-12 col-md-8">
									<picture>
										<source srcset="{{ $seatingPlan->image_path }}.webp" type="image/webp">
										<source srcset="{{ $seatingPlan->image_path }}" type="image/jpeg">
										<img class="img-fluid" src="{{ $seatingPlan->image_path }}"/>
									</picture>
								</div>
								<div class="col-12 col-md-4">
									@if ($ticketFlagSignedIn)
										<h5>@lang('events.yourseats')</h5>
										@foreach ($user->eventParticipation as $participant)
											@if ($participant->seat && $participant->seat->event_seating_plan_id == $seatingPlan->id)
												{{ Form::open(array('url'=>'/events/' . $event->slug . '/seating/' . $seatingPlan->slug)) }}
													{{ Form::hidden('_method', 'DELETE') }}
													{{ Form::hidden('user_id', $user->id, array('id'=>'user_id','class'=>'form-control')) }}
													{{ Form::hidden('participant_id', $participant->id, array('id'=>'participant_id','class'=>'form-control')) }}
													{{ Form::hidden('seat_number', $participant->seat->seat, array('id'=>'seat_number','class'=>'form-control')) }}
													<h5>
														<button class="btn btn-success btn-block">
														{{ $participant->seat->seat }} - @lang('events.remove')
														</button>
													</h5>
												{{ Form::close() }}
											@endif
										@endforeach
									@elseif(Auth::user())
										<div class="alert alert-info">
											<h5>@lang('events.plspurchaseticket')</h5>
										</div>
									@else
										<div class="alert alert-info">
											<h5>@lang('events.plslogintopurchaseticket')</h5>
										</div>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	@endif

	<!-- Image Uploader -->
	<div class="pb-2 mt-4 mb-4 border-bottom d-none">
		<a name="image_uploader"></a>
		<h3>@lang('events.imageuploader')</h3>
	</div>
	<div class="row d-none">
	</div>

</div>

@endsection