<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            // Si l'utilisateur connecté n'est pas admin, on le déconnecte pour permettre la connexion admin
            if (!Auth::user()->is_admin) {
                Auth::logout();
                request()->session()->invalidate();
                request()->session()->regenerateToken();
                
                return redirect()->route('admin.login')
                    ->with('info', 'Session client fermée. Vous pouvez maintenant vous connecter en tant qu\'administrateur.');
            }
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Vérifier si l'utilisateur est admin
            if (!$user->is_admin) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Vous n\'avez pas les droits d\'administration.',
                ]);
            }

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Vous avez été déconnecté avec succès.');
    }
} 