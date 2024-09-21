@extends ('layouts.bigscreen')

@section ('page_title', config('app.name') . ' - Big Screen - Seating')

@section ('content')
<div class="container">
<!-- SEATING -->
@if (
		!$event->seatingPlans->isEmpty() && 
		(
			in_array('PUBLISHED', $event->seatingPlans->pluck('status')->toArray()) ||
			in_array('PREVIEW', $event->seatingPlans->pluck('status')->toArray())
		)
	)
		<div class="page-header">
			<a name="seating"></a>
			<h3>Seating Plans <small>- {{ $event->getSeatingCapacity() - $event->getSeatedCount() }} / {{ $event->getSeatingCapacity() }} Seats Remaining</small></h3>
		</div>
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			@foreach ($event->seatingPlans as $seatingPlan)
				@if ($seatingPlan->status != 'DRAFT')
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
							<h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_{{ $seatingPlan->slug }}" aria-expanded="true" aria-controls="collapse_{{ $seatingPlan->slug }}">
									{{ $seatingPlan->name }} <small>- {{ ($seatingPlan->columns * $seatingPlan->rows) - $seatingPlan->seats->count() }} / {{ $seatingPlan->columns * $seatingPlan->rows }} Available</small>
									@if ($seatingPlan->status != 'PUBLISHED')
										<small> - {{ $seatingPlan->status }}</small>
									@endif
								</a>
							</h4>
						</div>
						<div id="collapse_{{ $seatingPlan->slug }}" class="panel-collapse collapse @if ($loop->first) in @endif" role="tabpanel" aria-labelledby="collaspe_{{ $seatingPlan->slug }}">
							<div class="panel-body">
								<div class="table-responsive text-center">
									<table class="table">
										<thead>
											<tr>
											<?php
												$headers = explode(',', $seatingPlan->headers);
												$headers = array_combine(range(1, count($headers)), $headers);
											?>
											@for ($column = 1; $column <= $seatingPlan->columns; $column++)
												<th class="text-center"><h4><strong>ROW {{ucwords($headers[$column])}}</strong></h4></th>
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
																	<button class="btn btn-success btn-sm">
																		{{ ucwords($headers[$column]) . $row }} - {{ $event->getSeat($seatingPlan->id, ucwords($headers[$column] . $row))->eventParticipant->user->username }}
																	</button>
																@endif
															@else
																@if ($seatingPlan->locked)
																	<button class="btn btn-primary btn-sm" disabled>
																		{{ ucwords($headers[$column]) . $row }} - Empty
																	</button>
																@else
																	@if (Auth::user() && $event->getEventParticipant())
																		<button 
																			class="btn btn-primary btn-sm"
																			onclick="pickSeat(
																				'{{ $seatingPlan->slug }}',
																				'{{ ucwords($headers[$column]) . $row }}'
																			)"
																			data-toggle="modal"
																			data-target="#pickSeatModal"
																		>
																			{{ ucwords($headers[$column]) . $row }} - Empty
																		</button>
																	@else
																		<button class="btn btn-primary btn-sm">
																			{{ ucwords($headers[$column]) . $row }} - Empty
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
										<p class="text-center"><strong>NOTE: Seating Plan is currently locked!</strong></p>
									@endif
								</div>
							</div>
						</div>
					</div>
				@endif
			@endforeach
		</div>
	@endif
</div>
@endsection
