@extends ('layouts.admin-default')

@section ('page_title', 'Settings')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">API</h3>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/settings">Settings</a>
			</li>
			<li class="active">
				API
			</li>
		</ol> 
	</div>
</div>

@include ('layouts._partials._admin._settings.dashMini', ['active' => 'auth'])

<div class="row">
	<div class="col-xs-12">
		<div class="alert alert-info">
			Be careful! Changes these settings could break the site!
		</div>
	</div>
	<!-- Challonge -->
	<div class="col-xs-12 col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Challonge
			</div>
			<div class="panel-body">
				<div class="row">
					{{ Form::open(array('url'=>'/admin/settings/api', 'onsubmit' => 'return ConfirmSubmit()', 'files' => 'true')) }}
					 {{ csrf_field() }}
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								{{ Form::label('challonge_api_key','API Key',array('id'=>'','class'=>'')) }}
								{{ Form::text('challonge_api_key', $challongeApiKey, array('id'=>'','class'=>'form-control')) }}
							</div>
							<button type="submit" class="btn btn-success btn-block">Submit</button>
						</div>
						<div class="col-xs-12 col-md-6">
							<p>Challonge API Documentation</p>
							<p>Without this key Tournaments will be disabled</p>
							<p>https://challonge.com/settings/developer</p>
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
		<!-- Steam API Key -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Steam API Key
			</div>
			<div class="panel-body">
				<div class="row">
					{{ Form::open(array('url'=>'/admin/settings/api', 'onsubmit' => 'return ConfirmSubmit()', 'files' => 'true')) }}
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								{{ Form::label('steam_api_key','API Key',array('id'=>'','class'=>'')) }}
								{{ Form::text('steam_api_key', $steamApiKey, array('id'=>'','class'=>'form-control')) }}
							</div>
							<button type="submit" class="btn btn-success btn-block">Submit</button>
						</div>
						<div class="col-xs-12 col-md-6">
							<p>Steam API Documentation</p>
							<p>Without this key Steam Login will be disabled</p>
							<p>https://steamcommunity.com/dev/apikey</p>
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
		<!-- Facebook -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Facebook
			</div>
			<div class="panel-body">
				<div class="row">
					{{ Form::open(array('url'=>'/admin/settings/api', 'onsubmit' => 'return ConfirmSubmit()', 'files' => 'true')) }}
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								{{ Form::label('facebook_app_id','App Id',array('id'=>'','class'=>'')) }}
								{{ Form::text('facebook_app_id', $facebookAppId, array('id'=>'','class'=>'form-control')) }}
							</div>
							<div class="form-group">
								{{ Form::label('facebook_app_secret','App Secret',array('id'=>'','class'=>'')) }}
								{{ Form::text('facebook_app_secret', '', array('placeholder'=>'Obfuscated','id'=>'','class'=>'form-control')) }}
							</div>
							<button type="submit" class="btn btn-success btn-block">Submit</button>
						</div>
						<div class="col-xs-12 col-md-6">
							<p>Facebook API Documentation</p>
							<p>Without this key Facebook Posting will be disabled</p>
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
	<!-- Paypal -->
	<div class="col-xs-12 col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Paypal
			</div>
			<div class="panel-body">
				<div class="row">
					{{ Form::open(array('url'=>'/admin/settings/api', 'onsubmit' => 'return ConfirmSubmit()', 'files' => 'true')) }}
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								{{ Form::label('paypal_username','Username',array('id'=>'','class'=>'')) }}
								{{ Form::text('paypal_username', $paypalUsername, array('id'=>'','class'=>'form-control')) }}
							</div>
							<div class="form-group">
								{{ Form::label('paypal_password','Password',array('id'=>'','class'=>'')) }}
								{{ Form::text('paypal_password', '', array('placeholder'=>'Obfuscated','id'=>'','class'=>'form-control')) }}
							</div>
							<div class="form-group">
								{{ Form::label('paypal_signature','Signature',array('id'=>'','class'=>'')) }}
								{{ Form::text('paypal_signature', $paypalSignature, array('id'=>'','class'=>'form-control')) }}
							</div>
							<button type="submit" class="btn btn-success btn-block">Submit</button>
						</div>
						<div class="col-xs-12 col-md-6">
							<p>Paypal API Documentation</p>
							<p>Without this key Paypal Payments will be disabled</p>
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
		<!-- Stripe -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Stripe
			</div>
			<div class="panel-body">
				<div class="row">
					{{ Form::open(array('url'=>'/admin/settings/api', 'onsubmit' => 'return ConfirmSubmit()', 'files' => 'true')) }}
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								{{ Form::label('stripe_public_key','Public Key',array('id'=>'','class'=>'')) }}
								{{ Form::text('stripe_public_key', $stripePublicKey, array('id'=>'','class'=>'form-control')) }}
							</div>
							<div class="form-group">
								{{ Form::label('stripe_secret_key','Secret Key',array('id'=>'','class'=>'')) }}
								{{ Form::text('stripe_secret_key', '', array('placeholder'=>'Obfuscated','id'=>'','class'=>'form-control')) }}
							</div>
							<button type="submit" class="btn btn-success btn-block">Submit</button>
						</div>
						<div class="col-xs-12 col-md-6">
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