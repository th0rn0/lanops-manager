<header class="navbar sticky-top bg-dark flex-md-nowrap p-0 shadow" data-bs-theme="dark">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white" href="#">{{ Settings::getOrgName() }} Admin</a>

    <!-- <li class="nav-item text-nowrap">
      <button class="nav-link px-3 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSearch" aria-controls="navbarSearch" aria-expanded="false" aria-label="Toggle search">
        <svg class="bi"><use xlink:href="#search"></use></svg>
      </button>
    </li> -->
      <button class="nav-link d-md-none px-3 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
	  <i class="fa fa-list"></i>
      </button>

  <!-- <div id="navbarSearch" class="navbar-search w-100 collapse">
    <input class="form-control w-100 rounded-0 border-0" type="text" placeholder="Search" aria-label="Search">
  </div> -->


  <ul class="nav ms-auto top-nav">
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
			<i class="fa fa-user"></i>
			{{ $user->username }}
		</a>
		<div class="dropdown-menu">
			<a class="dropdown-item" href="/account"><i class="fa fa-fw fa-user"></i> Profile</a>
			<div class="divider"></div>
			<a class="dropdown-item" href="/logout"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
			</div>
		</li>
	</ul>
</header>