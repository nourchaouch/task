@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Tableau de bord Membre</h1>
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-xl font-semibold text-gray-700 mb-2">Tâches Assignées</div>
                <div class="text-3xl font-bold text-indigo-600">{{ $assignedTasks->count() }}</div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-xl font-semibold text-gray-700 mb-2">Tâches Terminées</div>
                <div class="text-3xl font-bold text-green-600">{{ $completedTasks }}</div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-xl font-semibold text-gray-700 mb-2">Tâches en Attente</div>
                <div class="text-3xl font-bold text-yellow-600">{{ $pendingTasks }}</div>
            </div>
        </div>

        <!-- Tasks List -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Mes Tâches</h2>
                
                @if($assignedTasks->count() > 0)
                    <div class="space-y-4">
                        @foreach($assignedTasks as $task)
                        <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $task->title }}</h3>
                                    <p class="text-gray-600 mb-2">{{ Str::limit($task->description, 150) }}</p>
                                    <div class="text-sm text-gray-500">
                                        Projet: {{ $task->project->name }}
                                    </div>
                                </div>
                                <div>
                                    @if($task->status === 'completed')
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                            Terminée
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                            En cours
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-4 flex justify-between items-center">
                                <div class="text-sm text-gray-500">
                                    Date limite: {{ $task->due_date ? $task->due_date->format('d/m/Y') : 'Non définie' }}
                                </div>
                                <a href="{{ route('tasks.show', $task) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300 disabled:opacity-25 transition">
                                    Voir détails
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600">Aucune tâche assignée.</p>
                @endif
            </div>
        </div>

        <!-- Projects List -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Mes Projets</h2>
                
                @if($projects->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($projects as $project)
                        <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $project->name }}</h3>
                                <p class="text-gray-600 mb-4">{{ Str::limit($project->description, 100) }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">
                                        {{ $project->tasks->where('assigned_to', Auth::id())->count() }} tâches assignées
                                    </span>
                                    <a href="{{ route('projects.show', $project) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300 disabled:opacity-25 transition">
                                        Voir détails
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600">Aucun projet trouvé.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
