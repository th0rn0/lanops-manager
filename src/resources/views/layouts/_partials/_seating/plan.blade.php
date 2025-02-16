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
                @for ($c = $seatingPlan->columns; $c >= 1; $c--)
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

<!-- Seat Modal -->
<div class="modal fade" id="pickSeatModal" tabindex="-1" role="dialog" aria-labelledby="editSeatingModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="pickSeatModalLabel"></h4>
			</div>
			@if (Auth::user())
				{{ Form::open(array('url'=>'/events/' . $event->slug . '/seating/', 'id'=>'pickSeatFormModal')) }}
					<div class="modal-body">
						<div class="form-group">
							<h4>Which ticket would you like to seat?</h4>
							{{
								Form::select(
									'participant_id',
									$user->getTickets($event->id),             
									null, 
									array(
										'id'    => 'format',
										'class' => 'form-control'
									)
								)
							}}
							<p>Are you sure you want this seat?</p>
							<p>You can remove it at anytime.</p>
						</div>
					</div>
					{{ Form::hidden('user_id', $user->id, array('id'=>'user_id','class'=>'form-control')) }}
					{{ Form::hidden('seat', NULL, array('id'=>'seat_modal','class'=>'form-control')) }}
					<div class="modal-footer">
						<button type="submit" class="btn btn-success">Yes</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
					</div>
				{{ Form::close() }}
			@endif
		</div>
	</div>
</div>

<script>
	function pickSeat(seating_plan_slug, seat)
	{
		$("#seat_number_modal").val(seat);
		$("#seat_modal").val(seat);
		$("#pickSeatModalLabel").html('Do you what to choose seat ' + seat);
		$("#pickSeatFormModal").prop('action', '/events/{{ $event->slug }}/seating/' + seating_plan_slug);
	}
</script>