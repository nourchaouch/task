@extends('layouts.app')

@section('title', 'Tableau de bord - Responsable')

@section('content')
<div class="bg-gray-100 min-h-screen py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
            <h1 class="text-2xl font-semibold text-gray-800 mb-4">
                Bienvenue, {{ Auth::user()->name }}
            </h1>
            <p class="text-gray-600">
                Responsable de projet
            </p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="text-sm font-medium text-gray-500 truncate mb-1">Total des projets</div>
                <div class="text-2xl font-semibold text-indigo-600">{{ $projectsCount }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="text-sm font-medium text-gray-500 truncate mb-1">Total des tâches</div>
                <div class="text-2xl font-semibold text-gray-600">{{ $tasksCount }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="text-sm font-medium text-gray-500 truncate mb-1">Tâches en attente</div>
                <div class="text-2xl font-semibold text-yellow-600">{{ $tasksStats['pending'] }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="text-sm font-medium text-gray-500 truncate mb-1">Tâches en cours</div>
                <div class="text-2xl font-semibold text-blue-600">{{ $tasksStats['in_progress'] }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="text-sm font-medium text-gray-500 truncate mb-1">Tâches terminées</div>
                <div class="text-2xl font-semibold text-green-600">{{ $tasksStats['completed'] }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Tasks -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-medium text-gray-900">Tâches récentes</h2>
                    <a href="{{ route('tasks.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Voir tout</a>
                </div>
                <div class="space-y-4">
                    @forelse($recentTasks as $task)
                    <div class="flex items-center justify-between border-b pb-4 last:border-b-0">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">{{ $task->title }}</h3>
                            <p class="text-sm text-gray-500">
                                Assigné à: {{ optional($task->assignedTo)->name ?? 'Non assigné' }}
                            </p>
                        </div>
                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full
                            @if($task->status === 'completed') bg-green-100 text-green-800
                            @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($task->status) }}
                        </span>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">Aucune tâche récente</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Projects -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-medium text-gray-900">Projets récents</h2>
                    <a href="#" class="text-sm text-indigo-600 hover:text-indigo-900">Voir tout</a>
                </div>
                <div class="space-y-4">
                    @forelse($recentProjects as $project)
                    <div class="border-b pb-4 last:border-b-0">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-900">{{ $project->name }}</h3>
                            <span class="text-sm text-gray-500">
                                {{ $project->tasks_count ?? $project->tasks->count() }} tâches
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            @php
                                $completedTasks = $project->tasks->where('status', 'completed')->count();
                                $totalTasks = $project->tasks->count();
                                $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks * 100) : 0;
                            @endphp
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">{{ number_format($progress, 0) }}% complété</p>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">Aucun projet récent</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
