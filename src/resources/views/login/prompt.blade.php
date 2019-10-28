@extends ('layouts.default')

@section ('page_title', 'Login to continue')

@section ('content')

	<div class="container">
		<div class="page-header">
			<h1>Please Login</h1> 
		</div>
		<p>Use one of the login methods below to continue or <a href="/register/standard">register</a></p>
		@if (in_array('steam', $activeLoginMethods))
			<a href="/login/steam">
				<img class="img img-responsive" src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_01.png">
			</a>
		@endif
		@if (in_array('standard', $activeLoginMethods))
			<label for="inputEmail" class="sr-only">Email address</label>
	        <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
	        <label for="inputPassword" class="sr-only">Password</label>
	        <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
	        <div class="checkbox">
	          	<label>
	            	<input type="checkbox" value="remember-me"> Remember me
	          	</label>
	        </div>
	        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		@endif
	</div>

@endsection