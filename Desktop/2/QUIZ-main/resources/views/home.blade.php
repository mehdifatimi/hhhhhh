@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="hero-section">
    <div class="hero-content">
        <h1>Développez vos compétences digitales avec DigitalQuiz</h1>
        <p>La plateforme interactive de quiz spécialisée dans le développement web, DevOps, IA et toutes les technologies du numérique.</p>
        <div class="hero-buttons">
            <a href="{{ route('browse') }}" class="btn btn-primary">Explorer les quiz</a>
            @guest
                <a href="{{ route('register') }}" class="btn btn-secondary">S'inscrire gratuitement</a>
            @else
                <a href="{{ route('quizzes.create') }}" class="btn btn-secondary">Créer un quiz</a>
            @endguest
        </div>
    </div>
</div>

<div class="container section-padding">
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
    <h2 class="section-title">Quiz populaires</h2>
    
    @if(isset($popularQuizzes) && count($popularQuizzes) > 0)
        <div class="quizzes-grid fade-in">
            @foreach($popularQuizzes as $quiz)
                <div class="quiz-card">
                    <div class="quiz-card-header">
                        <span class="difficulty-badge {{ $quiz->difficulty }}">
                            @if($quiz->difficulty === 'easy')
                                Facile
                            @elseif($quiz->difficulty === 'medium')
                                Moyen
                            @else
                                Difficile
                            @endif
                        </span>
                    </div>
                    <div class="quiz-card-body">
                        <h3>{{ $quiz->title }}</h3>
                        <p class="quiz-description">{{ $quiz->description }}</p>
                        <div class="quiz-meta">
                            <span><i class="fas fa-question-circle"></i> {{ $quiz->questions_count ?? '?' }} questions</span>
                            @if($quiz->time_limit)
                                <span><i class="fas fa-clock"></i> {{ floor($quiz->time_limit / 60) }} min</span>
                            @endif
                        </div>
                    </div>
                    <div class="quiz-card-footer">
                        @auth
                            <a href="{{ route('quizzes.play', $quiz->id) }}" class="btn-play">Jouer</a>
                        @else
                            <a href="{{ route('quizzes.play.guest', $quiz->id) }}" class="btn-play">Jouer en invité</a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center fade-in">
            <p style="font-size: 1.1rem; color: var(--text-secondary); margin-bottom: 2rem;">Aucun quiz disponible pour le moment.</p>
        </div>
    @endif
    
    <div class="text-center mt-4 mb-5">
        <a href="{{ route('browse') }}" class="btn btn-primary">Voir plus de quiz</a>
    </div>

    <h2 class="section-title">Catégories spécialisées</h2>
    <div class="categories-grid fade-in">
        @if(isset($categories) && count($categories) > 0)
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category->id) }}" class="category-card" id="{{ Str::slug($category->name) }}">
                    <div class="category-icon">
                        @if($category->icon)
                            <img src="{{ $category->icon }}" alt="{{ $category->name }}">
                        @else
                            @php
                                $icons = [
                                    'Développement Web Frontend' => 'fa-brands fa-html5',
                                    'Développement Web Backend' => 'fa-solid fa-server',
                                    'DevOps & Cloud' => 'fa-solid fa-cloud',
                                    'Frameworks PHP' => 'fa-brands fa-php',
                                    'JavaScript & Frameworks' => 'fa-brands fa-js',
                                    'Mobile & Applications' => 'fa-solid fa-mobile-screen',
                                    'Base de données' => 'fa-solid fa-database',
                                    'UI/UX Design' => 'fa-solid fa-palette',
                                    'Intelligence Artificielle' => 'fa-solid fa-brain',
                                    'Sécurité Informatique' => 'fa-solid fa-shield-halved',
                                    'Blockchain & Web3' => 'fa-solid fa-link',
                                    'Gestion de Projet IT' => 'fa-solid fa-tasks'
                                ];
                                $defaultIcon = 'fa-solid fa-code';
                            @endphp
                            <i class="{{ $icons[$category->name] ?? $defaultIcon }}"></i>
                        @endif
                    </div>
                    <h3>{{ $category->name }}</h3>
                    <p>{{ $category->quizzes_count ?? 0 }} quiz</p>
                </a>
            @endforeach
        @else
            <div class="text-center">
                <p>Aucune catégorie disponible pour le moment.</p>
            </div>
        @endif
    </div>

    <h2 class="section-title">Pourquoi choisir DigitalQuiz ?</h2>
    <div class="features-grid fade-in" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 4rem;">
        <div class="feature" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--box-shadow); text-align: center; transition: all 0.3s;">
            <div class="feature-icon" style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 1.5rem;">
                <i class="fas fa-code"></i>
            </div>
            <h3 style="margin-bottom: 1rem; font-weight: 600;">Quiz spécialisés</h3>
            <p style="color: var(--text-secondary);">Contenus créés par des experts pour couvrir les technologies et frameworks les plus demandés en développement web.</p>
        </div>
        <div class="feature" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--box-shadow); text-align: center; transition: all 0.3s;">
            <div class="feature-icon" style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 1.5rem;">
                <i class="fas fa-brain"></i>
            </div>
            <h3 style="margin-bottom: 1rem; font-weight: 600;">Apprentissage interactif</h3>
            <p style="color: var(--text-secondary);">Des questions techniques, des challenges de code et des QCM pour tester vos compétences de façon ludique.</p>
        </div>
        <div class="feature" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--box-shadow); text-align: center; transition: all 0.3s;">
            <div class="feature-icon" style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 1.5rem;">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3 style="margin-bottom: 1rem; font-weight: 600;">Suivi de progression</h3>
            <p style="color: var(--text-secondary);">Analysez vos performances, identifiez vos forces et faiblesses dans chaque domaine technique.</p>
        </div>
        <div class="feature" style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--box-shadow); text-align: center; transition: all 0.3s;">
            <div class="feature-icon" style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 1.5rem;">
                <i class="fas fa-certificate"></i>
            </div>
            <h3 style="margin-bottom: 1rem; font-weight: 600;">Badges & Certifications</h3>
            <p style="color: var(--text-secondary);">Validez votre expertise et partagez vos réussites sur vos profils professionnels.</p>
        </div>
    </div>
    
    <div class="cta section-padding" style="background: linear-gradient(135deg, var(--primary-dark), var(--primary-color)); color: white; text-align: center; padding: 4rem 2rem; border-radius: 15px; margin: 2rem 0;">
        <h2 style="font-size: 2.2rem; margin-bottom: 1.5rem;">Prêt à booster vos compétences techniques ?</h2>
        <p style="font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.9;">Rejoignez notre communauté de développeurs et testez vos connaissances sur plus de 100 technologies du web et du digital.</p>
        <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
            <a href="{{ route('browse') }}" class="btn" style="background: white; color: var(--primary-color); padding: 0.8rem 1.8rem; border-radius: 50px; font-weight: 500; text-decoration: none; transition: all 0.3s; display: inline-block; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">Explorer les quiz</a>
            @guest
            <a href="{{ route('register') }}" class="btn" style="background: transparent; color: white; padding: 0.8rem 1.8rem; border-radius: 50px; font-weight: 500; text-decoration: none; transition: all 0.3s; display: inline-block; border: 2px solid white;">S'inscrire gratuitement</a>
            @endguest
        </div>
    </div>
    
    <h2 class="section-title">Témoignages de développeurs</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 3rem;" class="fade-in">
        <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: var(--box-shadow); position: relative;">
            <div style="font-size: 1.1rem; color: var(--text-secondary); margin-bottom: 1.5rem;">
                <i class="fas fa-quote-left" style="color: var(--primary-light); font-size: 1.5rem; margin-right: 10px;"></i>
                DigitalQuiz m'a permis de préparer efficacement mes entretiens techniques en frontend. Les quiz sur React et JavaScript m'ont donné confiance pour décrocher mon emploi actuel.
            </div>
            <div style="display: flex; align-items: center;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: #e0e0e0; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                    <i class="fas fa-user" style="color: #757575;"></i>
                </div>
                <div>
                    <h4 style="margin: 0; color: var(--text-primary);">Thomas L.</h4>
                    <p style="margin: 0; color: var(--text-secondary); font-size: 0.9rem;">Développeur Frontend</p>
                </div>
            </div>
        </div>
        <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: var(--box-shadow); position: relative;">
            <div style="font-size: 1.1rem; color: var(--text-secondary); margin-bottom: 1.5rem;">
                <i class="fas fa-quote-left" style="color: var(--primary-light); font-size: 1.5rem; margin-right: 10px;"></i>
                En tant que lead développeur, j'utilise cette plateforme pour former mon équipe sur les bonnes pratiques. Les quiz DevOps et Laravel sont particulièrement utiles pour notre stack technique.
            </div>
            <div style="display: flex; align-items: center;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: #e0e0e0; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                    <i class="fas fa-user" style="color: #757575;"></i>
                </div>
                <div>
                    <h4 style="margin: 0; color: var(--text-primary);">Sarah M.</h4>
                    <p style="margin: 0; color: var(--text-secondary); font-size: 0.9rem;">Lead Développeuse</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
