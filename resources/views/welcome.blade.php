<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JobMatch - Trouvez l'emploi qui vous correspond</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .hero-section {
            background: linear-gradient(to right, #4e54c8, #8f94fb);
            color: white;
            padding: 100px 0;
        }
        .features-section {
            padding: 80px 0;
        }
        .feature-card {
            border: none;
            border-radius: 10px;
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #4e54c8;
        }
        .cta-section {
            background-color: #f1f5fe;
            padding: 80px 0;
        }
        .btn-primary {
            background-color: #4e54c8;
            border-color: #4e54c8;
            padding: 10px 25px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #3a40af;
            border-color: #3a40af;
        }
        .btn-outline-primary {
            color: #4e54c8;
            border-color: #4e54c8;
            padding: 10px 25px;
            font-weight: 600;
        }
        .btn-outline-primary:hover {
            background-color: #4e54c8;
            border-color: #4e54c8;
        }
        .stats-item {
            text-align: center;
            padding: 20px;
        }
        .stats-number {
            font-size: 3rem;
            font-weight: 700;
            color: #4e54c8;
            margin-bottom: 10px;
        }
        .stats-label {
            font-size: 1.1rem;
            color: #6c757d;
        }
        .testimonial-card {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            height: 100%;
        }
        .testimonial-text {
            font-style: italic;
            margin-bottom: 20px;
        }
        .testimonial-author {
            font-weight: 600;
        }
        .footer {
            background-color: #2d3748;
            color: white;
            padding: 50px 0 20px;
        }
        .footer a {
            color: #cbd5e0;
            text-decoration: none;
        }
        .footer a:hover {
            color: white;
        }
        .footer-heading {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-briefcase me-2"></i>JobMatch
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/home') }}">Tableau de bord</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary ms-2" href="{{ route('register') }}">Inscription</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Trouvez l'emploi qui vous correspond</h1>
                    <p class="lead mb-4">JobMatch vous aide à trouver le poste idéal en fonction de vos compétences et aspirations. Que vous soyez candidat ou recruteur, notre plateforme facilite la mise en relation.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('register') }}?type=candidat" class="btn btn-light">Je cherche un emploi</a>
                        <a href="{{ route('register') }}?type=recruteur" class="btn btn-outline-light">Je recrute</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80" alt="JobMatch - Trouvez l'emploi qui vous correspond" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <section class="features-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Comment ça marche ?</h2>
                <p class="lead text-muted">Une approche simple et efficace pour trouver le bon match.</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-card card p-4 text-center h-100">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h4>Créez votre profil</h4>
                            <p class="text-muted">Inscrivez-vous et complétez votre profil avec vos compétences, expériences et préférences.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card card p-4 text-center h-100">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h4>Explorez les opportunités</h4>
                            <p class="text-muted">Découvrez des offres d'emploi correspondant à votre profil grâce à notre algorithme de matching.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card card p-4 text-center h-100">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <h4>Connectez-vous</h4>
                            <p class="text-muted">Postulez directement via la plateforme et suivez l'avancement de vos candidatures.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="stats-section py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="stats-item">
                        <div class="stats-number">5000+</div>
                        <div class="stats-label">Offres d'emploi</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-item">
                        <div class="stats-number">2000+</div>
                        <div class="stats-label">Entreprises inscrites</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-item">
                        <div class="stats-number">85%</div>
                        <div class="stats-label">Taux de satisfaction</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="testimonials-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Ils nous font confiance</h2>
                <p class="lead text-muted">Découvrez les témoignages de nos utilisateurs.</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            "Grâce à JobMatch, j'ai trouvé un poste qui correspond parfaitement à mes compétences et à mes aspirations professionnelles."
                        </div>
                        <div class="testimonial-author">Sarah D. - Développeuse Web</div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            "En tant que recruteur, JobMatch m'a permis d'identifier rapidement des candidats qualifiés pour nos postes vacants."
                        </div>
                        <div class="testimonial-author">Marc L. - Responsable RH</div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            "L'interface intuitive et l'algorithme de matching m'ont vraiment aidé à trouver rapidement des opportunités pertinentes."
                        </div>
                        <div class="testimonial-author">Karim B. - Ingénieur</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">Prêt à trouver votre match professionnel idéal ?</h2>
            <p class="lead mb-4">Rejoignez notre communauté et découvrez des opportunités qui correspondent à vos attentes.</p>
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">S'inscrire gratuitement</a>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="footer-heading">JobMatch</h5>
                    <p>La plateforme qui connecte les talents avec les opportunités professionnelles.</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="footer-heading">Liens</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/') }}">Accueil</a></li>
                        <li><a href="#">À propos</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 class="footer-heading">Candidats</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('register') }}?type=candidat">Inscription candidats</a></li>
                        <li><a href="#">Rechercher un emploi</a></li>
                        <li><a href="#">Conseils de carrière</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 class="footer-heading">Recruteurs</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('register') }}?type=recruteur">Inscription recruteurs</a></li>
                        <li><a href="#">Publier une offre</a></li>
                        <li><a href="#">Solutions entreprises</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; {{ date('Y') }} JobMatch. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="me-3">Mentions légales</a>
                    <a href="#">Politique de confidentialité</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>