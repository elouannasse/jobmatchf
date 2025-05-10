<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Job Match
    </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout-fix.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fixed-modals.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Additional custom styles -->
    @stack('styles')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/global-delete.js') }}"></script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 p-0 m-0">
        <div class="wrapper">
            <!-- Sidebar -->
            <nav id="sidebar" class="sidebar">
                <!-- Sidebar Logo -->
                <div class="sidebar-header">
                    <h3>JobMatch</h3>
                </div>

                <!-- Sidebar Navigation -->
                <div class="sidebar-nav">
                    <ul class="list-unstyled components">
                        <li class="{{ request()->is('home') ? 'active' : '' }}">
                            <a href="{{ route('home') }}">
                                <i class="fas fa-tachometer-alt me-2"></i> {{ __('Tableau de bord') }}
                            </a>
                        </li>

                        @if(auth()->user()->isRecruteur())
                        <li class="sidebar-heading">{{ __('RECRUTEUR') }}</li>
                        <li class="{{ request()->is('offres*') ? 'active' : '' }}">
                            <a href="{{ route('offres.index') }}">
                                <i class="fas fa-briefcase me-2"></i> {{ __('Mes offres') }}
                            </a>
                        </li>
                        <li class="{{ request()->is('candidatures-recues*') ? 'active' : '' }}">
                            <a href="{{ route('candidatures.recues') }}">
                                <i class="fas fa-file-alt me-2"></i> {{ __('Candidatures reçues') }}
                            </a>
                        </li>
                        @endif

                        @if(auth()->user()->isCandidat())
                        <li class="sidebar-heading">{{ __('CANDIDAT') }}</li>
                        <li class="{{ request()->is('offres-disponibles*') ? 'active' : '' }}">
                            <a href="{{ route('offres.disponibles') }}">
                                <i class="fas fa-search me-2"></i> {{ __('Offres disponibles') }}
                            </a>
                        </li>
                        <li class="{{ request()->is('mes-candidatures*') || request()->is('candidatures') ? 'active' : '' }}">
                            <a href="{{ route('candidatures.index') }}">
                                <i class="fas fa-file-alt me-2"></i> {{ __('Mes candidatures') }}
                            </a>
                        </li>
                        @endif

                        @if(auth()->user()->isAdmin())
                        <li class="sidebar-heading">{{ __('ADMINISTRATION') }}</li>
                        <li class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                            <a href="{{ route('admin.users') }}">
                                <i class="fas fa-users me-2"></i> {{ __('Utilisateurs') }}
                            </a>
                        </li>
                        <li class="{{ request()->is('admin/offres*') ? 'active' : '' }}">
                            <a href="{{ route('admin.offres') }}">
                                <i class="fas fa-briefcase me-2"></i> {{ __('Offres') }}
                            </a>
                        </li>
                        @endif

                        <li class="sidebar-heading">{{ __('COMPTE') }}</li>
                        <li class="{{ request()->is('profile*') ? 'active' : '' }}">
                            <a href="{{ route('profile.show') }}">
                                <i class="fas fa-user me-2"></i> {{ __('Mon profil') }}
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> {{ __('Déconnexion') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <div id="content" class="main-content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Mobile) -->
                    <button id="sidebarToggle" class="btn btn-link d-md-none rounded-circle me-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="me-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
                                <div class="user-avatar">
                                    <span>{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                                    {{ __('Profil') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                                    {{ __('Déconnexion') }}
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <!-- Begin Page Content -->
                <div class="content-wrapper">
                    @yield('content')
                </div>
                <!-- End Page Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>{{ __('Laravel') }} &copy; {{ date('Y') }}</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->
            </div>
        </div>
    </div>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>