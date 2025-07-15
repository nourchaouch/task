@extends('layouts.app')

@section('title', 'Accueil - Gestion des tâches')

@section('content')
<div class="relative bg-white">
    <!-- Hero section -->
    <div class="relative bg-indigo-600">
        <div class="absolute inset-0">
            <img class="h-full w-full object-cover" src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80" alt="Task Management">
            <div class="absolute inset-0 bg-indigo-600 mix-blend-multiply opacity-80"></div>
        </div>
        <div class="relative px-4 py-24 sm:px-6 sm:py-32 lg:py-40 lg:px-8">
            <h1 class="text-center text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">
                Gestion des Tâches Simplifiée
            </h1>
            <p class="mx-auto mt-6 max-w-lg text-center text-xl text-indigo-100 sm:max-w-3xl">
                Organisez, suivez et collaborez sur vos projets efficacement
            </p>
            <div class="mx-auto mt-10 max-w-sm sm:flex sm:max-w-none sm:justify-center">
                @guest
                    <div class="space-y-4 sm:mx-auto sm:inline-grid sm:grid-cols-2 sm:gap-5 sm:space-y-0">
                        <a href="{{ route('login') }}" class="flex items-center justify-center rounded-md border border-transparent bg-white px-4 py-3 text-base font-medium text-indigo-700 shadow-sm hover:bg-indigo-50 sm:px-8">
                            Se connecter
                        </a>
                        <a href="{{ route('register') }}" class="flex items-center justify-center rounded-md border border-transparent bg-indigo-500 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-600 sm:px-8">
                            S'inscrire
                        </a>
                    </div>
                @else
                    <div class="space-y-4 sm:mx-auto sm:inline-grid sm:grid-cols-2 sm:gap-5 sm:space-y-0">
                        <a href="{{ auth()->user()->role === 'responsable' ? route('dashboard.manager') : route('dashboard.member') }}" 
                           class="flex items-center justify-center rounded-md border border-transparent bg-white px-4 py-3 text-base font-medium text-indigo-700 shadow-sm hover:bg-indigo-50 sm:px-8">
                            Accéder au tableau de bord
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </div>

    <!-- Features section -->
    <div class="py-24 sm:py-32 bg-white">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center">
                <h2 class="text-base font-semibold leading-7 text-indigo-600">Plus efficace</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    Tout ce dont vous avez besoin pour gérer vos projets
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 py-12">
                <!-- Feature 1 -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-center w-12 h-12 bg-indigo-100 rounded-lg mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Gestion des tâches</h3>
                    <p class="text-gray-600">Organisez et suivez vos tâches efficacement. Définissez des priorités et des échéances.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-center w-12 h-12 bg-indigo-100 rounded-lg mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Travail d'équipe</h3>
                    <p class="text-gray-600">Collaborez facilement avec votre équipe. Partagez des informations et suivez les progrès.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-center w-12 h-12 bg-indigo-100 rounded-lg mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Suivez vos progrès</h3>
                    <p class="text-gray-600">Visualisez l'avancement de vos projets avec des statistiques et des rapports détaillés.</p>
                </div>
            </div>

            <!-- Statistics Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-8">
                <!-- Projects Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100">
                            <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total des projets</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $projectsCount }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tasks Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100">
                            <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Tâches en cours</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $tasksCount }}</p>
                        </div>
                    </div>
                </div>

                <!-- Team Members Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100">
                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Membres d'équipe</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $teamMembersCount }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
