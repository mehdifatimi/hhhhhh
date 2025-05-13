@extends('layouts.app')

@section('title', 'Résultats du Quiz')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/result.css') }}">
@endsection

@section('content')
<div class="result-container">
    <div class="container py-5">
        <div class="result-header">
            <h1>Résultats : {{ $attempt->quiz->title }}</h1>
            <a href="{{ route('quizzes.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Retour aux quiz
            </a>
        </div>
        
        <div class="result-card">
            <div class="score-circle {{ $isPassed ? 'passed' : 'failed' }}">
                <div class="score-value">{{ round($scorePercentage) }}%</div>
                <div class="score-text">{{ $isPassed ? 'Réussi!' : 'Échoué' }}</div>
            </div>
            
            <div class="result-details">
                <p>Vous avez obtenu <strong>{{ $correctAnswers }} réponses correctes</strong> sur {{ $totalQuestions }} questions.</p>
                <p>Score minimum pour réussir: <strong>{{ $attempt->quiz->passing_score }}%</strong></p>
                <p>Temps utilisé: <strong>{{ $attempt->started_at->diffInMinutes($attempt->completed_at) }} minutes</strong></p>
                
                @if($isPassed)
                    <div class="success-message">
                        <i class="fas fa-trophy"></i>
                        <p>Félicitations! Vous avez réussi ce quiz.</p>
                    </div>
                @else
                    <div class="failure-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Vous n'avez pas atteint le score minimum pour réussir ce quiz. N'hésitez pas à réessayer!</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="answers-review">
            <h2>Revue des Questions</h2>
            
            @foreach($attempt->quiz->questions as $index => $question)
                @php
                    $userAnswerIds = $attempt->answers->where('question_id', $question->id)->pluck('answer_id')->toArray();
                    $correctAnswerIds = $question->answers->where('is_correct', true)->pluck('id')->toArray();
                    $isCorrect = !array_diff($userAnswerIds, $correctAnswerIds) && !array_diff($correctAnswerIds, $userAnswerIds);
                @endphp
                
                <div class="question-review {{ $isCorrect ? 'correct' : 'incorrect' }}">
                    <div class="question-header">
                        <span class="question-number">Question {{ $index + 1 }}</span>
                        <span class="question-status">
                            @if($isCorrect)
                                <i class="fas fa-check-circle"></i> Correct
                            @else
                                <i class="fas fa-times-circle"></i> Incorrect
                            @endif
                        </span>
                    </div>
                    
                    <div class="question-content">
                        <h4>{{ $question->text }}</h4>
                        
                        @if($question->image_url)
                            <div class="question-image">
                                <img src="{{ $question->image_url }}" alt="Question Image">
                            </div>
                        @endif
                        
                        <div class="answers-list">
                            @foreach($question->answers as $answer)
                                <div class="answer-item 
                                    {{ in_array($answer->id, $correctAnswerIds) ? 'correct-answer' : '' }} 
                                    {{ in_array($answer->id, $userAnswerIds) ? 'user-selected' : '' }}">
                                    <i class="fas {{ in_array($answer->id, $userAnswerIds) ? 'fa-check-square' : 'fa-square' }}"></i>
                                    <span>{{ $answer->text }}</span>
                                    
                                    @if(in_array($answer->id, $correctAnswerIds))
                                        <i class="fas fa-check correct-icon"></i>
                                    @endif
                                    
                                    @if(in_array($answer->id, $userAnswerIds) && !in_array($answer->id, $correctAnswerIds))
                                        <i class="fas fa-times incorrect-icon"></i>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        @if($question->explanation)
                            <div class="answer-explanation">
                                <h5>Explication:</h5>
                                <p>{{ $question->explanation }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="result-actions">
            <a href="{{ route('quizzes.play', $attempt->quiz->id) }}" class="btn btn-primary">
                <i class="fas fa-redo"></i> Réessayer ce quiz
            </a>
            <a href="{{ route('browse') }}" class="btn btn-secondary">
                <i class="fas fa-search"></i> Explorer d'autres quiz
            </a>
        </div>
    </div>
</div>
@endsection
