<div class="panel panel-default">
    <div class="panel-heading">
        <strong><a href="/tournaments/{{ $tournament->slug }}">{{ $tournament->name }}</a></strong>
        @if ($tournament->hasEvent())
            <strong> - {{ $tournament->event->display_name }}</strong>
        @endif
        <small> - {{ $tournament->status }}</small>
    </div>
    <div class="panel-body">
        <p class="bg-info padding">participants: {{ $tournament->participants->count() }}</p>
        @if ($tournament->isTeamBased())
            <p class="bg-info padding">Team Size: {{ $tournament->team_size }}</p>
        @endif
        @if (Auth::user() && !$tournament->isUserSignedUp(Auth::user()) && $tournament->signupsOpen())
            {{ Form::open(array('url'=>'/tournaments/' . $tournament->slug . '/register')) }}
                <button type="submit" class="btn btn-success btn-sm btn-block">Signup</button>
            {{ Form::close() }}
        @elseif (Auth::user() && $tournament->isUserSignedUp(Auth::user()) && $tournament->signupsOpen())
            {{ Form::open(array('url'=>'/tournaments/' . $tournament->slug . '/unregister')) }}
                <button type="submit" class="btn btn-danger btn-sm btn-block">Remove Signup</button>
            {{ Form::close() }}
        @endif
        @if ($tournament->hasParticipants())
            <table width="100%" class="table table-striped table-hover" id="dataTables-example">
                <thead>
                    <tr>
                        <th>Avatar</th>
                        <th>User</th>
                        @if ($tournament->hasEvent())
                            <th>Seat</th>
                        @endif
                        @if ($tournament->hasTeams())
                            <th>Team</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($tournament->participants)
                        @foreach ($tournament->participants as $participant)
                            <tr class="table-row odd gradeX">
                                <td width="10%"><img class="img-responsive img-rounded" src="{{ $participant->user->avatar }}"/></td>
                                <td>
                                    {{ $participant->user->username }}
                                    @if ($participant->user->steamid)
                                        <br><span class="text-muted"><small>Steam: {{ $participant->user->steamname }}</small></span>
                                    @endif
                                </td>
                                @if ($tournament->hasEvent())
                                    <td>
                                        $tournament->getParticipantByUserOrSomething
                                    </td>
                                @endif
                                @if ($tournament->hasTeams())
                                    <td>
                                        $tournament->getParticipantByUserOrSomething
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        @endif
    </div>
</div>
