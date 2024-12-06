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
        @if ($participants)
            @foreach ($participants as $participant)
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
                            {{ $participant->getSeat()->seat }} - {{ $participant->getSeat()->seatingPlan->name }}
                        </td>
                    @endif
                    @if ($tournament->hasTeams() && $participant->team)
                        <td>
                            {{ $participant->team->name }}
                        </td>
                    @endif
                </tr>
            @endforeach
        @endif
    </tbody>
</table>