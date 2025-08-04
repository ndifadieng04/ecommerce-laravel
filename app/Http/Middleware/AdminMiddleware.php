<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifions si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('admin.login')->with('error', 'Veuillez vous connecter pour accéder à l\'administration.');
        }

        // Vérifions si l'utilisateur est admin
        $user = auth()->user();
        if (!$user->is_admin) {
            Auth::logout();
            return redirect()->route('admin.login')->with('error', 'Vous n\'avez pas les droits d\'administration.');
        }

        return $next($request);
    }
}
