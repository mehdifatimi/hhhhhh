<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$request->user()) {
            return redirect('login');
        }

        if ($role === 'admin' && !$request->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Accès non autorisé. Vous devez être administrateur.');
        }

        if ($role === 'user' && !$request->user()->isUser()) {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        return $next($request);
    }
}
