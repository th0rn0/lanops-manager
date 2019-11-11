@extends ('layouts.admin-default')

@section ('page_title', 'Settings')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Settings</h1>
		<ol class="breadcrumb">
			<li class="active">
				Settings
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-lg-6 col-xs-12">
		<!-- Main -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Main
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>Setting</th>
								<th>Value</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($settings as $setting)
								@if (
									strpos($setting->setting, 'about') === false && 
									strpos($setting->setting, 'terms_and_conditions') === false &&
									strpos($setting->setting, 'org_') === false &&
									strpos($setting->setting, 'slider_') === false &&
									strpos($setting->setting, 'payment') === false &&
									strpos($setting->setting, 'credit') === false &&
									strpos($setting->setting, 'login') === false &&
									$setting->setting != 'currency' &&
									$setting->setting != 'shop_enabled' &&
									$setting->setting != 'social_facebook_page_access_token'
								)
									<tr>
										{{ Form::open(array('url'=>'/admin/settings/', 'onsubmit' => 'return ConfirmSubmit()')) }}
											<td>
												{{ ucwords(str_replace("_"," ",$setting->setting)) }}<br>
												@if ($setting->description != null)
													<small>{{ $setting->description }}</small>
												@endif
											</td>
											<td>
												{{ Form::text($setting->setting, $setting->value ,array('id'=>'setting','class'=>'form-control')) }}
											</td>
											<td>
												<button type="submit" class="btn btn-default btn-sm btn-block">Update</button>
											</td>
										{{ Form::close() }}
										{{ Form::open(array('url'=>'/admin/settings/', 'onsubmit' => 'return ConfirmDelete()')) }}
											<td width="15%">
												@if (!$setting->default)
													{{ Form::hidden('_method', 'DELETE') }}
													<button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
												@endif
											</td>
										{{ Form::close() }}
									</tr>
								@endif
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-xs-12">
		<!-- Shop System -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-info-circle fa-fw"></i> Shop System
			</div>
			<div class="panel-body">
				<p>The Shop can be used for buying merch, consumables etc.</p>
				@if ($isShopEnabled)
					{{ Form::open(array('url'=>'/admin/settings/shop/disable')) }}
						<button type="submit" class="btn btn-block btn-danger">Disable</button>
					{{ Form::close() }}
				@else
					{{ Form::open(array('url'=>'/admin/settings/shop/enable')) }}
						<button type="submit" class="btn btn-block btn-success">Enable</button>
					{{ Form::close() }}
				@endif
			</div>
		</div>
		<!-- Credit System -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-info-circle fa-fw"></i> Credit System
			</div>
			<div class="panel-body">
				<p>The Credit System is used to award participants with credit they can use to buy things from the shop and events. It can be award for buying tickets, attending events, participanting/winning tournaments or manually assigned.</p>
				@if ($isCreditEnabled)
					{{ Form::open(array('url'=>'/admin/settings/credit/disable')) }}
						<button type="submit" class="btn btn-block btn-danger">Disable</button>
					{{ Form::close() }}
				@else
					{{ Form::open(array('url'=>'/admin/settings/credit/enable')) }}
						<button type="submit" class="btn btn-block btn-success">Enable</button>
					{{ Form::close() }}
				@endif
			</div>
		</div>

		<!-- Social Media -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-info-circle fa-fw"></i> Social Media
			</div>
			<div class="panel-body">
				<p><small>Link Social Media your social media accounts to publish posts and pictures from the Lan Manager</small></p>
				<div class="row">
					<div class="col-xs-12 col-md-6">
						<h4>Facebook</h4>
						@if ($facebookCallback != null)
							<a href="{{ $facebookCallback }}">
								<button type="button" class="btn btn-block btn-success">Link Account</button>
							</a>
						@else
							{{ Form::open(array('url'=>'/admin/settings/unlink/facebook')) }}
								{{ Form::hidden('_method', 'DELETE') }}
								<button type="submit" class="btn btn-block btn-danger">Unlink Account</button>
							{{ Form::close() }}
						@endif
					</div>
					<div class="col-xs-12 col-md-6">
						<h4>Twitter <small>Coming soon</small></h4>
						{{ Form::open(array('url'=>'/admin/settings/link/twitter')) }}
							<button type="submit" class="btn btn-block btn-success" disabled>Link Account</button>
						{{ Form::close() }}
					</div>
				</div>
			</div>
		</div>
	 	<!-- Misc -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Misc
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/settings/generate/qr', 'onsubmit' => 'return ConfirmSubmit()')) }}
					<button type="submit" class="btn btn-danger btn-sm btn-block">Re generate QR Codes</button>
				{{ Form::close() }}
				@foreach (config() as $config)
					{{ dd($config) }}
				@endforeach
			</div>  
		</div>
	</div>
</div>
 
@endsection