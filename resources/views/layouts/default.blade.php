<!DOCTYPE html>
<html lang="en" class="full-height">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/png" sizes="32x32" href="{{ Settings::getOrgFavicon() }}">
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css' />
		<link href="/css/app.css" rel=stylesheet />
		
		{!! Analytics::render() !!}
		
		<title>
			@hasSection ('page_title')
				@yield ('page_title') | {{ Settings::getOrgName() }}
			@else
				Home of LAN gaming in Yorkshire | {{ Settings::getOrgName() }}
			@endif
		</title>
	</head>
	<body class="full-height">
		@include ('layouts._partials.navigation')
		@yield ('content')
		<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
		<!-- // TODO MOVE ME -->
		<style>
			.footer {
				height: 200px;
				background-color: #333333;
			}
		</style>
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
							LANs we've hosted
						</div>
					</div>

					<div class="col-md-4  text-center">
						<div class="stats-number">
							{{ Helpers::getEventParticipantTotal() }}
						</div>
						<hr />
						<div class="stats-title">
							GAMERs we've entertained
						</div>
					</div>

					<div class="col-md-4  text-center">
						<div class="stats-number">
							A LOT
						</div>
						<hr />
						<div class="stats-title">
							PIZZAs we've ordered
						</div>
					</div>
				</div>
			</div>
		</div>
		<footer class="footer" style="height:30%">
			<div class="container">
				<div class="hidden-xs hidden-sm">
					<br><br><br>
				</div>
				<div class="col-lg-4 hidden-md hidden-sm hidden-xs">
					<img class="img-responsive" src="{{ Settings::getOrgLogo() }}">
				</div>
				<div class="col-lg-8 col-sm-12 col-md-12 text-center">
					<div class="col-lg-6 col-md-6">
						<h2 class="text-muted">Help</h2>
						<p class="text-muted hidden"><a href="/contact">Contact Us</a></p>
						<p class="text-muted"><a href="/about">About Us</a></p>
						<p class="text-muted hidden">Lan Guide</p>
					</div>
					<div class="col-lg-6 col-md-6">
						<h2 class="text-muted">Connect</h2>
						<p class="text-muted"><a target="_blank" href="{{ Settings::getFacebookLink() }}">Facebook</a></p>
						<p class="text-muted"><a target="_blank" href="{{ Settings::getDiscordLink() }}">Discord</a></p>
						<p class="text-muted"><a target="_blank" href="{{ Settings::getSteamLink() }}">Steam</a></p>
					</div>
					<div class="col-lg-12">
						<p class="text-muted">© {{ Settings::getOrgName() }} {{ date("Y") }}. All rights reserved.</p>
					</div>
				</div>
			</div>
		</footer>
	</body>
</html>
