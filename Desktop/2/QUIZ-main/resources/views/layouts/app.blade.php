<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DigitalQuiz - @yield('title', 'Accueil')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/professional.css') }}" rel="stylesheet">
    <link href="{{ asset('css/header-auth.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="{{ route('home') }}" class="navbar-brand">Digital<span>Quiz</span></a>
                <div class="nav-links">
                    <a href="{{ route('browse') }}">Explorer</a>
                    <a href="{{ route('about') }}">À propos</a>
                    @auth
                        <a href="{{ route('quizzes.index') }}">
                            <i class="fas fa-tasks"></i> Mes Quiz
                        </a>
                        <a href="{{ route('quizzes.create') }}" class="btn-nav btn-primary-nav">
                            <i class="fas fa-plus"></i> Créer
                        </a>
                        <div class="user-info-inline">
                            <div class="user-avatar">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">
                                @else
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                @endif
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <span class="user-email">{{ Auth::user()->email }}</span>
                            <a href="{{ route('profile') }}">
                                <i class="fas fa-user"></i> Mon profil
                            </a>
                            <a href="{{ route('stats') }}">
                                <i class="fas fa-chart-line"></i> Statistiques
                            </a>
                            <a href="{{ route('quizzes.index') }}">
                                <i class="fas fa-folder"></i> Mes quiz
                            </a>
                            @if(Auth::user()->role_id === 1)
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-shield-alt"></i> Administration
                            </a>
                            @endif
                            <a href="{{ route('logout') }}" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Déconnexion
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}">Se connecter</a>
                        <a href="{{ route('register') }}" class="btn-nav btn-primary-nav">S'inscrire</a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <h3>Digital<span>Quiz</span></h3>
                    <p>La plateforme d'apprentissage interactive pour les professionnels du numérique. Testez et améliorez vos compétences dans tous les domaines du développement digital.</p>
                </div>
                <div class="footer-links">
                    <h4>Liens utiles</h4>
                    <ul>
                        <li><a href="{{ route('home') }}">Accueil</a></li>
                        <li><a href="{{ route('browse') }}">Explorer</a></li>
                        <li><a href="{{ route('about') }}">À propos</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>Catégories</h4>
                    <ul>
                        <li><a href="{{ route('browse') }}#frontend">Développement Frontend</a></li>
                        <li><a href="{{ route('browse') }}#backend">Développement Backend</a></li>
                        <li><a href="{{ route('browse') }}#devops">DevOps & Cloud</a></li>
                        <li><a href="{{ route('browse') }}#mobile">Développement Mobile</a></li>
                    </ul>
                </div>
                <div class="footer-social">
                    <h4>Rejoignez-nous</h4>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-github"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-discord"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} DigitalQuiz. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
