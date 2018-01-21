<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="/">{{ Settings::getOrgName() }} Admin</a>
  </div>
  <!-- Top Menu Items -->
  <ul class="nav navbar-right top-nav">
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ $user->steamname }} <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li>
          <a href="/account"><i class="fa fa-fw fa-user"></i> Profile</a>
        </li>
        <li class="divider"></li>
        <li>
          <a href="/logout"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
        </li>
      </ul>
    </li>
  </ul>
  <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav">
      <li>
          <a href="/admin"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
      </li>
      <li>
        <a href="/admin/users"><i class="fa fa-user fa-fw"></i> Users</a>
      </li>
      <li>
        <a href="/admin/venues"><i class="fa fa-building fa-fw"></i> Venues</a>
      </li>
      <li>
        <a href="/admin/events"><i class="fa fa-book fa-fw"></i> Events</a>
        <ul class="nav nav-second-level">
          @foreach (Helpers::getEvents('DESC', 5, true) as $event)
            <li>
              <a href="/admin/events/{{ $event->slug }}">
                {{ $event->display_name }}
                @if ($event->status != 'PUBLISHED')
                  - {{ $event->status }}
                @endif
              </a>
            </li>
          @endforeach
        </ul>
        <!-- /.nav-second-level -->
      </li>
      <li>
        <a href="/admin/gallery"><i class="fa fa-camera fa-fw"></i> Gallery</a>
      </li>
      <li>
        <a><i class="fa fa-credit-card fa-fw"></i> Purchases - TBC</a>
      </li>
      <li>
        <a href="/admin/settings"><i class="fa fa-wrench" aria-hidden="true"></i> Settings/Misc</a> 
      </li>
    </ul>
  </div>
  <!-- /.navbar-collapse -->
</nav>