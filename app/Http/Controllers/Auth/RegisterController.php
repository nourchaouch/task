<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ], [
                'email.unique' => 'Cette adresse email est déjà utilisée.',
                'email.required' => 'L\'adresse email est requise.',
                'email.email' => 'Veuillez entrer une adresse email valide.',
                'password.required' => 'Le mot de passe est requis.',
                'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
                'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
                'name.required' => 'Le nom est requis.',
                'name.max' => 'Le nom ne doit pas dépasser 255 caractères.'
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $validator->errors()->first()
                    ], 422);
                }
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if ($request->expectsJson()) {
                $token = JWTAuth::fromUser($user);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Inscription réussie !',
                    'token' => $token,
                    'user' => $user
                ]);
            }

            auth()->login($user);
            return redirect()->route('home.index')
                ->with('success', 'Inscription réussie !');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->validator->errors()->first()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Une erreur est survenue lors de l'inscription. Veuillez réessayer."
                ], 500);
            }
            return redirect()->back()
                ->withErrors(['error' => "Une erreur est survenue lors de l'inscription. Veuillez réessayer."])
                ->withInput();
        }
    }
}
