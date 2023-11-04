<li class="nav-item dropdown">
	<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $user->username }} @if (Settings::isCreditEnabled()) ({{ $user->credit_total }}) @endif</a>
	<div class="dropdown-menu">
		@if ( $user->admin == 1 )
			<a class="dropdown-item" href="/admin">@lang('layouts.user_navi_admin')</a>
		@endif
		<a class="dropdown-item" href="/account">@lang('layouts.user_navi_profile')</a>
		<div role="separator" class="dropdown-divider"></div>
		<a class="dropdown-item" href="/logout">@lang('layouts.user_navi_logout')</a>
	</div>
</li>