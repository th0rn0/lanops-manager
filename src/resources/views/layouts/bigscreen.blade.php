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
		<link href="/css/app.css" rel=stylesheet />
    	
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
		@yield ('content')
		<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	</body>
</html>
