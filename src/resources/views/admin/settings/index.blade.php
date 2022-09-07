@extends ('layouts.admin-default')

@section ('page_title', 'Settings')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Settings</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">
				Settings
			</li>
		</ol>
	</div>
</div>

@include ('layouts._partials._admin._settings.dashMini')

<div class="row">
	<div class="col-lg-6 col-12">
		<!-- Main -->
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-wrench fa-fw"></i> Main
			</div>
			<div class="card-body">
				<table class="table table-striped table-hover table-responsive">
					<thead>
						<tr>
							<th>Setting</th>
							<th>Value</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@foreach ($settings as $key=>$setting)
							@if (
								strpos($setting->setting, 'about') === false &&
								strpos($setting->setting, 'terms_and_conditions') === false &&
								strpos($setting->setting, 'org_') === false &&
								strpos($setting->setting, 'systems_') === false &&
								strpos($setting->setting, 'slider_') === false &&
								strpos($setting->setting, 'payment') === false &&
								strpos($setting->setting, 'credit') === false &&
								strpos($setting->setting, 'login') === false &&
								strpos($setting->setting, 'auth') === false &&
								strpos($setting->setting, 'shop') === false &&
								strpos($setting->setting, 'gallery') === false &&
								strpos($setting->setting, 'help') === false &&
								strpos($setting->setting, 'matchmaking_enabled') === false &&
								strpos($setting->setting, 'seo') === false &&
								strpos($setting->setting, 'privacy_policy') === false &&
								strpos($setting->setting, 'legal_notice') === false &&
								strpos($setting->setting, 'theme') === false &&
								$setting->setting != 'currency' &&
								$setting->setting != 'social_facebook_page_access_token' &&
								$setting->setting != 'installed'
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
											{{ Form::text($setting->setting, $setting->value ,array('id'=>'setting' . $key,'class'=>'form-control')) }}
										</td>
										<td>
											<button type="submit" class="btn btn-success btn-sm btn-block">Update</button>
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
	<div class="col-lg-6 col-12">
		<!-- Shop System -->
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-info-circle fa-fw"></i> Shop System
			</div>
			<div class="card-body">
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
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-info-circle fa-fw"></i> Credit System
			</div>
			<div class="card-body">
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

		<!-- Gallery System -->
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-info-circle fa-fw"></i> Gallery System
			</div>
			<div class="card-body">
				<p>The Gallery can be used for uploading pictures.</p>
				@if ($isGalleryEnabled)
					{{ Form::open(array('url'=>'/admin/settings/gallery/disable')) }}
						<button type="submit" class="btn btn-block btn-danger">Disable</button>
					{{ Form::close() }}
				@else
					{{ Form::open(array('url'=>'/admin/settings/gallery/enable')) }}
						<button type="submit" class="btn btn-block btn-success">Enable</button>
					{{ Form::close() }}
				@endif
			</div>
		</div>

		<!-- Help System -->
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-info-circle fa-fw"></i> Help System
			</div>
			<div class="card-body">
				<p>The Help System can be used to populate help articles.</p>
				@if ($isHelpEnabled)
					{{ Form::open(array('url'=>'/admin/settings/help/disable')) }}
						<button type="submit" class="btn btn-block btn-danger">Disable</button>
					{{ Form::close() }}
				@else
					{{ Form::open(array('url'=>'/admin/settings/help/enable')) }}
						<button type="submit" class="btn btn-block btn-success">Enable</button>
					{{ Form::close() }}
				@endif
			</div>
		</div>

		<!-- Matchmaking System -->
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-info-circle fa-fw"></i> Matchmaking System
			</div>
			<div class="card-body">
				<p>The Matchmaking feature can be used to make matches by admins or users without the need of an event tournament.</p>
				@if ($isMatchMakingEnabled)
					{{ Form::open(array('url'=>'/admin/settings/matchmaking/disable')) }}
						<button type="submit" class="btn btn-block btn-danger">Disable</button>
					{{ Form::close() }}
				@else
					{{ Form::open(array('url'=>'/admin/settings/matchmaking/enable')) }}
						<button type="submit" class="btn btn-block btn-success">Enable</button>
					{{ Form::close() }}
				@endif
			</div>
		</div>

		<!-- Social Media -->
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-info-circle fa-fw"></i> Social Media
			</div>
			<div class="card-body">
				<p><small>Link Social Media your social media accounts to publish posts and pictures from the Lan Manager</small></p>
				<div class="row">
					<div class="col-12 col-md-6">
						<h4>Facebook</h4>
						@if (!$facebookIsLinked)
							<a href="{{ $facebookCallback }}">
								<button type="button" class="btn btn-block btn-success" @if($facebookCallback == null) disabled @endif>Link Account</button>
							</a>
						@else
							{{ Form::open(array('url'=>'/admin/settings/unlink/facebook')) }}
								{{ Form::hidden('_method', 'DELETE') }}
								<button type="submit" class="btn btn-block btn-danger" >Unlink Account</button>
							{{ Form::close() }}
						@endif
					</div>
					<div class="col-12 col-md-6">
						<h4>Twitter <small>Coming soon</small></h4>
						{{ Form::open(array('url'=>'/admin/settings/link/twitter')) }}
							<button type="submit" class="btn btn-block btn-success" disabled>Link Account</button>
						{{ Form::close() }}
					</div>
				</div>
			</div>
		</div>
	 	<!-- Misc -->
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-wrench fa-fw"></i> Misc
			</div>
			<div class="card-body">
				<div class="mb-3">
					{{ Form::open(array('url'=>'/admin/settings/generate/qr', 'onsubmit' => 'return ConfirmSubmit()')) }}
						<button type="submit" class="btn btn-danger btn-sm btn-block">Re generate QR Codes</button>
					{{ Form::close() }}
				</div>
				<div class="mb-3">
					{{ Form::open(array('url'=>'/admin/settings/generate/newqr', 'onsubmit' => 'return ConfirmSubmit()')) }}
						<button type="submit" class="btn btn-danger btn-sm btn-block">Re generate QR Codes with new urls</button>
					{{ Form::close() }}
				</div>
				@foreach (config() as $config)
					{{ dd($config) }}
				@endforeach
			</div>
		</div>
	</div>
	<div class="col-12">
		<!-- Shop System -->
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-info-circle fa-fw"></i> SEO & Analytics
			</div>
			<div class="card-body">
				{{ Form::open(array('url'=>'/admin/settings/', 'onsubmit' => 'return ConfirmSubmit()')) }}
					<div class="row">
						<div class="form-group col-12">
							{{ Form::label('seo_keywords', "SEO Keywords" ,array('id'=>'','class'=>'')) }}
							{{ Form::text("seo_keywords", implode(', ', explode(',', Settings::getSeoKeywords())) ,array('id'=>'setting_seo_keywords','class'=>'form-control')) }}
							<small>Separate each keyword with a Comma.</small>
						</div>
						<div class="form-group col-12 col-md-6">
							{{ Form::label('analytics_google_id', "Google Analyics ID" ,array('id'=>'','class'=>'')) }}
							{{ Form::text("analytics_google_id", config('analytics.configurations.GoogleAnalytics.tracking_id') ,array('id'=>'setting_analytics_google_id','class'=>'form-control')) }}
						</div>
						<div class="form-group col-12 col-md-6">
							{{ Form::label('analytics_facebook_pixel', "Facebook Pixel ID" ,array('id'=>'','class'=>'')) }}
							{{ Form::text("analytics_facebook_pixel", config('facebook-pixel.facebook_pixel_id') ,array('id'=>'setting_analytics_facebook_pixel','class'=>'form-control')) }}
						</div>
					</div>
					<button type="submit" class="btn btn-success btn-sm btn-block">Update</button>
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>

@endsection