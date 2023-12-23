<li class="dropdown">
	<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $user->username }}<span class="caret"></span></a>
	<ul class="dropdown-menu">
		@if ( $user->admin == 1 )
			<li><a href="/admin">Admin</a></li>
		@endif
		<li><a href="/account">Profile</a></li>
		<li role="separator" class="divider"></li>
		<li><a href="/logout">Logout</a></li>
	</ul>
</li>