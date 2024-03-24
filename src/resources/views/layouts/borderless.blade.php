<!DOCTYPE html>
<html lang="en" class="full-height">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>
			@hasSection ('page_title')
				@yield('page_title') | {{ config('app.name') }}
			@else
				{{ config('app.tagline') }} | {{ config('app.name') }}
			@endif
		</title>
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css' />
		<link href="/css/app.css" rel=stylesheet />
	</head>
	<body class="full-height">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<img class="img-responsive" style="margin-top:-211px; margin-bottom:-259px;" src="{{ config('app.logo') }}"/>
				</div>
			</div>
		</div>
		@yield ('content')
	</body>
</html>
