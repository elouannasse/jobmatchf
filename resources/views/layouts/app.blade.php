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

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Job Match') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">{{ __('Accueil') }}</a>
                            </li>
                            
                            @if(Auth::user()->isRecruteur())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('offres.index') }}">{{ __('Mes offres') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('candidatures.recues') }}">{{ __('Candidatures reçues') }}</a>
                                </li>
                            @endif
                            
                            @if(Auth::user()->isCandidat())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('offres.disponibles') }}">{{ __('Offres disponibles') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('candidatures.mes-candidatures') }}">{{ __('Mes candidatures') }}</a>
                                </li>
                            @endif
                            
                            @if(Auth::user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                                </li>
                            @endif
                        @endauth
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

        <main>
            @yield('content')
        </main>
        
        <footer class="bg-dark text-white py-4 mt-5">
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
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    @auth
    <script>
    // Fonction pour mettre à jour le compteur de notifications
    function updateNotificationCounter() {
        $.ajax({
            url: '{{ route("notifications.count") }}',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                const count = response.count;
                const badgeElement = $('#notification-badge');
                
                if (count > 0) {
                    badgeElement.text(count);
                    badgeElement.show();
                } else {
                    badgeElement.hide();
                }
            },
            error: function(error) {
                console.error('Erreur lors de la récupération des notifications:', error);
            }
        });
    }

    // Mise à jour du contenu du dropdown des notifications
    function updateNotificationDropdown() {
        $.ajax({
            url: '{{ route("notifications.dropdown") }}',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#notifications-dropdown').html(response);
            }
        });
    }

    $(document).ready(function() {
        // Mise à jour initiale
        // updateNotificationCounter();
        
        // Actualisation périodique (toutes les 30 secondes)
        setInterval(function() {
            updateNotificationCounter();
            updateNotificationDropdown();
        }, 30000);
    });
    </script>
    @endauth
    
    @stack('scripts')
</body>
</html>