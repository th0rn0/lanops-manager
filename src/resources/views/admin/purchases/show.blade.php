@extends ('layouts.admin-default')

@section ('page_title', 'Purchase - ' . $purchase->id)

@section ('content')
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">Purchases</h3>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/purchases/">Purchases</a>
			</li>
			<li class="active">
				{{ $purchase->id }}
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-credit-card fa-fw"></i> Items
			</div>
			<div class="panel-body">
				@if ($purchase->basket)
					@include ('layouts._partials._checkout.basket', ['basket' => Helpers::formatBasket($purchase->basket, $purchase->user, $purchase->referral_discount_total, true)])
				@else
					<table width="100%" class="table table-striped table-hover">
						<thead>
							<tr>
								<th>Item</th>
								<th>Quantity</th>
								<th>Price Paid</th>
							</tr>
						</thead>
						<tbody>
							@if (!$purchase->participants->isEmpty())
								@foreach ($purchase->participants as $participant)
									<tr>
										<td>
											{{ $participant->ticket->name }} for {{ $participant->event->display_name }}
										</td>
										<td>
											1
										</td>
										<td>
											@if ($participant->ticket->price != null)
												{{ config('app.currency_symbol') }}{{ number_format((float)$participant->ticket->price, 2, '.', '') }}
											@endif
										</td>
									</tr>
								@endforeach
							@endif
						</tbody>
					</table>
				@endif
			</div>  
		</div>
	</div>
	<div class="col-xs-12 col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-credit-card fa-fw"></i> Details
			</div>
			<div class="panel-body">
				<ul class="list-group">
					<li class="list-group-item list-group-item-info"><strong>Purchase ID: <span class="pull-right">{{ $purchase->id }}</span></strong></li>
					<li class="list-group-item list-group-item-info">
						<strong>
							Referral Code Used: 
							<span class="pull-right">
								@if ($purchase->referral_code_user_id )
									Yes
								@else
									No
								@endif
							</span>
						</strong>
					</li>
					@if ($purchase->referralCodeUsedPurchase)
						<li class="list-group-item list-group-item-info">
							<strong>
								Referral Redeemed: 
								<span class="pull-right">
										Yes
								</span>
							</strong>
						</li>
						<li class="list-group-item list-group-item-info">
							<strong>
								Referral User: 
								<span class="pull-right">
									<a href="/admin/users/{{ $purchase->referralCodeUsedPurchase->user->id }}">
										{{ $purchase->referralCodeUsedPurchase->user->username }}
									</a>
								</span>
							</strong>
						</li>
					@endif
					<li class="list-group-item list-group-item-info">
						<strong>
							User: 
							<span class="pull-right">
								{{ $purchase->user->username }}
								@if ($purchase->user->steamid)
									- <span class="text-muted"><small>Steam: {{ $purchase->user->steamname }}</small></span>
								@endif
							</span>
						</strong>
					</li>
					<li class="list-group-item list-group-item-info"><strong>Name: <span class="pull-right">{{ $purchase->user->firstname }} {{ $purchase->user->surname }}</span></strong></li>
					<li class="list-group-item @if (strtolower($purchase->status) != 'success') list-group-item-danger @else list-group-item-success @endif"><strong>Status: <span class="pull-right">{{ $purchase->status }}</span></strong></li>
					<li class="list-group-item list-group-item-info"><strong>Type: <span class="pull-right">{{ $purchase->type }}</span></strong></li>
					@if ($purchase->paypal_email != null)
						<li class="list-group-item list-group-item-info">
							<strong>Paypal Email: <span class="pull-right">{{ $purchase->paypal_email }}</span></strong>
						</li>
					@endif
					<li class="list-group-item list-group-item-info"><strong>Transaction ID: <span class="pull-right">{{ $purchase->transaction_id }}</span></strong></li>
					<li class="list-group-item list-group-item-info"><strong>Timestamp: <span class="pull-right">{{ $purchase->created_at }}</span></strong></li>
				</ul>
				@if (count($purchase->participants) > 0)
					@foreach ($purchase->participants as $participant)
						<a href="/admin/events/{{ $participant->event->slug }}/participants/{{ $participant->id }}"><button class="btn btn-block btn-success">View Participant - {{ $participant->user->username }}</button></a>
					@endforeach
				@endif
			</div>  
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-credit-card fa-fw"></i> Total at Gateway
			</div>
			<div class="panel-body">
				<strong>
					Total: 
					<span class="pull-right">
						{{ config('app.currency_symbol') }}{{ $purchase->total }} </br>
					</span></br>
					Total Before Discount:
					<span class="pull-right">
						{{ config('app.currency_symbol') }}{{ $purchase->total_before_discount }} </br>
					</span></br>
					Referral Discount: 
					<span class="pull-right">
						{{ config('app.currency_symbol') }}{{ $purchase->referral_discount_total }}
					</span>
				</strong>
			</div>
		</div>
	</div>

</div>

@endsection