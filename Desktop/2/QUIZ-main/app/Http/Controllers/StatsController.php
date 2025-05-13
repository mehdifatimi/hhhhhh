<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * Affiche les statistiques de l'utilisateur connecté
     */
    public function showUserStats()
    {
        $user = Auth::user();
        
        // Nombre total de quiz tentés
        $totalAttempts = QuizAttempt::where('user_id', $user->id)->count();
        
        // Nombre de quiz complétés
        $completedAttempts = QuizAttempt::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->count();
        
        // Nombre de quiz réussis
        $passedAttempts = QuizAttempt::where('quiz_attempts.user_id', $user->id)
            ->whereNotNull('quiz_attempts.completed_at')
            ->whereRaw('quiz_attempts.score >= quizzes.passing_score')
            ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->count();
        
        // Score moyen
        $averageScore = QuizAttempt::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->avg('score') ?? 0;
        
        // Meilleur score
        $bestScore = QuizAttempt::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->max('score') ?? 0;
        
        // Catégories les plus jouées
        $topCategories = QuizAttempt::where('quiz_attempts.user_id', $user->id)
            ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->join('categories', 'quizzes.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('count(*) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // Progression par mois (6 derniers mois)
        $monthlyProgress = QuizAttempt::where('quiz_attempts.user_id', $user->id)
            ->whereNotNull('quiz_attempts.completed_at')
            ->where('quiz_attempts.completed_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(quiz_attempts.completed_at, "%Y-%m") as month'),
                DB::raw('AVG(quiz_attempts.score) as average_score'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Badges gagnés
        $badges = $user->badges()->orderBy('pivot_earned_at', 'desc')->get();
        
        return view('stats.user', compact(
            'totalAttempts',
            'completedAttempts',
            'passedAttempts',
            'averageScore',
            'bestScore',
            'topCategories',
            'monthlyProgress',
            'badges'
        ));
    }
    
    /**
     * Affiche les statistiques globales (admin)
     */
    public function showGlobalStats()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Non autorisé');
        }
        
        // Nombre total d'utilisateurs
        $totalUsers = User::count();
        
        // Nombre total de quiz
        $totalQuizzes = Quiz::count();
        
        // Nombre total de tentatives
        $totalAttempts = QuizAttempt::count();
        
        // Taux de réussite global
        $passedAttemptsCount = QuizAttempt::whereNotNull('quiz_attempts.completed_at')
            ->whereRaw('quiz_attempts.score >= quizzes.passing_score')
            ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->count();
        
        $passRate = $totalAttempts > 0 ? ($passedAttemptsCount / $totalAttempts) * 100 : 0;
        
        // Top 5 des quiz les plus joués
        $topQuizzes = QuizAttempt::select('quiz_id', DB::raw('COUNT(*) as attempt_count'))
            ->with('quiz')
            ->groupBy('quiz_id')
            ->orderBy('attempt_count', 'desc')
            ->limit(5)
            ->get();
        
        // Top 5 des catégories les plus populaires
        $topCategories = QuizAttempt::join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->join('categories', 'quizzes.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('count(*) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // Évolution des inscriptions (12 derniers mois)
        $userRegistrations = User::where('created_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Évolution des tentatives de quiz (12 derniers mois)
        $attemptTrends = QuizAttempt::where('created_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        return view('stats.global', compact(
            'totalUsers',
            'totalQuizzes',
            'totalAttempts',
            'passRate',
            'topQuizzes',
            'topCategories',
            'userRegistrations',
            'attemptTrends'
        ));
    }
}
