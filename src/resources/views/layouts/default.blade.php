<!DOCTYPE html>
<html lang="en" class="full-height">
	<head>
		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id={{ config('app.google_tag_id') }}"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', "{{ config('app.google_tag_id') }}");
		</script>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/png" sizes="32x32" href="{{ config('app.favicon') }}">
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css' />

		@vite(['resources/assets/sass/app.scss'])
    	
		<title>
			@hasSection ('page_title')
				@yield ('page_title') | {{ config('app.name') }}
			@else
				{{ config('app.tagline') }} | {{ config('app.name') }}
			@endif
		</title>
		<meta name="keywords" content="{{ config('app.seo_keywords') }}"/>
		<x-seo::meta />
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
							EVENTs we've hosted
						</div>
					</div>

					<div class="col-md-4  text-center">
						<div class="stats-number">
							{{ Helpers::getEventParticipantTotal() }}
						</div>
						<hr />
						<div class="stats-title">
							PLAYERs we've entertained
						</div>
					</div>

					<div class="col-md-4  text-center">
						<div class="stats-number">
							A LOT
						</div>
						<hr />
						<div class="stats-title">
							Pizzas We've Ordered
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
					<img class="img-responsive" src="{{ config('app.logo') }}" alt="{{ config('app.name') }} logo footer">
				</div>
				<div class="col-lg-8 col-sm-12 col-md-12 text-center">
					<div class="col-lg-6 col-md-6">
						<h2 class="">Links</h2>
						<p class=" hidden"><a href="/contact">Contact Us</a></p>
						<p class=""><a href="/news">News</a></p>
						<p class=""><a href="/info">Frequently Asked Questions</a></p>
						<p class=""><a href="/terms">Terms & Conditions</a></p>
						<p class=""><a href="/about">About Us</a></p>
						<p class=""><a href="/polls">Polls</a></p>
						<p class=" hidden">Lan Guide</p>
					</div>
					<div class="col-lg-6 col-md-6">
						<h2 class="">Connect</h2>
						@if (config('app.facebook_link') != "")
							<p class=""><a target="_blank" href="{{ config('app.facebook_link') }}">Facebook</a></p>
						@endif
						@if (config('app.bsky_link') != "")
							<p class=""><a target="_blank" href="{{ config('app.bsky_link') }}">BSKY</a></p>
						@endif
						@if (config('app.discord_link') != "")
							<p class=""><a target="_blank" href="{{ config('app.discord_link') }}">Discord</a></p>
						@endif
						@if (config('app.steam_link') != "")
							<p class=""><a target="_blank" href="{{ config('app.steam_link') }}">Steam</a></p>
						@endif
						@if (config('app.youtube_link') != "")
							<p class=""><a target="_blank" href="{{ config('app.youtube_link') }}">Youtube</a></p>
						@endif
					</div>
				</div>
				<div class="col-lg-12 text-center">
					<p class="">© {{ config('app.name') }} {{ date("Y") }}. All rights reserved.</p>
				</div>
			</div>
		</footer>
	</body>
</html>
