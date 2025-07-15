<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        $user = Auth::user();
        
        if ($user->role !== $role) {
            // Log the unauthorized access attempt
            \Illuminate\Support\Facades\Log::warning('Unauthorized access attempt', [
                'user_id' => $user->id,
                'required_role' => $role,
                'user_role' => $user->role,
                'path' => $request->path()
            ]);

            // Redirect to the appropriate dashboard based on user's actual role
            if ($user->role === 'responsable') {
                return redirect()->route('dashboard.manager')
                    ->with('error', 'Accès non autorisé. Redirection vers votre tableau de bord.');
            } else if ($user->role === 'membre') {
                return redirect()->route('dashboard.member')
                    ->with('error', 'Accès non autorisé. Redirection vers votre tableau de bord.');
            } else {
                return redirect()->route('home.index')
                    ->with('error', 'Accès non autorisé.');
            }
        }

        return $next($request);
    }
}
