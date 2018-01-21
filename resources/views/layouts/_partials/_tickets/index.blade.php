<div class="panel panel-success">
	<div class="panel-heading">
		@if($participant->ticket)
			<strong>{{ $participant->ticket->name }} @if($participant->ticket && $participant->ticket->seatable) - Seat: @if ($participant->seat) {{$participant->seat->seat}} <small>in {{$participant->seat->seatingPlan->name}}</small> @else Not Seated @endif @endif</strong>
		@else
			@if($participant->staff)
				<strong>Staff Ticket @if($participant->seat) - Seat: {{$participant->seat->seat}} @endif</strong>
			@else
				<strong>Free Ticket @if($participant->seat) - Seat: {{$participant->seat->seat}} @endif</strong>
			@endif
		@endif
		@if($participant->gift == 1 && $participant->gift_accepted != 1)
			<span class="label label-info label-block pull-right" style="margin-left: 3px; margin-top:2px;">This Ticket has been gifted!</span>
		@endif
		@if($participant->ticket && !$participant->ticket->seatable)
			<span class="label label-info label-block pull-right" style="margin-top:2px;">This Ticket is not eligable for a seat</span>
		@endif
	</div>
	<div class="panel-body">
		<div class="row" style="display: flex; align-items: center;">
			<div class="col-md-8 col-sm-8 col-xs-12">
				@if($participant->gift != 1 && $participant->gift_accepted != 1)
					<button class="btn btn-md btn-success btn-block" onclick="giftTicket('{{ $participant->id }}')" data-toggle="modal" data-target="#giftTicketModal">
						Gift Ticket
					</button>
				@endif
				@if($participant->gift == 1 && $participant->gift_accepted != 1)
					<label>Gift URL:</label>
					<p>
						<strong>
							http://{{ config('app.url') }}/gift/accept/?url={{ $participant->gift_accepted_url }}
						</strong>
					</p>
					{{ Form::open(array('url'=>'/gift/' . $participant->id . '/revoke', 'id'=>'revokeGiftTicketForm')) }}
						<button type="submit" class="btn btn-primary btn-md btn-block">Revoke Gift</button>
					{{ Form::close() }}
				@endif
				@if($participant->seat)
					<hr>
				 {{ Form::open(array('url'=>'/events/' . $participant->event->slug . '/seating/' . $participant->seat->seatingPlan->id)) }}
						{{ Form::hidden('_method', 'DELETE') }}
						{{ Form::hidden('user_id', $user->id, array('id'=>'user_id','class'=>'form-control')) }} 
						{{ Form::hidden('participant_id', $participant->id, array('id'=>'participant_id','class'=>'form-control')) }} 
						{{ Form::hidden('seat_number', $participant->seat->seat, array('id'=>'seat_number','class'=>'form-control')) }} 
						<h5>
							<button class="btn btn-danger btn-block"> 
								Remove Seating
							</button>
						</h5>
					{{ Form::close() }}
				@endif
			</div>
			<div class="col-md-offset-2 col-md-2 col-sm-offset-2 col-sm-4 col-xs-12">
				<img class="img img-responsive" src="/{{ $participant->qrcode }}"/>
			</div>
		</div>
	</div>
</div>

@include('layouts._partials._gifts.modal')