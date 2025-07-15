@extends('layouts.app')

@section('title', 'Profil du membre - ' . $member->name)

@section('content')
<div class="bg-gray-100 min-h-screen py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Member Profile Header -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-20 w-20">
                    <div class="h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center">
                        <span class="text-3xl font-medium text-indigo-600">
                            {{ strtoupper(substr($member->name, 0, 2)) }}
                        </span>
                    </div>
                </div>
                <div class="ml-6">
                    <h1 class="text-2xl font-semibold text-gray-800">
                        {{ $member->name }}
                    </h1>
                    <p class="text-gray-600">{{ $member->email }}</p>
                </div>
            </div>
        </div>

        <!-- Task Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Tâches en attente</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ $taskStats['pending'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Tâches en cours</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $taskStats['in_progress'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Tâches terminées</h3>
                <p class="text-3xl font-bold text-green-600">{{ $taskStats['completed'] }}</p>
            </div>
        </div>

        <!-- Recent Tasks -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Tâches récentes</h2>
                </div>
                <div class="space-y-4">
                    @forelse($recentTasks as $task)
                        <div class="flex items-center justify-between border-b pb-4 last:border-b-0">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">{{ $task->title }}</h3>
                                <p class="text-sm text-gray-500">
                                    Projet: {{ $task->project->name }}
                                </p>
                            </div>
                            <span class="px-3 py-1 text-xs rounded-full
                                @if($task->status === 'completed') bg-green-100 text-green-800
                                @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($task->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucune tâche récente</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
