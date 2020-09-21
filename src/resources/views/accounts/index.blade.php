@extends ('layouts.default')

@section ('page_title', __('accounts_title'))

@section ('content')

	<div class="container">

		<div class="page-header">
			<h1>
			@lang('accounts_index.my_account')
			</h1>
		</div>
		<div class="row">
			<!-- ACCOUNT DETAILS -->
			<div class="col-xs-12  col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">@lang('accounts_index.account_details')</h3>
					</div>
					<div class="panel-body">
						{{ Form::open(array('url'=>'/account/' )) }}
							<div class="row" style="display: flex; align-items: center;">
								<div class="col-md-2 col-sm-12">
									@if ($user->avatar != NULL)
										<img src="{{ $user->avatar }}" alt="{{ $user->username }}'s Avatar" class="img-responsive img-thumbnail"/>
									@endif
								</div> 
								<div class="col-md-10 col-sm-12">
					                <div class="row">
					                    <div class="col-xs-12 col-md-6">
					                        <div class="form-group @error('firstname') has-error @enderror">
					                            {{ Form::label('firstname',__('accounts_index.firstname'),array('id'=>'','class'=>'')) }}
					                            <input id="firstname" type="firstname" class="form-control" name="firstname" value="{{ $user->firstname }}" required autocomplete="firstname">
					                        </div>
					                    </div>
					                    <div class="col-xs-12 col-md-6">
					                        <div class="form-group  @error('surname') has-error @enderror">
					                            {{ Form::label('surname',__('accounts_index.surname'),array('id'=>'','class'=>'')) }}
					                            <input id="surname" type="surname" class="form-control" name="surname" value="{{ $user->surname }}" required autocomplete="surname">
					                        </div>
					                    </div>
					                </div>
									<div class="form-group">
										{{ Form::label('Username',__('accounts_index.username'),array('id'=>'','class'=>'')) }}
										{{ Form::text('name', $user->username ,array('id'=>'name','class'=>'form-control', 'disabled' => 'disabled')) }}
									</div> 
									@if ($user->steamid && $user->steamname)
										<div class="form-group">
											{{ Form::label('steamname',__('accounts_index.steamname'),array('id'=>'','class'=>'')) }}
											{{ Form::text('steamname', $user->steamname ,array('id'=>'steamname','class'=>'form-control', 'disabled'=>'true')) }}
										</div>
									@endif
									@if ($user->email)
										<div class="form-group">
											{{ Form::label('email',__('accounts_index.email'),array('id'=>'','class'=>'')) }}
											<input type="email" class="form-control" name="email" id="email @error('email') is-invalid @enderror" aria-describedby="email" value="{{ $user->email }}" placeholder="Enter email">
											@error('email')
				                                <span class="invalid-feedback" role="alert">
				                                    <strong>{{ $message }}</strong>
				                                </span>
				                            @enderror
										</div>
									@endif
									@if ($user->password)
										<div class="form-group">
											<label for="password1">@lang('accounts_index.change_password')</label>
											<input type="password" name="password1" class="form-control @error('password1') is-invalid @enderror" id="password1" placeholder="Password">
										 	@error('password1')
				                                <span class="invalid-feedback" role="alert">
				                                    <strong>{{ $message }}</strong>
				                                </span>
				                            @enderror
										</div>
										<div class="form-group">
											<label for="password2">@lang('accounts_index.confirm_password')</label>
											<input type="password" name="password2" class="form-control @error('password2') is-invalid @enderror" id="password2" placeholder="Password">
										 	@error('password2')
				                                <span class="invalid-feedback" role="alert">
				                                    <strong>{{ $message }}</strong>
				                                </span>
				                            @enderror
										</div>
									@endif
									<button type="submit" class="btn btn-primary btn-block">@lang('accounts_index.submit')</button>
								</div>
							</div>
						{{ Form::close() }}
					</div>
				</div>
				@if ($creditLogs)
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Credit - {{ $user->credit_total }}</h3>
						</div>
						<div class="panel-body">
							<table width="100%" class="table table-striped table-hover" id="dataTables-example">
								<thead>
									<tr>
										<th>@lang('accounts_index.credit_action')</th>
										<th>@lang('accounts_index.credit_amount')</th>
										<th>@lang('accounts_index.credit_item')</th>
										<th>@lang('accounts_index.credit_reason')</th>
										<th>@lang('accounts_index.credit_timestamp')</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($creditLogs->reverse() as $creditLog)
									<tr class="table-row" class="odd gradeX">
										<td>{{ $creditLog->action }}</td>
										<td>{{ $creditLog->amount }}</td>
										<td>
											@if (strtolower($creditLog->action) == 'buy')
												@if (!$creditLog->purchase->participants->isEmpty())
													@foreach ($creditLog->purchase->participants as $participant)
														{{ $participant->event->display_name }} - {{ $participant->ticket->name }}
														@if (!$loop->last)
															<hr>
														@endif
													@endforeach
												@elseif ($creditLog->purchase->order != null)
													@foreach ($creditLog->purchase->order->items as $item)
														@if ($item->item)
															{{ $item->item->name }}
														@endif 
														 - x {{ $item->quantity }}
														 <br>
													 	@if ($item->price != null)
															{{ Settings::getCurrencySymbol() }}{{ $item->price * $item->quantity }}
															@if ($item->price_credit != null && Settings::isCreditEnabled())
																/
															@endif
														@endif
														@if ($item->price_credit != null && Settings::isCreditEnabled())
															{{ $item->price_credit * $item->quantity }} Credits
														@endif
														@if (!$loop->last)
															<hr>
														@endif
													@endforeach
												@endif
											@endif
										</td>
										<td>{{ $creditLog->reason }}</td>
										<td>
											{{ $creditLog->updated_at }}
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
							{{ $creditLogs->links() }}
						</div>
					</div>
				@endif
			</div>

			<!-- TICKETS -->
			<div class="col-sm-12 col-xs-12 col-md-6 col-lg-7">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">@lang('accounts_index.tickets')</h3>
					</div>
					<div class="panel-body">
						@if (count($eventParticipants))
							@foreach ($eventParticipants as $participant)
								@include ('layouts._partials._tickets.index')
							@endforeach
						@else
							@lang('accounts_index.no_tickets')
						@endif
					</div>
				</div>
			</div>

			<!-- PURCHASES -->
			<div class="col-sm-12 col-xs-12 col-md-6 col-lg-5">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">@lang('accounts_index.purchases')</h3>
					</div>
					<div class="panel-body">
						@if (count($user->purchases))
							<table class="table table-striped">
								<thead>
									<tr>
										<th>
											@lang('accounts_index.purchases_id')
										</th>
										<th>
											@lang('accounts_index.purchases_method')
										</th>
										<th>
											@lang('accounts_index.purchases_time')
										</th>
										<th>
											@lang('accounts_index.purchases_basket')
										</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($purchases as $purchase)
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
												@if (!$purchase->participants->isEmpty())
													@foreach ($purchase->participants as $participant)
														{{ $participant->event->display_name }} - {{ $participant->ticket->name }}
														@if (!$loop->last)
															<hr>
														@endif
													@endforeach
												@elseif ($purchase->order != null)
													@foreach ($purchase->order->items as $item)
														@if ($item->item)
															{{ $item->item->name }}
														@endif 
														 - x {{ $item->quantity }}
														 <br>
													 	@if ($item->price != null)
															{{ Settings::getCurrencySymbol() }}{{ $item->price * $item->quantity }}
															@if ($item->price_credit != null && Settings::isCreditEnabled())
																/
															@endif
														@endif
														@if ($item->price_credit != null && Settings::isCreditEnabled())
															{{ $item->price_credit * $item->quantity }} Credits
														@endif
														@if (!$loop->last)
															<hr>
														@endif
													@endforeach
												@endif
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
							{{ $purchases->links() }}
						@else
							@lang('accounts_index.no_purchases')
						@endif
						@if (Settings::isShopEnabled())
							<a href="/shop/orders">
								<button class="btn btn-success">@lang('accounts_index.show_shop_orders')</button>
							</a>
						@endif
					</div>
				</div>
			</div>

			<!-- DANGER ZONE -->
			<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Danger Zone</h3>
					</div>
					<div class="panel-body">
						<button type="button" name="" value="" class="btn btn-danger hidden">@lang('accounts_index.remove_steam_account')</button>
						<button type="button" name="" value="" class="btn btn-danger hidden">@lang('accounts_index.add_second_steam_account')</button>
						<button type="button" name="" value="" class="btn btn-danger hidden">@lang('accounts_index.add_twitch_account')</button>
						<button type="button" name="" value="" class="btn btn-danger hidden">@lang('accounts_index.remove_twitch_account')</button>
						<button class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal">@lang('accounts_index.remove_account')</button>
					</div>
				</div>
			</div>
		</div>
		@include ('layouts._partials._gifts.modal')
	</div>

	<!-- Confirm Delete Modal -->
	<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="confirmDeleteModalLabel">@lang('accounts_index.confirm_remove_account')</h4>
				</div>
				{{ Form::open(array('url'=>'/account/delete/', 'id'=>'confirmDeleteFormModal')) }}
					<div class="modal-body">
						<div class="form-group">
							<p>@lang('accounts_index.remove_account_line1')</p>
							<p><strong>@lang('accounts_index.remove_account_all')</strong> @lang('accounts_index.remove_account_line2')</p>
							<p><strong>@lang('accounts_index.remove_account_all')</strong> @lang('accounts_index.remove_account_line3')</p>
							<p><strong>@lang('accounts_index.remove_account_all')</strong> @lang('accounts_index.remove_account_line4')</p> 
							<p>@lang('accounts_index.remove_account_line5_1') <strong>@lang('accounts_index.remove_account_not')</strong> @lang('accounts_index.remove_account_line5_1')</p>
							<p>@lang('accounts_index.remove_account_line6_1') <strong>@lang('accounts_index.remove_account_not')</strong> @lang('accounts_index.remove_account_line6_2')</p>
							<p><strong>@lang('accounts_index.remove_account_line7')</strong></p>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-success">@lang('accounts_index.remove_account_accept')Accept</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">@lang('accounts_index.remove_account_cancel')Cancel</button>
					</div>
				{{ Form::close() }}
			</div>
		</div>
	</div>
	
@endsection