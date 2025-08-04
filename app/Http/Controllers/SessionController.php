<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    /**
     * Basculer vers la session client
     */
    public function switchToClient(Request $request)
    {
        // Si on est connecté en tant qu'admin, on se déconnecte
        if (Auth::check() && Auth::user()->is_admin) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('login')
            ->with('info', 'Session administrateur fermée. Vous pouvez maintenant vous connecter en tant que client.');
    }

    /**
     * Basculer vers la session admin
     */
    public function switchToAdmin(Request $request)
    {
        // Si on est connecté en tant que client, on se déconnecte
        if (Auth::check() && !Auth::user()->is_admin) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('admin.login')
            ->with('info', 'Session client fermée. Vous pouvez maintenant vous connecter en tant qu\'administrateur.');
    }

    /**
     * Afficher la page de sélection de session
     */
    public function showSessionSelector()
    {
        return view('session-selector');
    }
} 