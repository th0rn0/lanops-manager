@extends ('layouts.default')

@section ('page_title', 'Login to continue')

@section ('content')

	<div class="container">
		<div class="page-header">
			<h1>Please Login to Continue</h1> 
		</div>
		<p>Use one of the login methods below to continue.</p>
		<a href="/login">
			<img class="img img-responsive" src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_01.png">
		</a>
	</div>

@endsection