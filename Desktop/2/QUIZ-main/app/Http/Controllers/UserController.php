<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Affiche le profil de l'utilisateur connecté
     */
    public function showProfile()
    {
        $user = Auth::user();
        $quizCount = Quiz::where('user_id', $user->id)->count();
        $attemptsCount = QuizAttempt::where('user_id', $user->id)->count();
        $completedQuizCount = QuizAttempt::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->count();
        
        $badges = $user->badges;
        
        return view('profile.index', compact('user', 'quizCount', 'attemptsCount', 'completedQuizCount', 'badges'));
    }
    
    /**
     * Met à jour le profil de l'utilisateur
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.'])
                    ->withInput();
            }
        }
        
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        if ($request->hasFile('avatar')) {
            // Supprimer l'avatar précédent s'il existe
            if ($user->avatar && !str_contains($user->avatar, 'default-avatar.png')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = '/storage/' . $avatarPath;
        }
        
        $user->save();
        
        return redirect()->route('profile')
            ->with('success', 'Profil mis à jour avec succès.');
    }
    
    /**
     * Affiche la liste des utilisateurs (admin seulement)
     */
    public function listUsers()
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Non autorisé');
        }
        
        $users = User::withCount(['quizzes', 'attempts'])
            ->paginate(20);
            
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Met à jour le rôle d'un utilisateur (admin seulement)
     */
    public function updateUserRole(Request $request, $id)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Non autorisé');
        }
        
        $user = User::findOrFail($id);
        
        $request->validate([
            'role' => 'required|in:user,admin',
        ]);
        
        $user->is_admin = ($request->role === 'admin');
        $user->save();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Rôle de l\'utilisateur mis à jour avec succès.');
    }
    
    /**
     * Supprime un utilisateur (admin seulement)
     */
    public function deleteUser($id)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Non autorisé');
        }
        
        $user = User::findOrFail($id);
        
        // Empêcher la suppression de son propre compte
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        
        // Supprimer l'avatar si nécessaire
        if ($user->avatar && !str_contains($user->avatar, 'default-avatar.png')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
    
    /**
     * Affiche le tableau de bord administrateur
     */
    public function adminDashboard()
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Non autorisé');
        }
        
        $totalUsers = User::count();
        $totalQuizzes = Quiz::count();
        $totalAttempts = QuizAttempt::count();
        $publicQuizzes = Quiz::where('is_public', true)->count();
        
        $recentUsers = User::latest()->take(5)->get();
        $recentQuizzes = Quiz::with('user', 'category')
            ->latest()
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalQuizzes',
            'totalAttempts',
            'publicQuizzes',
            'recentUsers',
            'recentQuizzes'
        ));
    }
}
