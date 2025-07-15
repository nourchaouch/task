@extends('layouts.app')

@section('title', 'Inscription - Gestion des tâches')

@section('content')
<body class="bg-gradient-to-br from-indigo-50 to-blue-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
            <h2 class="text-3xl font-bold mb-6 text-center text-gray-800">Inscription</h2>
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">Nom complet</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                           class="w-full px-4 py-2 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-200"
                           placeholder="Votre nom complet">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Adresse email</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                           class="w-full px-4 py-2 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-200"
                           placeholder="votre@email.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-gray-700 text-sm font-semibold mb-2">Rôle</label>
                    <select name="role" id="role" required 
                            class="w-full px-4 py-2 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-200">
                        <option value="">Sélectionnez votre rôle</option>
                        <option value="project_manager" {{ old('role') == 'project_manager' ? 'selected' : '' }}>
                            Responsable de projet
                        </option>
                        <option value="team_member" {{ old('role') == 'team_member' ? 'selected' : '' }}>
                            Membre d'équipe
                        </option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Mot de passe</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-2 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-200"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-semibold mb-2">Confirmez le mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full px-4 py-2 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-200"
                           placeholder="••••••••">
                </div>

                <button type="submit" 
                        class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-[1.02]">
                    S'inscrire
                </button>
            </form>

            <div class="mt-8 text-center">
                <span class="text-gray-600">Vous avez déjà un compte ?</span>
                <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-medium ml-1 hover:underline">Connectez-vous ici</a>
            </div>
        </div>
    </div>
@endsection
