<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Affiche la liste des catégories
     */
    public function index()
    {
        $categories = Category::withCount('quizzes')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Affiche les quiz d'une catégorie spécifique
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        $quizzes = Quiz::where('category_id', $id)
            ->where('is_public', true)
            ->withCount('questions')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('categories.show', compact('category', 'quizzes'));
    }

    /**
     * Affiche le formulaire pour créer une catégorie
     * (réservé aux administrateurs)
     */
    public function create()
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Non autorisé');
        }

        return view('categories.create');
    }

    /**
     * Enregistre une nouvelle catégorie
     * (réservé aux administrateurs)
     */
    public function store(Request $request)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Non autorisé');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $category = new Category();
        $category->name = $request->name;

        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('category-icons', 'public');
            $category->icon = '/storage/' . $iconPath;
        }

        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès');
    }

    /**
     * Affiche le formulaire pour éditer une catégorie
     * (réservé aux administrateurs)
     */
    public function edit($id)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Non autorisé');
        }

        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * Met à jour une catégorie
     * (réservé aux administrateurs)
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Non autorisé');
        }

        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $category->name = $request->name;

        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('category-icons', 'public');
            $category->icon = '/storage/' . $iconPath;
        }

        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès');
    }

    /**
     * Supprime une catégorie
     * (réservé aux administrateurs)
     */
    public function destroy($id)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Non autorisé');
        }

        $category = Category::findOrFail($id);
        
        // Vérifiez si la catégorie contient des quiz
        $quizCount = Quiz::where('category_id', $id)->count();
        
        if ($quizCount > 0) {
            return back()->with('error', 'Impossible de supprimer cette catégorie car elle contient des quiz.');
        }
        
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès');
    }
}
