<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserQuizController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\BadgeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Routes d'authentification
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Routes publiques
Route::get('/browse', [QuizController::class, 'browse'])->name('browse');
Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/about', function() { return view('about'); })->name('about');
Route::get('/contact', function() { return view('contact'); })->name('contact');

// Routes protégées (utilisateur connecté)
Route::middleware('auth')->group(function () {
    // Routes du profil
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    
    // Routes des quiz (accès utilisateur)
    Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/{id}', [QuizController::class, 'show'])->name('quizzes.show');
    
    // Routes pour jouer aux quiz (accès utilisateur)
    Route::get('/play/{id}', [UserQuizController::class, 'startQuiz'])->name('quizzes.play');
    Route::post('/play/{id}/submit', [UserQuizController::class, 'submitAnswer'])->name('quizzes.submit');
    Route::get('/results/{attemptId}', [UserQuizController::class, 'showResult'])->name('quizzes.result');
    Route::get('/my-attempts', [UserQuizController::class, 'showUserAttempts'])->name('user.attempts');
    
    // Routes des statistiques (accès utilisateur)
    Route::get('/stats', [StatsController::class, 'showUserStats'])->name('stats');
    
    // Routes des badges (accès utilisateur)
    Route::get('/badges', [BadgeController::class, 'showUserBadges'])->name('badges');
    
    // Routes admin (création et gestion des quiz)
    Route::middleware('role:admin')->group(function() {
        // Routes de création et gestion des quiz
        Route::get('/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
        Route::post('/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
        Route::get('/quizzes/{id}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
        Route::put('/quizzes/{id}', [QuizController::class, 'update'])->name('quizzes.update');
        Route::delete('/quizzes/{id}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
        
        // Routes des questions
        Route::get('/quizzes/{quizId}/questions', [QuizController::class, 'showQuestions'])->name('quizzes.questions.index');
        Route::get('/quizzes/{quizId}/questions/create', [QuizController::class, 'createQuestion'])->name('quizzes.questions.create');
        Route::post('/quizzes/{quizId}/questions', [QuizController::class, 'storeQuestion'])->name('quizzes.questions.store');
        Route::get('/questions/{id}/edit', [QuizController::class, 'editQuestion'])->name('questions.edit');
        Route::put('/questions/{id}', [QuizController::class, 'updateQuestion'])->name('questions.update');
        Route::delete('/questions/{id}', [QuizController::class, 'destroyQuestion'])->name('questions.destroy');
        Route::post('/questions/import', [QuizController::class, 'importQuestions'])->name('questions.import');
    });
    
    // Routes admin (gestion des utilisateurs et badges)
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function() {
        Route::get('/dashboard', [UserController::class, 'adminDashboard'])->name('dashboard');
        Route::get('/users', [UserController::class, 'listUsers'])->name('users.index');
        Route::put('/users/{id}/role', [UserController::class, 'updateUserRole'])->name('users.update.role');
        Route::delete('/users/{id}', [UserController::class, 'deleteUser'])->name('users.destroy');
        
        Route::get('/badges', [BadgeController::class, 'adminIndex'])->name('badges.index');
        Route::get('/badges/create', [BadgeController::class, 'create'])->name('badges.create');
        Route::post('/badges', [BadgeController::class, 'store'])->name('badges.store');
        Route::get('/badges/{id}/edit', [BadgeController::class, 'edit'])->name('badges.edit');
        Route::put('/badges/{id}', [BadgeController::class, 'update'])->name('badges.update');
        Route::delete('/badges/{id}', [BadgeController::class, 'destroy'])->name('badges.destroy');
        Route::post('/badges/assign', [BadgeController::class, 'assignBadge'])->name('badges.assign');
        
        Route::get('/stats', [StatsController::class, 'showGlobalStats'])->name('stats');
    });
});

// Route pour jouer aux quiz en tant qu'invité
Route::get('/play-guest/{id}', [UserQuizController::class, 'startGuestQuiz'])->name('quizzes.play.guest');
Route::post('/play-guest/{id}/submit', [UserQuizController::class, 'submitGuestAnswer'])->name('quizzes.submit.guest');
Route::get('/results-guest/{attemptId}', [UserQuizController::class, 'showGuestResult'])->name('quizzes.result.guest');
Route::post('/profile', [UserController::class, 'updateProfile']);
