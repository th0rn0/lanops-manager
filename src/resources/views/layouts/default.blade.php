<!DOCTYPE html>
<html lang="en" class="full-height">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/png" sizes="32x32" href="{{ Settings::getOrgFavicon() }}">
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700&display=swap' rel='stylesheet' type='text/css' />
		<link href="/css/app.css?v={{ Helpers::getCssVersion() }}" rel=stylesheet />
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" rel="stylesheet">
    	
    	    {!! SEOMeta::generate() !!}
		    {!! OpenGraph::generate() !!}

		{!! Analytics::render() !!}
		
		@if(config('facebook-pixel.enabled'))
		    <!-- Facebook Pixel Code -->
		    <script>
		        !function(f,b,e,v,n,t,s)
		        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		            n.queue=[];t=b.createElement(e);t.async=!0;
		            t.src=v;s=b.getElementsByTagName(e)[0];
		            s.parentNode.insertBefore(t,s)}(window, document,'script',
		            'https://connect.facebook.net/en_US/fbevents.js');
		        fbq('init', "{{ config('facebook-pixel.facebook_pixel_id') }}");
		        fbq('track', 'PageView');
		    </script>
		    <noscript><img height="1" width="1" style="display:none"
		                   src="https://www.facebook.com/tr?id={{ config('facebook-pixel.facebook_pixel_id') }}&ev=PageView&noscript=1"
		        /></noscript>
		    <!-- End Facebook Pixel Code -->
		@endif
		
		<title>
			@hasSection ('page_title')
				@yield ('page_title') | {{ Settings::getOrgName() }}
			@else
				{{ Settings::getOrgTagline() }} | {{ Settings::getOrgName() }}
			@endif
		</title>
	</head>
	<body class="full-height">
		@include ('layouts._partials.navigation')
		<div class="container" style="margin-top:30px;">
			<div class='row'>
				@foreach (['danger', 'warning', 'success', 'info'] as $msg)
					@if (Session::has('alert-' . $msg))
						<div class="col-xs-12" style="margin-top:30px; margin-bottom:-40px;">
							<p class="alert alert-{{ $msg }}">
								<b>{{ Session::get('alert-' . $msg) }}</b> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							</p>
						</div>
					@endif
				@endforeach
				@if (isset($errors) && $errors->any())
					<div class="col-xs-12" style="margin-top:30px; margin-bottom:-40px;">
						<div class="alert alert-danger">
							<ul class="list-unstyled">
								@foreach ($errors->all() as $error)
									<li><strong>{{ $error }}</strong></li>
								@endforeach
							</ul>
						</div>
					</div>
				@endif
			</div>
		</div>
		@yield ('content')
		<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
		<br>
		<div class="stats  section-padding">
			<div class="container">
				<div class="row">
					<div class="col-md-4  text-center">
						<div class="stats-number">
							{{ Helpers::getEventTotal() }}
						</div>
						<hr />
						<div class="stats-title">
							@lang('layouts.events_weve_hosted')
						</div>
					</div>

					<div class="col-md-4  text-center">
						<div class="stats-number">
							{{ Helpers::getEventParticipantTotal() }}
						</div>
						<hr />
						<div class="stats-title">
							@lang('layouts.players_weve_entertained')
						</div>
					</div>

					<div class="col-md-4  text-center">
						<div class="stats-number">
							@lang('layouts.a_lot')
						</div>
						<hr />
						<div class="stats-title">
							{{ Settings::getFrontpageAlotTagline() }}
						</div>
					</div>
				</div>
			</div>
		</div>
		<footer class="footer">
			<div class="container">
				<div class="hidden-xs hidden-sm">
					<br><br>
				</div>
				<div class="col-lg-4 hidden-md hidden-sm hidden-xs">
					<img class="img-responsive" src="{{ Settings::getOrgLogo() }}">
				</div>
				<div class="col-lg-8 col-sm-12 col-md-12 text-center">
					<div class="col-lg-6 col-md-6">
						<h2 class="">@lang('layouts.default_links')</h2>
						<p class=" hidden"><a href="/contact">@lang('layouts.default_contact_us')</a></p>
						<p class=""><a href="/news">@lang('layouts.default_news')</a></p>
						<p class=""><a href="/terms">@lang('layouts.default_terms_and_conditions')</a></p>
						<p class=""><a href="/legalnotice">@lang('layouts.default_legal_and_privacy')</a></p>
						<p class=""><a href="/about">@lang('layouts.default_about_us')</a></p>
						<p class=""><a href="/polls">@lang('layouts.default_polls')</a></p>
						<p class=" hidden">@lang('layouts.default_lan_guide')</p>
					</div>
					<div class="col-lg-6 col-md-6">
						<h2 class="">Connect</h2>
						@if (Settings::getFacebookLink() != "")
							<p class=""><a target="_blank" href="{{ Settings::getFacebookLink() }}">@lang('layouts.default_facebook')</a></p>
						@endif
						@if (Settings::getDiscordLink() != "")
							<p class=""><a target="_blank" href="{{ Settings::getDiscordLink() }}">@lang('layouts.default_discord')</a></p>
						@endif
						@if (Settings::getSteamLink() != "")
							<p class=""><a target="_blank" href="{{ Settings::getSteamLink() }}">@lang('layouts.default_steam')</a></p>
						@endif
						@if (Settings::getTwitterLink() != "")
							<p class=""><a target="_blank" href="{{ Settings::getTwitterLink() }}">@lang('layouts.default_twitter')</a></p>
						@endif
						@if (Settings::getRedditLink() != "")
						<p class=""><a target="_blank" href="{{ Settings::getRedditLink() }}">@lang('layouts.default_reddit')</a></p>
						@endif
					</div>
					<div class="col-lg-12">
						<p class="">© {{ Settings::getOrgName() }} {{ date("Y") }}. @lang('layouts.default_rights_reserved')</p>
					</div>
				</div>
				<div class="col-lg-12 text-center">
					<p class="">Powered By <a href="https://eventula.com">Eventula Event Manager</a></p>
				</div>
			</div>
		</footer>
	</body>
</html>
