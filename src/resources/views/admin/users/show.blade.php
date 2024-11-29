@extends ('layouts.admin-default')

@section ('page_title', 'Users - View '. $userShow->username)

@section ('content')
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">{{ $userShow->username }}</h3>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/users/">Users</a>
			</li>
			<li class="active">
				{{ $userShow->username }}
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-sm-12 col-lg-10">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> User
			</div>
			<div class="panel-body">
				@if ($userShow->banned)
					<div class="alert alert-danger">This User has been banned!</div>
				@endif
				<div class="media">
  					<div class="media-left">
						<img class="media-object" src="{{ $userShow->avatar }}">
			  		</div>
  					<div class="media-body">
						<ul class="list-group">
							<li class="list-group-item">Username: {{ $userShow->username }}</li>
							@if ($userShow->steamid) <li class="list-group-item">Steam: {{ $userShow->steamname }}</li> @endif
							<li class="list-group-item">Name: {{ $userShow->firstname }} {{ $userShow->surname }}</li>
							<li class="list-group-item">
								Admin: @if ($userShow->admin) Yes @else No @endif
							</li>
							@if ($userShow->email != null)
								<li class="list-group-item">Email: {{ $userShow->email }}</li>
							@endif
							<li class="list-group-item">Referral Discounts Unclaimed: {{ $userShow->referral_code_count }}</li>
							<li class="list-group-item">Referral Discounts Used: {{ $userShow->referralsRedeemed() }}</li>
						</ul>
  					</div>
  				</div>
			</div>  
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Purchases</h3>
			</div>
			<div class="panel-body">
				@if (count($userShow->purchases))
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
								<th>
									Referral Code
								</th>
								<th>
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($userShow->purchases as $purchase)
								<tr>
									<td>
										{{ $purchase->id }}
									</td>
									<td>
										{{ $purchase->getPurchaseType() }}
									</td>
									<td>
										{{  date('d-m-y H:i', strtotime($purchase->created_at)) }}
									</td>
									<td>
										@if ($purchase->basket)
											@include ('layouts._partials._checkout.basket', ['basket' => Helpers::formatBasket($purchase->basket, $purchase->user, $purchase->referral_discount_total, true)])
										@elseif (!$purchase->participants->isEmpty())
											@foreach ($purchase->participants as $participant)
												{{ $participant->event->display_name }} - {{ $participant->ticket->name }}
												@if (!$loop->last)
													<hr>
												@endif
											@endforeach
										@endif
									</td>
									<td>
										@if ($purchase->referralUser )
											<a href="/admin/users/{{ $purchase->referralUser->id }}">{{ $purchase->referralUser->referral_code }} - {{ $purchase->referralUser->username }}</a>
										@endif
									</td>
									<td>
										<a href="/admin/purchases/{{ $purchase->id }}">
											<button class="btn btn-sm btn-block btn-success">View</button>
										</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					{{ $purchases->links() }}
				@else
					User has no purchases
				@endif
			</div>
		</div>
	</div>
	<div class="col-sm-12 col-lg-2">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> Options
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12">
						@if ($userShow->admin)
							{{ Form::open(array('url'=>'/admin/users/' . $userShow->id . '/admin')) }}
								{{ Form::hidden('_method', 'DELETE') }}
								<button type="submit" class="btn btn-block btn-info">Remove Admin</button>
							{{ Form::close() }}
						@else
							{{ Form::open(array('url'=>'/admin/users/' . $userShow->id . '/admin')) }}
								<button type="submit" class="btn btn-block btn-success">Make Admin</button>
							{{ Form::close() }}
						@endif
						<small>This will add or remove access to this admin panel. This means they can access everything! BE CAREFUL!</small>
					</div>
					@if ($userShow->email != null && $userShow->password != null)
						<div class="col-xs-12 col-sm-12">
							{{ Form::open(array('url'=>'/login/forgot')) }}
	                            @csrf
								<input type="hidden" name="email" value="{{ $userShow->email }}">
								<button type="submit" class="btn btn-block btn-success">Reset Password</button>
							{{ Form::close() }}
							<small>This will reset the users password and sent a verification link to their email. If they are using a 3rd party Login this will do nothing.</small>
						</div>
					@endif
				</div>
				<br>
				<h4>Danger Zone</h4>
				<hr>
				<div class="row">
					<div class="col-xs-12 col-sm-12">
						@if (!$userShow->banned)
							{{ Form::open(array('url'=>'/admin/users/' . $userShow->id . '/ban')) }}
								<button type="submit" class="btn btn-block btn-danger">Ban</button>
							{{ Form::close() }}
						@else
							{{ Form::open(array('url'=>'/admin/users/' . $userShow->id . '/unban')) }}
								<button type="submit" class="btn btn-block btn-success">Un-Ban</button>
							{{ Form::close() }}
						@endif
					</div>
				</div>
			</div>
		</div>			
	</div>
</div>
 
@endsection