<nav class="navbar navbar-expand-md flex-md-nowrap sticky-top custom-header p-0 shadow" @if(Colors::isNavbarDark())data-bs-theme="dark" @else data-bs-theme="light" @endif>

	

	<div class="container-fluid">
		<button type="button" class="navbar-toggler collapsed" data-bs-toggle="collapse" data-bs-target="#topbar-navigation" aria-expanded="false">
			<span class="navbar-toggler-icon"></span>
		</button>
		<a class="navbar-brand" style="padding:3px 0px; "href="/">
			<picture>
				<source srcset="{{ Settings::getOrgLogo() }}.webp" type="image/webp">
				<source srcset="{{ Settings::getOrgLogo() }}" type="image/jpeg">
				<img style="height:100% " src="{{ Settings::getOrgLogo() }}"/>
			</picture>
		</a>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="topbar-navigation">
			<ul class="navbar-nav ms-auto">
				@if (Auth::check())
					@include ('layouts._partials.user-navigation')
				@else
					<li class="nav-item"><a class="nav-link" href="/login">@lang('layouts.navi_login')</a></li>
				@endif
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>