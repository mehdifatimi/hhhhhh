<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BadgeController extends Controller
{
    /**
     * Affiche les badges de l'utilisateur connecté
     */
    public function showUserBadges()
    {
        $user = Auth::user();
        $badges = $user->badges;
        
        return view('badges.user', compact('badges'));
    }
    
    /**
     * Affiche tous les badges (admin)
     */
    public function adminIndex()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Non autorisé');
        }
        
        $badges = Badge::withCount('users')->get();
        
        return view('admin.badges.index', compact('badges'));
    }
    
    /**
     * Affiche le formulaire pour créer un badge (admin)
     */
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Non autorisé');
        }
        
        return view('admin.badges.create');
    }
    
    /**
     * Enregistre un nouveau badge (admin)
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Non autorisé');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'required_score' => 'nullable|integer|min:0',
            'required_quizzes' => 'nullable|integer|min:0',
            'is_special' => 'boolean',
        ]);
        
        $badge = new Badge();
        $badge->name = $request->name;
        $badge->description = $request->description;
        $badge->required_score = $request->required_score;
        $badge->required_quizzes = $request->required_quizzes;
        $badge->is_special = $request->has('is_special');
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('badges', 'public');
            $badge->image = '/storage/' . $imagePath;
        }
        
        $badge->save();
        
        return redirect()->route('admin.badges.index')
            ->with('success', 'Badge créé avec succès');
    }
    
    /**
     * Affiche le formulaire pour éditer un badge (admin)
     */
    public function edit($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Non autorisé');
        }
        
        $badge = Badge::findOrFail($id);
        
        return view('admin.badges.edit', compact('badge'));
    }
    
    /**
     * Met à jour un badge (admin)
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Non autorisé');
        }
        
        $badge = Badge::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'required_score' => 'nullable|integer|min:0',
            'required_quizzes' => 'nullable|integer|min:0',
            'is_special' => 'boolean',
        ]);
        
        $badge->name = $request->name;
        $badge->description = $request->description;
        $badge->required_score = $request->required_score;
        $badge->required_quizzes = $request->required_quizzes;
        $badge->is_special = $request->has('is_special');
        
        if ($request->hasFile('image')) {
            // Supprimer l'image précédente
            if ($badge->image) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $badge->image));
            }
            
            $imagePath = $request->file('image')->store('badges', 'public');
            $badge->image = '/storage/' . $imagePath;
        }
        
        $badge->save();
        
        return redirect()->route('admin.badges.index')
            ->with('success', 'Badge mis à jour avec succès');
    }
    
    /**
     * Supprime un badge (admin)
     */
    public function destroy($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Non autorisé');
        }
        
        $badge = Badge::findOrFail($id);
        
        // Supprimer l'image
        if ($badge->image) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $badge->image));
        }
        
        $badge->delete();
        
        return redirect()->route('admin.badges.index')
            ->with('success', 'Badge supprimé avec succès');
    }
    
    /**
     * Attribue un badge à un utilisateur (admin)
     */
    public function assignBadge(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Non autorisé');
        }
        
        $request->validate([
            'badge_id' => 'required|exists:badges,id',
            'user_id' => 'required|exists:users,id',
        ]);
        
        $user = User::findOrFail($request->user_id);
        $badge = Badge::findOrFail($request->badge_id);
        
        // Vérifier si l'utilisateur a déjà ce badge
        if ($user->badges()->where('badge_id', $badge->id)->exists()) {
            return back()->with('error', 'L\'utilisateur possède déjà ce badge');
        }
        
        $user->badges()->attach($badge->id, ['earned_at' => now()]);
        
        return back()->with('success', 'Badge attribué avec succès');
    }
}
