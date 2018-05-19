@extends ('layouts.default')

@section ('page_title', 'Updating your Profile')

@section ('content')

	<div class="container">

		<div class="row">
			<!-- STEAM DETAILS -->
			<div class="col-xs-12  col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Steam Details</h3>
					</div>
					<div class="panel-body">
						{{ Form::open(array('url'=>'/account/' . $user->id )) }}
							<div class="row" style="display: flex; align-items: center;">
								<div class="col-md-2 col-sm-12">
									@if ($user->avatar != NULL)
										<img src="{{$user->avatar}}" class="img-responsive img-thumbnail"/>
									@endif
								</div> 
								<div class="col-md-10 col-sm-12">
									<div class="form-group">
										{{ Form::label('User Name','Name',array('id'=>'','class'=>'')) }}
										{{ Form::text('name', $user->username ,array('id'=>'name','class'=>'form-control', 'disabled' => 'disabled')) }}
									</div> 
									<div class="form-group">
										{{ Form::label('steamname','Steam Name',array('id'=>'','class'=>'')) }}
										{{ Form::text('steamname', $user->steamname ,array('id'=>'steamname','class'=>'form-control', 'disabled'=>'true')) }}
									</div>
								</div>
								
								<div class="form-group pull-right">
									<button type="submit" name="action" value="update" class="btn btn-primary hidden">Update</button>
									<button type="button" name="" value="" class="btn btn-danger hidden">Unlink Steam</button>
								</div>
							</div>
						{{ Form::close() }}
					</div>
				</div>
			</div>

			<!-- TICKETS -->
			<div class="col-sm-12 col-xs-12 col-md-6 col-lg-7">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Tickets</h3>
					</div>
					<div class="panel-body">
						@if (count($user->eventParticipants))
							@foreach ($user->eventParticipants as $participant)
								@include ('layouts._partials._tickets.index')
							@endforeach
						@else
							You currently have no tickets.
						@endif
					</div>
				</div>
			</div>

			<!-- PURCHASES -->
			<div class="col-sm-12 col-xs-12 col-md-6 col-lg-5">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Purchases</h3>
					</div>
					<div class="panel-body">
						@if (count($user->purchases))
							<table class="table table-striped">
								<thead>
									<tr>
										<th>
											ID
										</th>
										<th>
											Method
										</th>
										<th>
											Time
										</th>
										<th>
											Basket
										</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($user->purchases as $purchase)
										<tr>
											<td>
												{{ $purchase->id }}
											</td>
											<td>
												{{ $purchase->type }}
											</td>
											<td>
												{{  date('d-m-y H:i', strtotime($purchase->created_at)) }}
											</td>
											<td>
												@foreach ($purchase->participants as $participant)
													{{ $participant->event->display_name }} - {{ $participant->ticket->name }}
													@if(!$loop->last)
														<hr>
													@endif
												@endforeach
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						@else
							You have no purchases
						@endif
					</div>
				</div>
			</div>
		</div>
		@include ('layouts._partials._gifts.modal')
	</div>
@endsection