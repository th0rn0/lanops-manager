@extends ('layouts.default')

@section ('page_title', 'Register')

@section ('content')

<div class="container">
	<div class="page-header">
		<h1>Register Details</h1>
	</div>
	<div class="row">
		{{ Form::open(array('url'=>'/account/register/' )) }}
			{{ csrf_field() }}
			{{ Form::hidden('method', $loginMethod, array('id'=>'method','class'=>'form-control')) }}
			@if ($loginMethod == "steam")
				{{ Form::hidden('avatar', $avatar, array('id'=>'avatar','class'=>'form-control')) }}
				{{ Form::hidden('steamid', $steamid, array('id'=>'steamid','class'=>'form-control')) }}
				{{ Form::hidden('steamname', $steamname, array('id'=>'steamname','class'=>'form-control')) }}
			@endif
			<div class="col-xs-12 col-md-6">
				<div class="row">
					<div class="col-xs-12 col-md-6">
						<div class="form-group">
							{{ Form::label('firstname','Firstname',array('id'=>'','class'=>'')) }}
							{{ Form::text('firstname', NULL, array('id'=>'firstname','class'=>'form-control', 'required')) }}
						</div>
					</div>
					<div class="col-xs-12 col-md-6">
						<div class="form-group">
							{{ Form::label('surname','Surname',array('id'=>'','class'=>'')) }}
							{{ Form::text('surname', NULL, array('id'=>'surname','class'=>'form-control', 'required')) }}
						</div>
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('username','Username',array('id'=>'','class'=>'')) }}
					{{ Form::text('username', NULL, array('id'=>'username','class'=>'form-control', 'required')) }}
				</div>
				@if ($loginMethod == "standard")
					<div class="form-group">
						{{ Form::label('email','E-Mail',array('id'=>'','class'=>'')) }}
						<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
					</div>
					<div class="form-group">
						{{ Form::label('password','Password',array('id'=>'','class'=>'')) }}
						 <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
					</div>
					<div class="form-group">
						{{ Form::label('password-confirm','Confirm Password',array('id'=>'','class'=>'')) }}
						<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
					</div>
				@endif
				@if ($loginMethod == "steam")
					<div class="form-group">
						{{ Form::label('steamname','Steam Name',array('id'=>'','class'=>'')) }}
						{{ Form::text('steamname', $steamname, array('id'=>'steamname','class'=>'form-control', 'disabled'=>'true')) }}
					</div>
				@endif
			</div>
			<div class="col-xs-12 col-md-6">
				{!! Settings::getRegistrationTermsAndConditions() !!}
				<h5>By Clicking on Confirm you are agreeing to the Terms and Conditions as set by {!! Settings::getOrgName() !!}</h5>
				<button type="submit" class="btn btn-block btn-primary">Register</button>
			</div>
		{{ Form::close() }}
	</div>
</div>

@endsection