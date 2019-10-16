@extends ('layouts.default')

@section ('page_title', 'Register')

@section ('content')

<div class="container">
	<div class="page-header">
		<h1>Register Details</h1>
	</div>
	{{ Form::open(array('url'=>'/account/register/' )) }}
		<div class="form-group">
			{{ Form::label('firstname','Firstname',array('id'=>'','class'=>'')) }}
				{{ Form::text('firstname', NULL, array('id'=>'firstname','class'=>'form-control', 'required')) }}
		</div>
		<div class="form-group">
			{{ Form::label('surname','Surname',array('id'=>'','class'=>'')) }}
				{{ Form::text('surname', NULL, array('id'=>'surname','class'=>'form-control', 'required')) }}
		</div>
		<div class="form-group">
			{{ Form::label('username','Username',array('id'=>'','class'=>'')) }}
				{{ Form::text('username', NULL, array('id'=>'username','class'=>'form-control', 'required')) }}
		</div>
		<div class="form-group">
			{{ Form::label('steamname','Steam Name',array('id'=>'','class'=>'')) }}
				{{ Form::text('steamname', $steamname, array('id'=>'steamname','class'=>'form-control', 'disabled'=>'true')) }}
		</div>
		{{ Form::hidden('avatar', $avatar, array('id'=>'avatar','class'=>'form-control')) }}
		{{ Form::hidden('steamid', $steamid, array('id'=>'steamid','class'=>'form-control')) }}
		{{ Form::hidden('steamname', $steamname, array('id'=>'steamname','class'=>'form-control')) }}

		{!! Settings::getRegistrationTermsAndConditions() !!}
		<h5>By Clicking on Confirm you are agreeing to the Terms and Conditions as set by {!! Settings::getOrgName() !!}</h5>
		<button type="submit" class="btn  btn-primary">Register</button>
		{{ csrf_field() }}
	{{ Form::close() }}
</div>

@endsection