@extends('layouts.app')

@section('title', 'Parcourir les Quiz')

@section('content')
<div class="hero-section">
    <div class="hero-content">
        <h1>Explorez nos Quiz de Développement Digital</h1>
        <p>Testez vos compétences, relevez des défis techniques et perfectionnez votre expertise dans tous les domaines du développement web et digital.</p>
        <div class="hero-buttons">
            <a href="#categories" class="btn btn-primary">Découvrir les catégories</a>
            @auth
                <a href="{{ route('quizzes.create') }}" class="btn btn-secondary">Créer un quiz</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-secondary">Se connecter pour créer</a>
            @endauth
        </div>
    </div>
</div>

<div class="container py-5">
    <h2 class="section-title" id="categories">Catégories Spécialisées</h2>

    <div class="categories-grid fade-in">
        @forelse($categories as $category)
            <a href="{{ route('categories.show', $category->id) }}" class="category-card" id="{{ Str::slug($category->name) }}">
                <div class="category-icon">
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
                            'Gestion de Projet IT' => 'fa-solid fa-tasks',
                            'Sciences' => 'fa-solid fa-flask',
                            'Histoire' => 'fa-solid fa-landmark',
                            'Géographie' => 'fa-solid fa-globe',
                            'Littérature' => 'fa-solid fa-book',
                            'Cinéma' => 'fa-solid fa-film',
                            'Musique' => 'fa-solid fa-music',
                            'Sport' => 'fa-solid fa-futbol',
                            'Informatique' => 'fa-solid fa-laptop-code',
                            'Culture Générale' => 'fa-solid fa-book',
                            'Technologie' => 'fa-solid fa-laptop'
                        ];
                        $defaultIcon = 'fa-solid fa-code';
                    @endphp
                    <i class="{{ $icons[$category->name] ?? $defaultIcon }}"></i>
                </div>
                <h3>{{ $category->name }}</h3>
                <p>{{ $category->quizzes_count ?? 0 }} quiz</p>
            </a>
        @empty
            <div class="text-center">
                <p>Aucune catégorie disponible pour le moment.</p>
                @auth
                    @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mt-3">Ajouter une catégorie</a>
                    @endif
                @endauth
            </div>
        @endforelse
    </div>

    <h2 class="section-title">Quiz populaires</h2>
    <div class="quizzes-grid fade-in">
                @forelse($popularQuizzes as $quiz)
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
                                <span>{{ $quiz->questions_count }} questions</span>
                                @if($quiz->time_limit)
                                    <span>{{ floor($quiz->time_limit / 60) }} min</span>
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
                @empty
                    <p class="text-center">Aucun quiz populaire disponible pour le moment.</p>
                @endforelse
            </div>
        </div>
    </div>

    <h2 class="section-title">Quiz récents</h2>
    <div class="quizzes-grid fade-in">
                @forelse($recentQuizzes as $quiz)
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
                                <span>{{ $quiz->questions_count }} questions</span>
                                @if($quiz->time_limit)
                                    <span>{{ floor($quiz->time_limit / 60) }} min</span>
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
                @empty
                    <p class="text-center">Aucun quiz récent disponible pour le moment.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
