@extends ('layouts.admin-default')

@section ('page_title', 'Settings')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Authentication</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="/admin/settings">Settings</a>
			</li>
			<li class="breadcrumb-item active">
				Authentication
			</li>
		</ol>
	</div>
</div>

@include ('layouts._partials._admin._settings.dashMini', ['active' => 'auth'])

<div class="row">
	<div class="col-12">
		<!-- Login Methods -->
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-wrench fa-fw"></i> Login Methods
			</div>
			<div class="card-body">
				<div class="row">
					@foreach ($supportedLoginMethods as $method)
						<div class="col">
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
		</div>
		<!-- Terms & Conditions -->
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-info-circle fa-fw"></i> Terms and Conditions
			</div>
			<div class="card-body">
				{{ Form::open(array('url'=>'/admin/settings/', 'onsubmit' => 'return ConfirmSubmit()')) }}
					<div class="form-group">
						{{ Form::label('registration_terms_and_conditions','Registration',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('registration_terms_and_conditions', Settings::getRegistrationTermsAndConditions() ,array('id'=>'','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<button type="submit" class="btn btn-success btn-block">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>

@endsection