<a href="/matchmaking/{{ $match->id }}" class="link-unstyled card event-card card-hover mb-3">
    <div class="card-header">
        @if ($match->game && $match->game->image_thumbnail_path)
            <picture>
                <source srcset="{{ $match->game->image_thumbnail_path }}.webp" type="image/webp">
                <source srcset="{{ $match->game->image_thumbnail_path }}" type="image/jpeg">
                <img class="img img-fluid rounded" src="{{ $match->game->image_thumbnail_path }}" alt="{{ $match->game->name }}">
            </picture>
        @endif
        <h3>@lang('matchmaking.match'){{ $match->id }}</h3>
        <span class="small">
            @if ($match->status == 'COMPLETE')
                <span class="badge badge-success">@lang('matchmaking.ended')</span>
            @endif
            @if ($match->status == 'LIVE')
                <span class="badge badge-success">@lang('matchmaking.live')</span>
            @endif
            @if ($match->status == 'PENDING')
                <span class="badge badge-light">@lang('matchmaking.pending')</span>
            @endif
            @if ($match->status == 'WAITFORPLAYERS')
                <span class="badge badge-light">@lang('matchmaking.waitforplayers')</span>
            @endif
            @if ($match->status == 'DRAFT')
                <span class="badge badge-success">@lang('matchmaking.draft')</span>
            @endif
            @if ($match->status != 'COMPLETE' && !$match->getMatchTeamPlayer(Auth::id()))
                <span class="badge badge-danger">@lang('matchmaking.notsignedup')</span>
            @endif
            @if ($match->status != 'COMPLETE' && $match->getMatchTeamPlayer(Auth::id()))
                <span class="badge badge-success">@lang('matchmaking.signedup')</span>
            @endif
            @if ( $match->owner_id == Auth::id())
            <span class="badge badge-info">@lang('matchmaking.matchowner')</span>
            @endif
            @if ( $match->getMatchTeamOwner(Auth::id()))
            <span class="badge badge-info">@lang('matchmaking.teamowner')</span>
            @endif
        </span>
    </div>
    <div class="card-body">
        @if ($match->status != 'COMPLETE' && !($match->status == 'LIVE' && $match->game->matchmaking_autoapi))
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
        @if ($match->status == 'COMPLETE' || ($match->status == 'LIVE' && $match->game->matchmaking_autoapi))
            @php
                $teams = $match->teams;
                $teams = $teams->sortByDesc('team_score')->take(3);
            @endphp
            @foreach ($teams as $teamsentry)
                <h5 class="m-0">{{ $loop->iteration }} - {{ $teamsentry->name }} - {{ $teamsentry->team_score }}</h5>
            @endforeach
            <strong class="m-0 mt-1">
                @lang('matchmaking.teamcount') {{ $match->teams->count() }}
            </strong>
            @if ($match->status == 'COMPLETE')
                <h5 class="m-0 mt-1">@lang('matchmaking.ended')</h5>
            @else
                <h5 class="m-0 mt-1">@lang('matchmaking.live')</h5>
            @endif
        @endif
    </div>
</a>