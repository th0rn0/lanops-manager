@extends ('layouts.default')

@section ('page_title', __('accounts.accounts_title'))

@section ('content')

	<div class="container">

		<div class="row">
			
			<div class="col-12  col-lg-12 mt-3 mb-3">
				<!-- Email -->
				<div class="card mb-3">
					<div class="card-header ">
						<h3 class="card-title">@lang('accounts.emaildetails')</h3>
					</div>
					<div class="card-body">
						{{ Form::open(array('url'=>'/account/email' )) }}
							<div class="row" style="display: flex; align-items: center;">
								<div class="col-md-2 col-sm-12">
									@lang('accounts.emailmessage')
								</div>
								<div class="col-md-10 col-sm-12">
									@if ($user->email)
										<div class="form-group">
											{{ Form::label('email',__('accounts.email'),array('id'=>'','class'=>'')) }}
											@if(Settings::isAuthAllowEmailChangeEnabled())
												<input type="email" class="form-control" name="email" id="email @error('email') is-invalid @enderror" aria-describedby="email" value="{{ $user->email }}" placeholder="@lang('accounts.email')">
											@else
												<input type="email" class="form-control" name="email" id="email @error('email') is-invalid @enderror" aria-describedby="email" value="{{ $user->email }}" disabled placeholder="@lang('accounts.email')">
											@endif
											
											@error('email')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
									@else
									<div class="form-group">
										{{ Form::label('email',__('accounts.email'),array('id'=>'','class'=>'')) }}
											<input type="email" class="form-control" name="email" id="email @error('email') is-invalid @enderror" aria-describedby="email" value="{{ $user->email }}" placeholder="@lang('accounts.email')">
										@error('email')
											<span class="invalid-feedback" role="alert">
												<strong>{{ $message }}</strong>
											</span>
										@enderror
									</div>
									
									@endif
									@if (!$user->email || Settings::isAuthAllowEmailChangeEnabled())
										<button type="submit" class="btn btn-primary btn-block">@lang('accounts.submit')</button>
									@endif
								</div>
							</div>
						{{ Form::close() }}
					</div>
				</div>
			</div>
		</div>
			

@endsection