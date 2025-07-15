@extends('layouts.app')

@section('title', 'Tableau de bord - Membre')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Welcome Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Bienvenue, {{ Auth::user()->name }}</h1>
            <p class="text-gray-600 mt-1">Voici un aperçu de vos tâches et activités</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Tâches en attente</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ $taskStats['pending'] ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Tâches en cours</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $taskStats['in_progress'] ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Tâches terminées</h3>
                <p class="text-3xl font-bold text-green-600">{{ $taskStats['completed'] ?? 0 }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Tasks Section -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Mes tâches</h2>
                        <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 border border-transparent rounded-md font-medium text-indigo-700 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Voir tout
                        </a>
                    </div>
                    <div class="space-y-4">
                        @if(isset($tasks) && $tasks->count() > 0)
                            @foreach($tasks as $task)
                            <div class="flex items-center p-4 bg-white border rounded-lg shadow-sm hover:border-indigo-500 transition-colors duration-150">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-800">{{ $task->title }}</h3>
                                    @if(isset($task->project))
                                    <p class="text-sm text-gray-600">Projet: {{ $task->project->name }}</p>
                                    @endif
                                    <p class="text-sm text-gray-600">Échéance: {{ optional($task->due_date)->format('d/m/Y') ?? 'Non définie' }}</p>
                                </div>
                                <div>
                                    <span class="px-3 py-1 text-sm rounded-full whitespace-nowrap
                                        @if($task->status === 'completed') bg-green-100 text-green-800
                                        @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($task->status) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune tâche</h3>
                                <p class="mt-1 text-sm text-gray-500">Vous n'avez pas encore de tâches assignées.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Projects Section -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Projets actifs</h2>
                        <a href="{{ route('projects.index') }}" class="text-indigo-600 hover:text-indigo-800">Voir tout</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($projects as $project)
                        <div class="border-b pb-4 last:border-b-0">
                            <h3 class="font-medium text-gray-800">{{ $project->name }}</h3>
                            <div class="mt-2">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $project->completion_percentage }}%"></div>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $project->completion_percentage }}% complété</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500">Aucun projet à afficher</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Events Section -->
            <div class="bg-white rounded-lg shadow lg:col-span-2">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Événements à venir</h2>
                        <a href="{{ route('events.index') }}" class="text-indigo-600 hover:text-indigo-800">Voir tout</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @forelse($upcomingEvents as $event)
                        <div class="border rounded-lg p-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <span class="text-indigo-600 font-semibold">{{ $event->start_date->format('d') }}</span>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-800">{{ $event->title }}</h3>
                                    <p class="text-sm text-gray-600">{{ $event->start_date->format('H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 col-span-3">Aucun événement à venir</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
