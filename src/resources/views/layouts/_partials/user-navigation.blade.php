<li class="dropdown">
	<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $user->username }} @if (Settings::isCreditEnabled()) ({{ $user->credit_total }}) @endif<span class="caret"></span></a>
	<ul class="dropdown-menu">
		@if ( $user->admin == 1 )
			<li><a href="/admin">@lang('layouts.user_navi_admin')</a></li>
		@endif
		<li><a href="/account">@lang('layouts.user_navi_profile')</a></li>
		<li role="separator" class="divider"></li>
		<li><a href="/logout">@lang('layouts.user_navi_logout')</a></li>
	</ul>
</li>