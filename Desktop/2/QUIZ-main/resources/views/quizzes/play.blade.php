@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
<div class="quiz-play-container">
    <div class="container py-5">
        <div class="quiz-header">
            <h1>{{ $quiz->title }}</h1>
            
            <div class="quiz-meta">
                <span class="difficulty-badge {{ $quiz->difficulty }}">
                    @if($quiz->difficulty === 'easy')
                        Facile
                    @elseif($quiz->difficulty === 'medium')
                        Moyen
                    @else
                        Difficile
                    @endif
                </span>
                
                @if($quiz->time_limit)
                    <span class="time-limit" id="time-remaining">
                        {{ floor($quiz->time_limit / 60) }}:00
                    </span>
                @endif
            </div>
        </div>
        
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress" style="width: {{ ($currentQuestionIndex / $questions->count()) * 100 }}%"></div>
            </div>
            <div class="question-counter">
                Question {{ $currentQuestionIndex + 1 }} / {{ $questions->count() }}
            </div>
        </div>
        
        @if($questions->count() > 0 && $currentQuestionIndex < $questions->count())
            @php
                $currentQuestion = $questions[$currentQuestionIndex];
                $isMultipleChoice = $currentQuestion->answers->where('is_correct', true)->count() > 1;
            @endphp
            
            <div class="question-card">
                <h3 class="question-text">{{ $currentQuestion->text }}</h3>
                
                @if($currentQuestion->image_url)
                    <div class="question-image">
                        <img src="{{ $currentQuestion->image_url }}" alt="Question Image">
                    </div>
                @endif
                
                <form action="{{ route('quizzes.submit', $quiz->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="attempt_id" value="{{ $attempt->id }}">
                    <input type="hidden" name="question_id" value="{{ $currentQuestion->id }}">
                    <input type="hidden" name="current_index" value="{{ $currentQuestionIndex }}">
                    
                    <div class="answers-container">
                        @foreach($currentQuestion->answers->shuffle() as $answer)
                            <div class="answer-option">
                                <input type="{{ $isMultipleChoice ? 'checkbox' : 'radio' }}" 
                                       name="answers[]" 
                                       id="answer-{{ $answer->id }}" 
                                       value="{{ $answer->id }}" 
                                       required>
                                <label for="answer-{{ $answer->id }}">{{ $answer->text }}</label>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="quiz-navigation">
                        @if($currentQuestionIndex === $questions->count() - 1)
                            <button type="submit" class="btn btn-success">Terminer le quiz</button>
                        @else
                            <button type="submit" class="btn btn-primary">Question suivante</button>
                        @endif
                        
                        <button type="submit" name="finish_quiz" value="1" class="btn btn-outline-secondary">
                            Terminer maintenant
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="alert alert-warning">
                Ce quiz ne contient pas de questions.
            </div>
        @endif
    </div>
</div>

@if($quiz->time_limit)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let timeLimit = {{ $quiz->time_limit }};
        let timerElement = document.getElementById('time-remaining');
        
        let timer = setInterval(function() {
            timeLimit--;
            
            let minutes = Math.floor(timeLimit / 60);
            let seconds = timeLimit % 60;
            
            timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            
            if (timeLimit <= 0) {
                clearInterval(timer);
                document.querySelector('form').submit();
            }
        }, 1000);
    });
</script>
@endif
@endsection
