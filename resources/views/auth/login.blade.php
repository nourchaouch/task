@extends('layouts.app')

@section('title', 'Connexion - Gestion des tâches')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Connexion</h2>
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
            @csrf
            
            <div>
                <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">
                    Adresse email
                </label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}"
                       class="w-full px-4 py-2 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-200"
                       placeholder="votre@email.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">
                    Mot de passe
                </label>
                <input type="password" name="password" id="password" required
                       class="w-full px-4 py-2 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-200"
                       placeholder="••••••••">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" 
                    class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-[1.02]">
                Se connecter
            </button>
        </form>

        <div class="mt-6 text-center">
            <span class="text-gray-600">Pas encore de compte ?</span>
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-medium ml-1 hover:underline">
                Inscrivez-vous ici
            </a>
        </div>
    </div>
</div>
@endsection
