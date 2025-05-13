<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\UserQuizController;
use App\Http\Controllers\API\StatsController;
use App\Http\Controllers\API\BadgeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Public Quiz Routes (No authentication required)
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/quizzes/public', [QuizController::class, 'publicQuizzes']);
Route::get('/quizzes/public/{id}', [QuizController::class, 'showPublicQuiz']);

// Guest Quiz Attempt Routes
Route::post('/guest/quiz-attempt', [UserQuizController::class, 'createGuestAttempt']);
Route::post('/guest/quiz-answer', [UserQuizController::class, 'submitGuestAnswer']);
Route::get('/guest/quiz-result/{attemptId}', [UserQuizController::class, 'getGuestResult']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // User Routes
    Route::get('/user', [UserController::class, 'profile']);
    Route::put('/user', [UserController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Category Routes
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
    
    // Quiz Routes
    Route::get('/quizzes', [QuizController::class, 'index']);
    Route::post('/quizzes', [QuizController::class, 'store']);
    Route::get('/quizzes/{id}', [QuizController::class, 'show']);
    Route::put('/quizzes/{id}', [QuizController::class, 'update']);
    Route::delete('/quizzes/{id}', [QuizController::class, 'destroy']);
    
    // Question Routes
    Route::get('/quizzes/{quizId}/questions', [QuestionController::class, 'index']);
    Route::post('/quizzes/{quizId}/questions', [QuestionController::class, 'store']);
    Route::put('/questions/{id}', [QuestionController::class, 'update']);
    Route::delete('/questions/{id}', [QuestionController::class, 'destroy']);
    Route::post('/questions/import', [QuestionController::class, 'import']);
    
    // Quiz Attempt Routes
    Route::post('/quiz-attempts', [UserQuizController::class, 'createAttempt']);
    Route::post('/quiz-answers', [UserQuizController::class, 'submitAnswer']);
    Route::get('/quiz-attempts', [UserQuizController::class, 'getUserAttempts']);
    Route::get('/quiz-attempts/{id}', [UserQuizController::class, 'getAttemptDetails']);
    
    // Statistics Routes
    Route::get('/stats/user', [StatsController::class, 'userStats']);
    Route::get('/stats/quizzes', [StatsController::class, 'quizStats']);
    
    // Badges Routes
    Route::get('/badges', [BadgeController::class, 'index']);
    Route::get('/user/badges', [BadgeController::class, 'userBadges']);
    
    // Admin Only Routes
    Route::middleware('admin')->group(function() {
        Route::get('/users', [UserController::class, 'index']);
        Route::put('/users/{id}/role', [UserController::class, 'updateRole']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
        
        Route::post('/badges', [BadgeController::class, 'store']);
        Route::put('/badges/{id}', [BadgeController::class, 'update']);
        Route::delete('/badges/{id}', [BadgeController::class, 'destroy']);
        Route::post('/badges/assign', [BadgeController::class, 'assignBadge']);
        
        Route::get('/stats/global', [StatsController::class, 'globalStats']);
    });
});
