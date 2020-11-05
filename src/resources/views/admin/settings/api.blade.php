@extends ('layouts.admin-default')

@section ('page_title', 'Settings')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">API</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="/admin/settings">Settings</a>
			</li>
			<li class="breadcrumb-item active">
				API
			</li>
		</ol>
	</div>
</div>

@include ('layouts._partials._admin._settings.dashMini', ['active' => 'auth'])

<div class="row">
	<div class="col-12">
		<div class="alert alert-info">
			Be careful! Changes these settings could break the site!
		</div>
	</div>
	<!-- Challonge -->
	<div class="col-12 col-md-6">
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-wrench fa-fw"></i> Challonge
			</div>
			<div class="card-body">
				<div class="row">
					{{ Form::open(array('url'=>'/admin/settings/api', 'onsubmit' => 'return ConfirmSubmit()', 'files' => 'true')) }}
					 {{ csrf_field() }}
						<div class="col-12 col-md-6">
							<div class="form-group">
								{{ Form::label('challonge_api_key','API Key',array('id'=>'','class'=>'')) }}
								{{ Form::text('challonge_api_key', $challongeApiKey, array('id'=>'','class'=>'form-control')) }}
							</div>
							<button type="submit" class="btn btn-success btn-block">Submit</button>
						</div>
						<div class="col-12 col-md-6">
							<p>Challonge API Documentation</p>
							<p>Without this key Tournaments will be disabled</p>
							<p>https://challonge.com/settings/developer</p>
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
		<!-- Steam API Key -->
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-wrench fa-fw"></i> Steam API Key
			</div>
			<div class="card-body">
				<div class="row">
					{{ Form::open(array('url'=>'/admin/settings/api', 'onsubmit' => 'return ConfirmSubmit()', 'files' => 'true')) }}
						<div class="col-12 col-md-6">
							<div class="form-group">
								{{ Form::label('steam_api_key','API Key',array('id'=>'','class'=>'')) }}
								{{ Form::text('steam_api_key', $steamApiKey, array('id'=>'','class'=>'form-control')) }}
							</div>
							<button type="submit" class="btn btn-success btn-block">Submit</button>
						</div>
						<div class="col-12 col-md-6">
							<p>Steam API Documentation</p>
							<p>Without this key Steam Login will be disabled</p>
							<p>https://steamcommunity.com/dev/apikey</p>
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
		<!-- Facebook -->
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-wrench fa-fw"></i> Facebook
			</div>
			<div class="card-body">
				<div class="row">
					{{ Form::open(array('url'=>'/admin/settings/api', 'onsubmit' => 'return ConfirmSubmit()', 'files' => 'true')) }}
						<div class="col-12 col-md-6">
							<div class="form-group">
								{{ Form::label('facebook_app_id','App Id',array('id'=>'','class'=>'')) }}
								{{ Form::text('facebook_app_id', $facebookAppId, array('id'=>'','class'=>'form-control')) }}
							</div>
							<div class="form-group">
								{{ Form::label('facebook_app_secret','App Secret',array('id'=>'','class'=>'')) }}
								{{ Form::text('facebook_app_secret', $facebookAppSecret, array('id'=>'','class'=>'form-control')) }}
							</div>
							<button type="submit" class="btn btn-success btn-block">Submit</button>
						</div>
						<div class="col-12 col-md-6">
							<p>Facebook API Documentation</p>
							<p>Without this key Facebook Posting will be disabled</p>
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
	<!-- Paypal -->
	<div class="col-12 col-md-6">
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-wrench fa-fw"></i> Paypal
			</div>
			<div class="card-body">
				<div class="row">
					{{ Form::open(array('url'=>'/admin/settings/api', 'onsubmit' => 'return ConfirmSubmit()', 'files' => 'true')) }}
						<div class="col-12 col-md-6">
							<div class="form-group">
								{{ Form::label('paypal_username','Username',array('id'=>'','class'=>'')) }}
								{{ Form::text('paypal_username', $paypalUsername, array('id'=>'','class'=>'form-control')) }}
							</div>
							<div class="form-group">
								{{ Form::label('paypal_password','Password',array('id'=>'','class'=>'')) }}
								{{ Form::text('paypal_password', $paypalPassword, array('id'=>'','class'=>'form-control')) }}
							</div>
							<div class="form-group">
								{{ Form::label('paypal_signature','Signature',array('id'=>'','class'=>'')) }}
								{{ Form::text('paypal_signature', $paypalSignature, array('id'=>'','class'=>'form-control')) }}
							</div>
							<button type="submit" class="btn btn-success btn-block">Submit</button>
						</div>
						<div class="col-12 col-md-6">
							<p>Paypal API Documentation</p>
							<p>Without this key Paypal Payments will be disabled</p>
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
		<!-- Stripe -->
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-wrench fa-fw"></i> Stripe
			</div>
			<div class="card-body">
				<div class="row">
					{{ Form::open(array('url'=>'/admin/settings/api', 'onsubmit' => 'return ConfirmSubmit()', 'files' => 'true')) }}
						<div class="col-12 col-md-6">
							<div class="form-group">
								{{ Form::label('stripe_public_key','Public Key',array('id'=>'','class'=>'')) }}
								{{ Form::text('stripe_public_key', $stripePublicKey, array('id'=>'','class'=>'form-control')) }}
							</div>
							<div class="form-group">
								{{ Form::label('stripe_secret_key','Secret Key',array('id'=>'','class'=>'')) }}
								{{ Form::text('stripe_secret_key', $stripeSecretKey, array('id'=>'','class'=>'form-control')) }}
							</div>
							<button type="submit" class="btn btn-success btn-block">Submit</button>
						</div>
						<div class="col-12 col-md-6">
							<p>Stripe API Documentation</p>
							<p>Without this key Card Payments will be disabled</p>
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
</div>

@endsection