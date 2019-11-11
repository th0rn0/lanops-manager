@extends ('layouts.admin-default')

@section ('page_title', 'Settings')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">Settings - Auth & Login</h3>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/settings">Settings</a>
			</li>
			<li class="active">
				Auth & Login
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-lg-6 col-xs-12">
		<!-- Login Methods -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Login Methods
			</div>
			<div class="panel-body">
				@foreach ($supportedLoginMethods as $method)
					<div class="col-sm-6 col-xs-12">
						<h4>{{ ucwords(str_replace('-', ' ', (str_replace('_', ' ' , $method)))) }}</h4>
						@if (in_array($method, $activeLoginMethods))
							{{ Form::open(array('url'=>'/admin/settings/login/' . $method . '/disable')) }}
								<button type="submit" class="btn btn-block btn-danger">Disable</button>
							{{ Form::close() }}
						@else
							{{ Form::open(array('url'=>'/admin/settings/login/' . $method . '/enable')) }}
								<button type="submit" class="btn btn-block btn-success">Enable</button>
							{{ Form::close() }}
						@endif
					</div>
				@endforeach
			</div>
		</div>
		<!-- Terms & Conditions -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-info-circle fa-fw"></i> Terms and Conditions
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/settings/', 'onsubmit' => 'return ConfirmSubmit()')) }}
					<div class="form-group">
						{{ Form::label('registration_terms_and_conditions','Registration',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('registration_terms_and_conditions', Settings::getRegistrationTermsAndConditions() ,array('id'=>'','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-xs-12">
</div>
 
@endsection