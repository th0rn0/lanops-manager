<nav class="navbar navbar-expand-md @if(Colors::isNavbarDark()) navbar-dark @else navbar-light @endif fixed-top custom-header">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<!-- <div class="navbar-header"> -->
			<button type="button" class="navbar-toggler collapsed" data-toggle="collapse" data-target="#topbar-navigation" aria-expanded="false">
				<span class="navbar-toggler-icon"></span>
			</button>
			<a class="navbar-brand" href="/"><img class="img-fluid" style="width:200px; height: auto; margin-top:-46px;" src="{{ Settings::getOrgLogo() }}"/></a>
		<!-- </div> -->

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="topbar-navigation">
			<ul class="navbar-nav ml-auto">
				@include ('layouts._partials._tournaments.navigation')
				@include ('layouts._partials.events-navigation')
				@if (Settings::isGalleryEnabled())
				<li class="nav-item"><a class="nav-link" href="/gallery">@lang('layouts.navi_gallery')</a></li>
				@endif
				@if (Settings::isShopEnabled())
					<li class="nav-item"><a class="nav-link" href="/shop">@lang('layouts.navi_shop')</a></li>
				@endif
				@if (Settings::isHelpEnabled())
				<li class="nav-item"><a class="nav-link" href="/help">@lang('layouts.navi_help')</a></li>
				@endif
				@if (Auth::check())
					@include ('layouts._partials.user-navigation')
				@else
					<li class="nav-item"><a class="nav-link" href="/login">@lang('layouts.navi_login')</a></li>
				@endif
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>