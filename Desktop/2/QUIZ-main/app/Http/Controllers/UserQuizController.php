<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\QuizAttempt;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserQuizController extends Controller
{
    /**
     * Démarre une tentative de quiz pour un utilisateur connecté
     */
    public function startQuiz($id)
    {
        $quiz = Quiz::with('questions.answers')->findOrFail($id);
        
        // Vérifier si l'utilisateur a accès au quiz
        if (!$quiz->is_public && $quiz->created_by != Auth::id()) {
            abort(403, 'Vous n\'avez pas accès à ce quiz.');
        }
        
        // Créer une nouvelle tentative
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => Auth::id(),
            'started_at' => now(),
            'total_questions' => $quiz->questions->count(),
        ]);
        
        // Si le quiz a des questions aléatoires, les mélanger
        $questions = $quiz->questions;
        if ($quiz->randomize_questions) {
            $questions = $questions->shuffle();
        }
        
        return view('quizzes.play', [
            'quiz' => $quiz,
            'questions' => $questions,
            'attempt' => $attempt,
            'currentQuestionIndex' => 0,
        ]);
    }
    
    /**
     * Soumet une réponse à une question
     */
    public function submitAnswer(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);
        $attempt = QuizAttempt::where('id', $request->attempt_id)
            ->where('user_id', Auth::id())
            ->whereNull('completed_at')
            ->firstOrFail();
        
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answers' => 'required|array',
            'answers.*' => 'exists:answers,id',
        ]);
        
        // Enregistrer la réponse de l'utilisateur
        foreach ($request->answers as $answerId) {
            $answer = Answer::findOrFail($answerId);
            UserAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $request->question_id,
                'answer_id' => $answerId,
                'is_correct' => $answer->is_correct,
            ]);
        }
        
        // Si c'est la dernière question, marquer la tentative comme terminée
        $nextQuestionIndex = $request->current_index + 1;
        $totalQuestions = $quiz->questions->count();
        
        if ($nextQuestionIndex >= $totalQuestions || $request->has('finish_quiz')) {
            $attempt->completed_at = now();
            $attempt->save();
            
            return redirect()->route('quizzes.result', $attempt->id);
        }
        
        // Passer à la question suivante
        return view('quizzes.play', [
            'quiz' => $quiz,
            'questions' => $quiz->questions,
            'attempt' => $attempt,
            'currentQuestionIndex' => $nextQuestionIndex,
        ]);
    }
    
    /**
     * Affiche le résultat d'une tentative de quiz
     */
    public function showResult($attemptId)
    {
        $attempt = QuizAttempt::with([
            'quiz',
            'answers.question',
            'answers.answer'
        ])->where('user_id', Auth::id())
          ->findOrFail($attemptId);
        
        if (!$attempt->completed_at) {
            return redirect()->route('quizzes.play', $attempt->quiz->id);
        }
        
        // Calculer le score
        $totalQuestions = $attempt->quiz->questions->count();
        $correctAnswers = 0;
        
        foreach ($attempt->quiz->questions as $question) {
            $userAnswerIds = $attempt->answers
                ->where('question_id', $question->id)
                ->pluck('answer_id')
                ->toArray();
            
            $correctAnswerIds = $question->answers
                ->where('is_correct', true)
                ->pluck('id')
                ->toArray();
            
            // Vérifier si les réponses de l'utilisateur correspondent aux réponses correctes
            $isCorrect = !array_diff($userAnswerIds, $correctAnswerIds) && 
                        !array_diff($correctAnswerIds, $userAnswerIds);
            
            if ($isCorrect) {
                $correctAnswers++;
            }
        }
        
        $scorePercentage = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
        $isPassed = $scorePercentage >= $attempt->quiz->passing_score;
        
        return view('quizzes.result', [
            'attempt' => $attempt,
            'correctAnswers' => $correctAnswers,
            'totalQuestions' => $totalQuestions,
            'scorePercentage' => $scorePercentage,
            'isPassed' => $isPassed,
        ]);
    }
    
    /**
     * Affiche la liste des tentatives de quiz de l'utilisateur
     */
    public function showUserAttempts()
    {
        $attempts = QuizAttempt::with('quiz')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('quizzes.user-attempts', compact('attempts'));
    }
    
    /**
     * Démarre une tentative de quiz pour un invité
     */
    public function startGuestQuiz($id)
    {
        $quiz = Quiz::with('questions.answers')
            ->where('is_public', true)
            ->findOrFail($id);
        
        // Créer une tentative anonyme
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'started_at' => now(),
            'completed' => false,
            'guest_session_id' => session()->getId(),
            'total_questions' => $quiz->questions->count(),
        ]);
        
        // Si le quiz a des questions aléatoires, les mélanger
        $questions = $quiz->questions;
        if ($quiz->randomize_questions) {
            $questions = $questions->shuffle();
        }
        
        return view('quizzes.play-guest', [
            'quiz' => $quiz,
            'questions' => $questions,
            'attempt' => $attempt,
            'currentQuestionIndex' => 0,
        ]);
    }
    
    /**
     * Soumet une réponse à une question pour un invité
     */
    public function submitGuestAnswer(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);
        $attempt = QuizAttempt::where('id', $request->attempt_id)
            ->where('guest_session_id', session()->getId())
            ->whereNull('completed_at')
            ->firstOrFail();
        
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answers' => 'required|array',
            'answers.*' => 'exists:answers,id',
        ]);
        
        // Enregistrer la réponse de l'invité
        foreach ($request->answers as $answerId) {
            UserAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $request->question_id,
                'answer_id' => $answerId,
            ]);
        }
        
        // Si c'est la dernière question, marquer la tentative comme terminée
        $nextQuestionIndex = $request->current_index + 1;
        $totalQuestions = $quiz->questions->count();
        
        if ($nextQuestionIndex >= $totalQuestions || $request->has('finish_quiz')) {
            $attempt->completed = true;
            $attempt->completed_at = now();
            $attempt->save();
            
            return redirect()->route('quizzes.result.guest', $attempt->id);
        }
        
        // Passer à la question suivante
        return view('quizzes.play-guest', [
            'quiz' => $quiz,
            'questions' => $quiz->questions,
            'attempt' => $attempt,
            'currentQuestionIndex' => $nextQuestionIndex,
        ]);
    }
    
    /**
     * Affiche le résultat d'une tentative de quiz pour un invité
     */
    public function showGuestResult($attemptId)
    {
        $attempt = QuizAttempt::with([
            'quiz', 
            'userAnswers.question',
            'userAnswers.answer'
        ])->where('guest_session_id', session()->getId())
          ->findOrFail($attemptId);
        
        if (!$attempt->completed) {
            return redirect()->route('quizzes.play.guest', $attempt->quiz->id);
        }
        
        // Calculer le score
        $totalQuestions = $attempt->quiz->questions->count();
        $correctAnswers = 0;
        
        foreach ($attempt->quiz->questions as $question) {
            $userAnswerIds = $attempt->userAnswers
                ->where('question_id', $question->id)
                ->pluck('answer_id')
                ->toArray();
            
            $correctAnswerIds = $question->answers
                ->where('is_correct', true)
                ->pluck('id')
                ->toArray();
            
            // Vérifier si les réponses de l'utilisateur correspondent aux réponses correctes
            $isCorrect = !array_diff($userAnswerIds, $correctAnswerIds) && 
                        !array_diff($correctAnswerIds, $userAnswerIds);
            
            if ($isCorrect) {
                $correctAnswers++;
            }
        }
        
        $scorePercentage = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
        $isPassed = $scorePercentage >= $attempt->quiz->passing_score;
        
        return view('quizzes.result-guest', [
            'attempt' => $attempt,
            'correctAnswers' => $correctAnswers,
            'totalQuestions' => $totalQuestions,
            'scorePercentage' => $scorePercentage,
            'isPassed' => $isPassed,
        ]);
    }
}
