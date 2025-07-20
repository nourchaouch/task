<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MembreMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'team_member') {
            return $next($request);
        }
        return redirect()->route('login')->with('error', 'Accès réservé aux membres.');
    }
}
