<div class="link-unstyled card event-card card-hover mb-3">
    <a href="/matchmaking/{{ $match->id }}" class="link-unstyled">
        <div class="card-header">
            @if ($match->game && $match->game->image_thumbnail_path)
                <picture>
                    <source srcset="{{ $match->game->image_thumbnail_path }}.webp" type="image/webp">
                    <source srcset="{{ $match->game->image_thumbnail_path }}" type="image/jpeg">
                    <img class="img img-fluid rounded" style="max-height: 212px;" src="{{ $match->game->image_thumbnail_path }}" alt="{{ $match->game->name }}">
                </picture>
            @endif
            <h3>@lang('matchmaking.match'){{ $match->id }}</h3>
            <span class="small">
                @if (isset($match->matchReplays) && count($match->matchReplays) > 0)
                    <span class="badge text-bg-primary">@lang('matchmaking.replayavailable')</span>
                @endif
                @if ($match->status == 'COMPLETE')
                    <span class="badge text-bg-success">@lang('matchmaking.ended')</span>
                @endif
                @if ($match->status == 'LIVE')
                    <span class="badge text-bg-success">@lang('matchmaking.live')</span>
                @endif
                @if ($match->status == 'PENDING')
                    <span class="badge text-bg-light">@lang('matchmaking.pending')</span>
                @endif
                @if ($match->status == 'WAITFORPLAYERS')
                    <span class="badge text-bg-light">@lang('matchmaking.waitforplayers')</span>
                @endif
                @if ($match->status == 'DRAFT')
                    <span class="badge text-bg-success">@lang('matchmaking.draft')</span>
                @endif
                @if ($match->status != 'COMPLETE' && !$match->getMatchTeamPlayer(Auth::id()))
                    <span class="badge text-bg-danger">@lang('matchmaking.notsignedup')</span>
                @endif
                @if ($match->status != 'COMPLETE' && $match->getMatchTeamPlayer(Auth::id()))
                    <span class="badge text-bg-success">@lang('matchmaking.signedup')</span>
                @endif
                @if ( $match->owner_id == Auth::id())
                <span class="badge text-bg-info">@lang('matchmaking.matchowner')</span>
                @endif
                @if ( $match->getMatchTeamOwner(Auth::id()))
                <span class="badge text-bg-info">@lang('matchmaking.teamowner')</span>
                @endif
            </span>
        </div>
        <div class="card-body">
            @if ($match->status != 'COMPLETE' && !($match->status == 'LIVE' && $match->game->matchmaking_autoapi))
                <div>
                    <strong>@lang('matchmaking.teamsizes'): {{ $match->team_size }}</strong>
                </div>
                @if ($match->game)
                    <div>
                        <strong>@lang('matchmaking.game'): {{ $match->game->name }}</strong>
                    </div>
                @endif
                <div>
                    <strong>@lang('matchmaking.teamcount'): {{ $match->teams->count() }}</strong>
                </div>
            @endif
            @if ($match->status == 'COMPLETE' || ($match->status == 'LIVE' && $match->game->matchmaking_autoapi))
                @php
                    $teams = $match->teams;
                    $teams = $teams->sortByDesc('team_score')->take(3);
                @endphp
                @foreach ($teams as $teamsentry)
                    <h5 class="m-0">{{ $loop->iteration }} - {{ $teamsentry->name }} - {{ $teamsentry->team_score }}</h5>
                @endforeach
                <strong class="m-0 mt-1">
                    @lang('matchmaking.teamcount'): {{ $match->teams->count() }}
                </strong>
                @if ($match->status == 'COMPLETE')
                    <h5 class="m-0 mt-1">@lang('matchmaking.ended')</h5>
                @else
                    <h5 class="m-0 mt-1">@lang('matchmaking.live')</h5>
                @endif
            @endif
        </div>
    </a>
@if (($match->status == 'LIVE' || $match->status == "WAITFORPLAYERS")  && isset($match->matchMakingServer) && isset($match->matchMakingServer->gameServer) )

    <div class="card-footer">
            <div>
                @php
                $availableParameters = new \stdClass();
                $availableParameters->game = $match->matchMakingServer->gameServer->game;
                $availableParameters->gameServer = $match->matchMakingServer->gameServer;
                @endphp
                        <h5>{{ $match->matchMakingServer->gameServer->name }}</h5>
                        <script>

                            document.addEventListener("DOMContentLoaded", function(event) {

                                $.get( '/games/{{ $match->matchMakingServer->gameServer->game->slug }}/gameservers/{{ $match->matchMakingServer->gameServer->slug }}/status', function( data ) {
                                    var serverStatus = JSON.parse(data);
                                    updateStatus('#serverstatus_{{ $match->matchMakingServer->gameServer->id }}_{{ $scope }}', serverStatus);
                                });
                                var start = new Date;

                                setInterval(function() {
                                    $.get( '/games/{{ $match->matchMakingServer->gameServer->game->slug }}/gameservers/{{ $match->matchMakingServer->gameServer->slug }}/status', function( data ) {
                                        var serverStatus = JSON.parse(data);
                                        updateStatus('#serverstatus_{{ $match->matchMakingServer->gameServer->id }}_{{ $scope }}', serverStatus);
                                    });
                                }, 30000);
                            });
                        </script>

                        <div class="mb-3" id="serverstatus_{{ $match->matchMakingServer->gameServer->id }}">
                            <div><i class="fas fa-map-marked-alt"></i><strong > Map: </strong><span id="serverstatus_{{ $match->matchMakingServer->gameServer->id }}_{{ $scope }}_map"></span></div>
                            <div><i class="fas fa-users"></i><span class ="ms-2"><strong class ="ms-2" > Players: </strong></span><span id="serverstatus_{{ $match->matchMakingServer->gameServer->id }}_{{ $scope }}_players"></span></div>
                        </div>
                        @if($match->matchMakingServer->gameServer->game->connect_stream_url && $match->matchMakingServer->gameServer->stream_port != 0)
                        <a class="btn btn-primary btn-block" href="{{ Helpers::resolveServerCommandParameters($match->matchMakingServer->gameServer->game->connect_stream_url, NULL, $availableParameters) }}" role="button">Join Stream</a>
                        @endif
                        @if($match->matchMakingServer->gameServer->game->connect_game_url && $match->getMatchTeamPlayer(Auth::id()))
                        <a class="btn btn-primary btn-block " id="connectGameUrl{{ $availableParameters->gameServer->id }}_{{ $scope }}" href="{{ Helpers::resolveServerCommandParameters($match->matchMakingServer->gameServer->game->connect_game_url, NULL, $availableParameters) }}" role="button">Join Game</a>
                        @endif
                        @if($match->matchMakingServer->gameServer->game->connect_game_command && $match->getMatchTeamPlayer(Auth::id()))
                            <div class="input-group mt-2">
                                <input class="form-control" id="connectGameCommand{{ $availableParameters->gameServer->id }}_{{ $scope }}" type="text" readonly value="{{ Helpers::resolveServerCommandParameters($match->matchMakingServer->gameServer->game->connect_game_command, NULL, $availableParameters) }}">
                                <span class="input-group-btn">
                                <button class="btn btn-primary" type="button" onclick="copyToClipBoard('connectGameCommand{{$availableParameters->gameServer->id}}_{{ $scope }}')"><i class="fas fa-external-link-alt"></i></button>
                            </div>
                        @endif
            </div>
        </div>
    @endif
</div>