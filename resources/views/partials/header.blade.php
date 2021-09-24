<div class="page-header">
	<!-- BEGIN HEADER TOP -->
	<div class="page-header-top">
		<div class="container">
			<!-- BEGIN LOGO -->
			<div class="page-logo">
				<a href="{{ url('/') }}"><img src="{{ asset('imgs/logo.png') }}" alt="logo" class="logo-default"></a>
			</div>
			<!-- END LOGO -->
			<!-- BEGIN RESPONSIVE MENU TOGGLER -->
			<a href="javascript:;" class="menu-toggler"></a>
			<!-- END RESPONSIVE MENU TOGGLER -->
			<!-- BEGIN TOP NAVIGATION MENU -->
			<div class="top-menu">
				<ul class="nav navbar-nav pull-right">
                    @if (Auth::user()->role == 1)
                    <li class="dropdown dropdown-user dropdown-dark">
						<a href="{{ url('/admin') }}"  class="dropdown-toggle">

                        <span class="label label-danger username " style="color: white;" >Admin panel</span>
						</a>
					</li>
                    <li class="droddown dropdown-separator">
						<span class="separator"></span>
					</li>
                    @elseif (Auth::user()->role == 2)
                    <li class="dropdown dropdown-user dropdown-dark">
						<a href="{{ url('/seller') }}"  class="dropdown-toggle">

                        <span class="label label-danger username " style="color: white;" >Seller panel</span>
						</a>
					</li>
                    <li class="droddown dropdown-separator">
						<span class="separator"></span>
					</li>
                    @endif
					<!-- BEGIN TODO DROPDOWN -->
					<li class="dropdown dropdown-user dropdown-dark">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="fa fa-hdd-o"></i>
						<span class="username username-hide-mobile"> Tickets & Reports  <span class="badge badge-roundless badge-danger" id="total_span"></span></span>
						</a>
						<ul class="dropdown-menu dropdown-menu-default">
							<li>
								<a href="{{ url('/ticket') }}">
								<i class="icon-clock"></i> My Tickets <span class="badge badge-roundless badge-success" id="ticket_span"></span> </a>
							</li>
							<li>
								<a href="{{ url('/report') }}">
								<i class="icon-clock"></i> My Reports <span class="badge badge-roundless badge-danger" id="report_span"></span> </a>
							</li>
						</ul>
					</li>
					<!-- END TODO DROPDOWN -->
                    <li class="droddown dropdown-separator">
						<span class="separator"></span>
					</li>
					<li class="dropdown dropdown-user dropdown-dark">
						<a href="{{ url('/balance') }}"  class="dropdown-toggle">

                        <span class="label label-danger username " style="color: white;" >$<b class="balance_span"></b> <i class="fa fa-plus"></i></span>
						</a>
					</li>
					<li class="droddown dropdown-separator">
						<span class="separator"></span>
					</li>
					<!-- BEGIN USER LOGIN DROPDOWN -->
					<li class="dropdown dropdown-user dropdown-dark">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="icon-user"></i> <span class="username username-hide-mobile bold">{{ Auth::user()->name }}</span>
						</a>
						<ul class="dropdown-menu dropdown-menu-default">
							<li>
								<a href="{{ url('/setting') }}">
								<i class="icon-settings"></i> Settings </a>
							</li>
							<li>
								<a href="{{ url('/orders') }}">
								<i class="icon-basket"></i> Orders </a>
							</li>
							<li>
								<a href="{{ url('/balance') }}">
								<i class="fa fa-money"></i> Add Balance
								<span class="badge badge-danger balance_span"> </span>
								</a>
							</li>
							<li>
								<a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
								<i class="icon-key"></i> Log Out </a>
                                <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
							</li>
						</ul>
					</li>
					<!-- END USER LOGIN DROPDOWN -->
				</ul>
			</div>
			<!-- END TOP NAVIGATION MENU -->
		</div>
	</div>
	<!-- END HEADER TOP -->
	<!-- BEGIN HEADER MENU -->
	<div class="page-header-menu">
		<div class="container">
			<!-- BEGIN MEGA MENU --><div class="hor-menu ">
				<ul class="nav navbar-nav">
                    @foreach ($buyer_menus as $menu)
                    <li class="menu-dropdown mega-menu-dropdown">
						<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
                            <i class="{{ $menu['icon'] }}"></i>{{ $menu['name'] }} <i class="fa fa-angle-down"></i>
						</a>
						<ul class="dropdown-menu">
                            @foreach ($menu['children'] as $ch_menu)
                            <li>
                                <a href="{{ url('/'.$menu['url'].'/'.$ch_menu->url) }}">
                                    <i class="{{ $ch_menu->icon }}"></i> {{ $ch_menu->name }}
                                    <span class="badge badge-danger" id="{{ $ch_menu->url }}_span">0</span>
                                </a>

							</li>
                            @endforeach

						</ul>
					</li>
                    @endforeach

				</ul>
			</div>
			<!-- END MEGA MENU -->
		</div>
	</div>
	<!-- END HEADER MENU -->
</div>
