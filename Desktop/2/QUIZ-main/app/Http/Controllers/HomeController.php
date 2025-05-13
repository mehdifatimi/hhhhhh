<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil avec les quiz populaires et les catégories
     */
    public function index()
    {
        try {
            // Récupérer les quiz populaires (public)
            $popularQuizzes = Quiz::where('is_public', true)
                ->withCount('questions')
                ->orderBy('attempts_count', 'desc')
                ->limit(6)
                ->get();
            
            // Récupérer toutes les catégories
            $categories = Category::withCount('quizzes')
                ->get();
            
            return view('home', [
                'popularQuizzes' => $popularQuizzes,
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            return view('home', [
                'popularQuizzes' => [],
                'categories' => [],
                'error' => 'Impossible de charger les données. Veuillez réessayer plus tard.'
            ]);
        }
    }
}
