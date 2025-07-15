<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Activity;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Log the authentication success
            Log::info('User logged in successfully', ['user_id' => Auth::id()]);

            // Redirect based on user role
            if (Auth::user()->role === 'responsable') {
                return redirect()->route('dashboard.manager');
            } else if (Auth::user()->role === 'membre') {
                return redirect()->route('dashboard.member');
            } else {
                // Default to member dashboard if role is not recognized
                return redirect()->route('dashboard.member');
            }
        }
 
        // Log failed login attempt
        Log::warning('Failed login attempt', ['email' => $request->email]);
 
        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:project_manager,team_member'
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'email.max' => 'L\'adresse email ne peut pas dépasser 255 caractères.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'role.required' => 'Veuillez sélectionner un rôle.',
            'role.in' => 'Le rôle sélectionné n\'est pas valide.'
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role
            ]);

            // Log the user in
            Auth::login($user);

            // Try to create activity log for new user registration
            try {
                Activity::create([
                    'user_id' => $user->id,
                    'description' => 'S\'est inscrit en tant que ' . 
                                   ($request->role === 'project_manager' ? 'responsable de projet' : 'membre d\'équipe'),
                    'subject_type' => User::class,
                    'subject_id' => $user->id
                ]);
            } catch (\Exception $e) {
                // Just log the activity creation failure, but don't stop the registration
                Log::warning('Failed to create activity log: ' . $e->getMessage());
            }
            
            // Redirect based on role
            if ($user->role === 'project_manager') {
                return redirect()->route('responsable.dashboard')
                    ->with('success', 'Votre compte a été créé avec succès !');
            } else {
                return redirect()->route('member.dashboard')
                    ->with('success', 'Votre compte a été créé avec succès !');
            }

        } catch (\Exception $e) {
            // Log the detailed error
            \Log::error('User registration failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
