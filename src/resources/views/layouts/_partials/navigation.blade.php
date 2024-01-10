<nav class="navbar navbar-default navbar-inverse navbar-fixed-top custom-header">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#topbar-navigation" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/"><img class="img-responsive" style="width:200px; height: auto; margin-top:-46px;" src="{{ Settings::getOrgLogo() }}"/></a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="topbar-navigation">
			<ul class="nav navbar-nav navbar-right">
				@include ('layouts._partials._tournaments.navigation')

				@include ('layouts._partials.events-navigation')

				<li><a href="/gallery">Gallery</a></li>
				@if (Auth::check())
					@include ('layouts._partials.user-navigation')
				@else
					<li><a href="/login">Login</a></li>
				@endif
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>