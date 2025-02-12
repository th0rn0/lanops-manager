<div class="table-responsive text-center">
    <table class="table">
        <thead>
            @if (!$horizontal)
                <tr>
                    @foreach ($seatingPlan->headers as $header)
                        <th class="text-center"><h4><strong>ROW {{ucwords($header)}}</strong></h4></th>
                    @endforeach
                </tr>
            @endif
         </thead>
        <tbody>
            @if (!$horizontal)
                @for ($c = 1; $c <= $seatingPlan->columns; $c++)
                    <tr>
                        @foreach ($seatingPlan->getSeatsForColumn($c)->sortBy('seat') as $seat)
                            <td>
                            
                                <button 
                                    class="btn @if ($seat->disabled) btn-danger @elseif ($seat->eventParticipant) btn-success @else btn-primary @endif btn-sm" 
                                    @if ($seat->disabled) disabled @endif
                                    @if (!$seat->eventParticipant && !$seat->disabled)
                                        onclick="pickSeat(
                                            '{{ $seatingPlan->slug }}',
                                            '{{ $seat->seat }}'
                                        )"
                                        data-toggle="modal"
                                        data-target="#pickSeatModal"
                                    @endif
                                    >
                                    {{ $seat->seat }} - @if ($seat->eventParticipant) {{ $seat->eventParticipant->user->username }} @elseif ($seat->disabled) Disabled @else Empty @endif
                                </button>

                            </td>
                        @endforeach
                    </tr>
                @endfor
            @else
                @foreach ($seatingPlan->headers as $header)
                    <tr>
                        <td>
                            <th class="text-center"><h4><strong>ROW {{ucwords($header)}}</strong></h4></th>
                        </td>
                        @foreach ($seatingPlan->getSeatsForRow($header) as $seat)
                            <td>
                            
                                <button 
                                    class="btn @if ($seat->disabled) btn-danger @elseif ($seat->eventParticipant) btn-success @else btn-primary @endif btn-sm" 
                                    @if ($seat->disabled) disabled @endif
                                    @if (!$seat->eventParticipant && !$seat->disabled)
                                        onclick="pickSeat(
                                            '{{ $seatingPlan->slug }}',
                                            '{{ $seat->seat }}'
                                        )"
                                        data-toggle="modal"
                                        data-target="#pickSeatModal"
                                    @endif
                                    >
                                    {{ $seat->seat }} - @if ($seat->eventParticipant) {{ $seat->eventParticipant->user->username }} @elseif ($seat->disabled) Disabled @else Empty @endif
                                </button>

                            </td>
                        @endforeach
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    @if ($seatingPlan->locked)
        <p class="text-center"><strong>NOTE: Seating Plan is currently locked!</strong></p>
    @endif
</div>