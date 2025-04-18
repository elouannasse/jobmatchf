<nav id="sidebar" aria-label="Main Navigation">
    <!-- Side Header -->
    <div class="bg-header-dark">
        <div class="content-header bg-white-5">
            <!-- Logo -->
            <a class="fw-semibold text-white tracking-wide" href="{{ route('home') }}">
            <span class="smini-visible">
              J<span class="opacity-75">M</span>
            </span>
                <span class="smini-hidden">
              Job<span class="opacity-75">Match</span>
            </span>
            </a>
            <!-- END Logo -->

            <!-- Options -->
            <div>
                <!-- Toggle Sidebar Style -->
                <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="class-toggle"
                        data-target="#sidebar-style-toggler" data-class="fa-toggle-off fa-toggle-on"
                        onclick="Dashmix.layout('sidebar_style_toggle');Dashmix.layout('header_style_toggle');">
                    <i class="fa fa-toggle-off" id="sidebar-style-toggler"></i>
                </button>
                <!-- END Toggle Sidebar Style -->

                <!-- Dark Mode -->
                <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="class-toggle"
                        data-target="#dark-mode-toggler" data-class="far fa"
                        onclick="Dashmix.layout('dark_mode_toggle');">
                    <i class="far fa-moon" id="dark-mode-toggler"></i>
                </button>
                <!-- END Dark Mode -->

                <!-- Close Sidebar -->
                <button type="button" class="btn btn-sm btn-alt-secondary d-lg-none" data-toggle="layout"
                        data-action="sidebar_close">
                    <i class="fa fa-times-circle"></i>
                </button>
                <!-- END Close Sidebar -->
            </div>
            <!-- END Options -->
        </div>
    </div>
    <!-- END Side Header -->

    <!-- Sidebar Scrolling -->
    <div class="js-sidebar-scroll">
        <!-- Side Navigation -->
        <div class="content-side content-side-full">
            <ul class="nav-main">
                <li class="nav-main-item">
                    <a href="{{ route('home') }}" class="nav-main-link{{ request()->routeIs('home') ? ' active' : '' }}">
                        <i class="nav-main-link-icon fa fa-home"></i>
                        <span class="nav-main-link-name">Home</span>
                    </a>
                </li>

                @auth
                    <li class="nav-main-item">
                        <a href="{{ route('dashboard') }}" class="nav-main-link{{ request()->routeIs('dashboard') ? ' active' : '' }}">
                            <i class="nav-main-link-icon fa fa-location-arrow"></i>
                            <span class="nav-main-link-name">Dashboard</span>
                        </a>
                    </li>

                    @can('viewAny', App\Models\User::class)
                        <li class="nav-main-item">
                            <a href="{{ route('user.index') }}" class="nav-main-link{{ request()->routeIs('user.*') ? ' active' : '' }}">
                                <i class="nav-main-link-icon fa fa-user"></i>
                                <span class="nav-main-link-name">Utilisateurs</span>
                            </a>
                        </li>
                    @endcan

                    @can('viewAny', App\Models\Offre::class)
                        <li class="nav-main-item">
                            <a href="{{ route('offre.index') }}" class="nav-main-link{{ request()->routeIs('offre.*') ? ' active' : '' }}">
                                <i class="nav-main-link-icon fa fa-briefcase"></i>
                                <span class="nav-main-link-name">Offres</span>
                            </a>
                        </li>
                    @endcan

                    @can('viewAllCandidatures', App\Models\Candidature::class)
                        <li class="nav-main-item">
                            <a href="{{ route('candidature.index') }}" class="nav-main-link{{ request()->routeIs('candidature.*') ? ' active' : '' }}">
                                <i class="nav-main-link-icon fa fa-briefcase"></i>
                                <span class="nav-main-link-name">Candidatures</span>
                            </a>
                        </li>
                    @endcan

                    <li class="nav-main-item">
                        <a href="{{ route('profile.edit') }}" class="nav-main-link{{ request()->routeIs('profile.*') ? ' active' : '' }}">
                            <i class="nav-main-link-icon fa fa-user"></i>
                            <span class="nav-main-link-name">Profile</span>
                        </a>
                    </li>

                    @can('view-candidat-settings')
                        <li class="nav-main-heading">Paramètres</li>
                        <li class="nav-main-item{{ request()->is('candidat/*') ? ' open' : '' }}">
                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                               aria-expanded="true" href="#">
                                <i class="nav-main-link-icon fa fa-wrench"></i>
                                <span class="nav-main-link-name">Candidat</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->routeIs('langue.*') ? ' active' : '' }}" href="{{ route('langue.index') }}">
                                        <span class="nav-main-link-name">Langue</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->routeIs('competence.*') ? ' active' : '' }}" href="{{ route('competence.index') }}">
                                        <span class="nav-main-link-name">Competence</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->routeIs('experience.*') ? ' active' : '' }}" href="{{ route('experience.index') }}">
                                        <span class="nav-main-link-name">Experience</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->routeIs('formation.*') ? ' active' : '' }}" href="{{ route('formation.index') }}">
                                        <span class="nav-main-link-name">Formation</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    <li class="nav-main-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" class="nav-main-link"
                               onclick="event.preventDefault();this.closest('form').submit();">
                                <i class="nav-main-link-icon fa fa-arrow-alt-circle-left"></i>
                                <span class="nav-main-link-name">Sign Out</span>
                            </a>
                        </form>
                    </li>
                @endauth

                @guest
                    <li class="nav-main-item">
                        <a href="{{ route('login') }}" class="nav-main-link">
                            <i class="nav-main-link-icon fa fa-sign-in-alt"></i>
                            <span class="nav-main-link-name">Se connecter</span>
                        </a>
                    </li>
                @endguest
            </ul>
        </div>
        <!-- END Side Navigation -->
    </div>
    <!-- END Sidebar Scrolling -->
</nav>
