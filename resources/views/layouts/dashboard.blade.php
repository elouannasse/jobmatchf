<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Job Match') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Dashboard CSS -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
            padding-top: 56px; /* Added for fixed navbar */
        }
        
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }
        
        .sidebar {
            height: 100vh; 
            background-color: #343a40;
            color: #fff;
            width: 250px;
            position: fixed;
            top: 56px; /* Position exactly below navbar */
            left: 0;
            bottom: 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            transition: all 0.3s;
            z-index: 999;
            overflow-y: auto; /* Allow scrolling for long sidebar content */
        }
        
        .sidebar-collapsed {
            margin-left: -250px;
        }
        
        .main-content {
            margin-left: 250px;
            transition: all 0.3s;
            padding: 20px;
            margin-top: 0; /* Remove any top margin */
        }
        
        .main-content-expanded {
            margin-left: 0;
        }
        
        .sidebar-header {
            padding: 20px;
            background-color: #212529;
        }
        
        .sidebar-menu {
            padding: 0;
            list-style: none;
            margin-bottom: 60px; /* Add space at the bottom */
        }
        
        .sidebar-menu li a {
            padding: 15px 20px;
            display: block;
            color: #adb5bd;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }
        
        .sidebar-menu li a:hover {
            background-color: #2c3136;
            color: #fff;
            border-left-color: #007bff;
        }
        
        .sidebar-menu li a.active {
            background-color: #2c3136;
            color: #fff;
            border-left-color: #007bff;
        }
        
        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .toggle-sidebar {
            cursor: pointer;
        }
        
        .card-dashboard {
            border-radius: 10px;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        
        .card-dashboard:hover {
            transform: translateY(-5px);
        }
        
        .stats-card {
            text-align: center;
            padding: 1.5rem;
        }
        
        .stats-card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .stats-card .stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stats-card .stats-text {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        /* Fixed footer */
        footer {
            margin-left: 250px;
            transition: all 0.3s;
        }
        
        .footer-expanded {
            margin-left: 0;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            
            .sidebar.active {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.active {
                margin-left: 250px;
            }
            
            footer {
                margin-left: 0;
            }
            
            footer.active {
                margin-left: 250px;
            }
        }
    </style>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
            <div class="container-fluid">
                <button id="sidebarCollapse" class="btn btn-primary d-md-block d-lg-none me-2">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Job Match') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Connexion') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Inscription') }}</a>
                            </li>
                        @else
                           
                            
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->prenom }} {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user me-2"></i>{{ __('Mon profil') }}
                                    </a>
                                    
                                    <div class="dropdown-divider"></div>
                                    
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>{{ __('Déconnexion') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="wrapper d-flex">
            <!-- Sidebar -->
            @auth
            <nav id="sidebar" class="sidebar">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Tableau de bord</h5>
                        <button id="sidebarCollapseDesktop" class="btn btn-sm btn-dark d-none d-lg-block">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    </div>
                </div>

                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Tableau de bord
                        </a>
                    </li>
                    
                    @if(Auth::user()->isAdmin())
                    <li class="sidebar-header text-uppercase ps-3 pt-4 pb-2">
                        <small>Administration</small>
                    </li>
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Tableau de bord
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.statistics') }}" class="{{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i> Statistiques
                        </a>
                    </li>
                    <li>
                        <a href="#usersSubmenu" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.recruteurs') || request()->routeIs('admin.candidats') || request()->routeIs('admin.users.create') ? 'true' : 'false' }}" class="dropdown-toggle {{ request()->routeIs('admin.recruteurs') || request()->routeIs('admin.candidats') || request()->routeIs('admin.users.create') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> Utilisateurs
                        </a>
                        <ul class="collapse {{ request()->routeIs('admin.recruteurs') || request()->routeIs('admin.candidats') || request()->routeIs('admin.users.create') ? 'show' : '' }} list-unstyled ms-4" id="usersSubmenu">
                            <li>
                                <a href="{{ route('admin.recruteurs') }}" class="{{ request()->routeIs('admin.recruteurs') ? 'active' : '' }} py-2">
                                    <i class="fas fa-user-tie"></i> Recruteurs
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.candidats') }}" class="{{ request()->routeIs('admin.candidats') ? 'active' : '' }} py-2">
                                    <i class="fas fa-user"></i> Candidats
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.create') }}" class="{{ request()->routeIs('admin.users.create') ? 'active' : '' }} py-2">
                                    <i class="fas fa-user-plus"></i> Ajouter un utilisateur
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('admin.offres') }}" class="{{ request()->routeIs('admin.offres') ? 'active' : '' }}">
                            <i class="fas fa-briefcase"></i> Offres d'emploi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.candidatures') }}" class="{{ request()->routeIs('admin.candidatures') ? 'active' : '' }}">
                            <i class="fas fa-file-alt"></i> Candidatures
                        </a>
                    </li>
                   
                    @endif
                    
                    @if(Auth::user()->isRecruteur())
                    <li class="sidebar-header text-uppercase ps-3 pt-4 pb-2">
                        <small>Recruteur</small>
                    </li>
                    <li>
                        <a href="{{ route('offres.index') }}" class="{{ request()->routeIs('offres.index') ? 'active' : '' }}">
                            <i class="fas fa-briefcase"></i> Mes offres
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('candidatures.recues') }}" class="{{ request()->routeIs('candidatures.recues') ? 'active' : '' }}">
                            <i class="fas fa-file-alt"></i> Candidatures reçues
                        </a>
                    </li>
                   
                    @endif
                    
                    @if(Auth::user()->isCandidat())
                    <li class="sidebar-header text-uppercase ps-3 pt-4 pb-2">
                        <small>Candidat</small>
                    </li>
                    <li>
                        <a href="{{ route('offres.disponibles') }}" class="{{ request()->routeIs('offres.disponibles') ? 'active' : '' }}">
                            <i class="fas fa-search"></i> Offres disponibles
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('candidatures.mes-candidatures') }}" class="{{ request()->routeIs('candidatures.mes-candidatures') ? 'active' : '' }}">
                            <i class="fas fa-file-alt"></i> Mes candidatures
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-calendar-alt"></i> Mes entretiens
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-star"></i> Favoris
                        </a>
                    </li>
                    @endif
                    
                    <li class="sidebar-header text-uppercase ps-3 pt-4 pb-2">
                        <small>Compte</small>
                    </li>
                    <li>
                        <a href="{{ route('profile.show') }}">
                            <i class="fas fa-user-circle"></i> Mon profil
                        </a>
                    </li>
                 
                
                    <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </a>
                    </li>
                </ul>
            </nav>
            @endauth

            <!-- Page Content -->
            <div id="content" class="main-content">
                @yield('content')
            </div>
        </div>
        
        <footer id="footer" class="bg-dark text-white py-4 mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h5>{{ config('app.name', 'Job Match') }}</h5>
                        <p>Trouvez l'emploi qui vous correspond ou le candidat idéal pour votre entreprise.</p>
                    </div>
                    <div class="col-md-3">
                        <h5>Liens rapides</h5>
                        <ul class="list-unstyled">
                            <li><a href="{{ url('/') }}" class="text-white">Accueil</a></li>
                            <li><a href="{{ route('login') }}" class="text-white">Connexion</a></li>
                            <li><a href="{{ route('register') }}" class="text-white">Inscription</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h5>Contact</h5>
                        <address>
                            <i class="fas fa-map-marker-alt me-2"></i>123 Avenue de l'Emploi<br>
                            <i class="fas fa-phone me-2"></i>+212 5XX-XXXXXX<br>
                            <i class="fas fa-envelope me-2"></i>contact@jobmatch.ma
                        </address>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <p class="mb-0">&copy; {{ date('Y') }} Job Match. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile sidebar toggle
            document.getElementById('sidebarCollapse').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
                document.getElementById('content').classList.toggle('active');
                document.getElementById('footer').classList.toggle('active');
            });
            
            // Desktop sidebar toggle
            document.getElementById('sidebarCollapseDesktop').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('sidebar-collapsed');
                document.getElementById('content').classList.toggle('main-content-expanded');
                document.getElementById('footer').classList.toggle('footer-expanded');
                
                // Change icon direction when sidebar is collapsed
                const icon = this.querySelector('i');
                if (icon.classList.contains('fa-chevron-left')) {
                    icon.classList.remove('fa-chevron-left');
                    icon.classList.add('fa-chevron-right');
                } else {
                    icon.classList.remove('fa-chevron-right');
                    icon.classList.add('fa-chevron-left');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>