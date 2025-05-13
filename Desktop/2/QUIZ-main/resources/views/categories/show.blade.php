@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="hero-section">
    <div class="hero-content">
        <h1>{{ $category->name }}</h1>
        <p>Découvrez tous les quiz de cette catégorie et testez vos connaissances</p>
    </div>
</div>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title">{{ $category->name }} ({{ $quizzes->total() }} quiz)</h2>
        <a href="{{ route('browse') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Retour aux catégories
        </a>
    </div>

    <div class="quizzes-grid fade-in">
        @forelse($quizzes as $quiz)
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
            <div class="text-center w-100">
                <p>Aucun quiz disponible dans cette catégorie pour le moment.</p>
                @auth
                    <a href="{{ route('quizzes.create') }}" class="btn btn-primary mt-3">Créer un quiz</a>
                @endauth
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $quizzes->links() }}
    </div>
</div>
@endsection
