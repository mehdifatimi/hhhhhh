<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * Get statistics for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userStats(Request $request)
    {
        $userId = $request->user()->id;
        
        // Get total quiz attempts
        $totalAttempts = QuizAttempt::where('user_id', $userId)->count();
        
        // Get completed quiz attempts
        $completedAttempts = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->count();
            
        // Get success rate
        $passedAttempts = QuizAttempt::where('user_id', $userId)
            ->where('passed', true)
            ->count();
            
        $successRate = $completedAttempts > 0 ? round(($passedAttempts / $completedAttempts) * 100) : 0;
        
        // Get average score
        $averageScore = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('score')
            ->avg('score') ?? 0;
            
        // Get total time spent on quizzes
        $totalTimeSpent = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('time_spent')
            ->sum('time_spent');
            
        // Format time spent
        $hours = floor($totalTimeSpent / 3600);
        $minutes = floor(($totalTimeSpent % 3600) / 60);
        $seconds = $totalTimeSpent % 60;
        $formattedTimeSpent = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        
        // Get recent quizzes
        $recentQuizzes = QuizAttempt::where('user_id', $userId)
            ->with('quiz:id,title')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($attempt) {
                return [
                    'quiz_id' => $attempt->quiz->id,
                    'quiz_title' => $attempt->quiz->title,
                    'score' => $attempt->score,
                    'completed' => !is_null($attempt->completed_at),
                    'passed' => $attempt->passed,
                    'date' => $attempt->created_at->format('Y-m-d H:i'),
                ];
            });
            
        // Get best categories based on average score
        $bestCategories = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('score')
            ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->join('categories', 'quizzes.category_id', '=', 'categories.id')
            ->select('categories.id', 'categories.name', DB::raw('AVG(quiz_attempts.score) as average_score'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('average_score', 'desc')
            ->take(3)
            ->get();
            
        return response()->json([
            'total_attempts' => $totalAttempts,
            'completed_attempts' => $completedAttempts,
            'passed_attempts' => $passedAttempts,
            'success_rate' => $successRate,
            'average_score' => round($averageScore, 1),
            'total_time_spent' => $totalTimeSpent,
            'formatted_time_spent' => $formattedTimeSpent,
            'recent_quizzes' => $recentQuizzes,
            'best_categories' => $bestCategories,
        ]);
    }

    /**
     * Get statistics for quizzes created by the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function quizStats(Request $request)
    {
        $userId = $request->user()->id;
        
        // Get quizzes created by user
        $quizzes = Quiz::where('created_by', $userId)->get();
        $quizIds = $quizzes->pluck('id')->toArray();
        
        if (empty($quizIds)) {
            return response()->json([
                'message' => 'No quizzes found',
                'total_quizzes' => 0,
            ]);
        }
        
        // Get total attempts for all user's quizzes
        $totalAttempts = QuizAttempt::whereIn('quiz_id', $quizIds)->count();
        
        // Get completed attempts
        $completedAttempts = QuizAttempt::whereIn('quiz_id', $quizIds)
            ->whereNotNull('completed_at')
            ->count();
            
        // Get success rate
        $passedAttempts = QuizAttempt::whereIn('quiz_id', $quizIds)
            ->where('passed', true)
            ->count();
            
        $successRate = $completedAttempts > 0 ? round(($passedAttempts / $completedAttempts) * 100) : 0;
        
        // Get average score across all quizzes
        $averageScore = QuizAttempt::whereIn('quiz_id', $quizIds)
            ->whereNotNull('score')
            ->avg('score') ?? 0;
            
        // Get most popular quizzes
        $popularQuizzes = QuizAttempt::whereIn('quiz_id', $quizIds)
            ->select('quiz_id', DB::raw('count(*) as attempt_count'))
            ->groupBy('quiz_id')
            ->orderBy('attempt_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $quiz = Quiz::find($item->quiz_id);
                return [
                    'quiz_id' => $quiz->id,
                    'title' => $quiz->title,
                    'attempt_count' => $item->attempt_count,
                ];
            });
            
        // Get quizzes with highest success rate
        $highSuccessQuizzes = QuizAttempt::whereIn('quiz_id', $quizIds)
            ->whereNotNull('completed_at')
            ->select('quiz_id', 
                DB::raw('count(*) as total_count'), 
                DB::raw('sum(case when passed = 1 then 1 else 0 end) as passed_count')
            )
            ->groupBy('quiz_id')
            ->having('total_count', '>=', 5) // Only include quizzes with at least 5 attempts
            ->get()
            ->map(function ($item) {
                $successRate = round(($item->passed_count / $item->total_count) * 100);
                $quiz = Quiz::find($item->quiz_id);
                return [
                    'quiz_id' => $quiz->id,
                    'title' => $quiz->title,
                    'success_rate' => $successRate,
                    'attempt_count' => $item->total_count,
                ];
            })
            ->sortByDesc('success_rate')
            ->take(5)
            ->values();
            
        // Get most challenging quizzes (lowest success rate)
        $challengingQuizzes = QuizAttempt::whereIn('quiz_id', $quizIds)
            ->whereNotNull('completed_at')
            ->select('quiz_id', 
                DB::raw('count(*) as total_count'), 
                DB::raw('sum(case when passed = 1 then 1 else 0 end) as passed_count')
            )
            ->groupBy('quiz_id')
            ->having('total_count', '>=', 5) // Only include quizzes with at least 5 attempts
            ->get()
            ->map(function ($item) {
                $successRate = round(($item->passed_count / $item->total_count) * 100);
                $quiz = Quiz::find($item->quiz_id);
                return [
                    'quiz_id' => $quiz->id,
                    'title' => $quiz->title,
                    'success_rate' => $successRate,
                    'attempt_count' => $item->total_count,
                ];
            })
            ->sortBy('success_rate')
            ->take(5)
            ->values();
            
        return response()->json([
            'total_quizzes' => count($quizIds),
            'total_attempts' => $totalAttempts,
            'completed_attempts' => $completedAttempts,
            'passed_attempts' => $passedAttempts,
            'success_rate' => $successRate,
            'average_score' => round($averageScore, 1),
            'popular_quizzes' => $popularQuizzes,
            'high_success_quizzes' => $highSuccessQuizzes,
            'challenging_quizzes' => $challengingQuizzes,
        ]);
    }

    /**
     * Get global statistics for the platform (admin only).
     *
     * @return \Illuminate\Http\Response
     */
    public function globalStats()
    {
        // Total users
        $totalUsers = User::count();
        
        // Total quizzes
        $totalQuizzes = Quiz::count();
        
        // Total quiz attempts
        $totalAttempts = QuizAttempt::count();
        
        // Total questions answered
        $totalQuestionsAnswered = UserAnswer::count();
        
        // Average success rate
        $completedAttempts = QuizAttempt::whereNotNull('completed_at')->count();
        $passedAttempts = QuizAttempt::where('passed', true)->count();
        $successRate = $completedAttempts > 0 ? round(($passedAttempts / $completedAttempts) * 100) : 0;
        
        // Average score
        $averageScore = QuizAttempt::whereNotNull('score')->avg('score') ?? 0;
        
        // Most active users
        $mostActiveUsers = QuizAttempt::select('user_id', DB::raw('count(*) as attempt_count'))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderBy('attempt_count', 'desc')
            ->take(10)
            ->get()
            ->map(function ($item) {
                $user = User::find($item->user_id);
                return [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'attempt_count' => $item->attempt_count,
                ];
            });
            
        // Most popular quizzes
        $popularQuizzes = QuizAttempt::select('quiz_id', DB::raw('count(*) as attempt_count'))
            ->groupBy('quiz_id')
            ->orderBy('attempt_count', 'desc')
            ->take(10)
            ->get()
            ->map(function ($item) {
                $quiz = Quiz::find($item->quiz_id);
                return [
                    'quiz_id' => $quiz->id,
                    'title' => $quiz->title,
                    'creator' => User::find($quiz->created_by)->name,
                    'attempt_count' => $item->attempt_count,
                ];
            });
            
        // Monthly growth in users and quizzes
        $monthlyUserGrowth = User::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get()
            ->map(function ($item) {
                return [
                    'date' => sprintf('%04d-%02d', $item->year, $item->month),
                    'count' => $item->count,
                ];
            })
            ->reverse()
            ->values();
            
        $monthlyQuizGrowth = Quiz::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get()
            ->map(function ($item) {
                return [
                    'date' => sprintf('%04d-%02d', $item->year, $item->month),
                    'count' => $item->count,
                ];
            })
            ->reverse()
            ->values();
            
        return response()->json([
            'total_users' => $totalUsers,
            'total_quizzes' => $totalQuizzes,
            'total_attempts' => $totalAttempts,
            'total_questions_answered' => $totalQuestionsAnswered,
            'success_rate' => $successRate,
            'average_score' => round($averageScore, 1),
            'most_active_users' => $mostActiveUsers,
            'popular_quizzes' => $popularQuizzes,
            'monthly_user_growth' => $monthlyUserGrowth,
            'monthly_quiz_growth' => $monthlyQuizGrowth,
        ]);
    }
}
