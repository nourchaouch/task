@extends('layouts.app')

@section('title', 'Liste des membres')

@section('content')
<div class="bg-gray-100 min-h-screen py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-800">
                    Liste des membres
                </h1>
                @if(auth()->user()->role === 'project_manager')
                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Ajouter un membre
                </a>
                @endif
            </div>
        </div>

        <!-- Members Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($members as $member)
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-xl font-medium text-indigo-600">
                                    {{ strtoupper(substr($member->name, 0, 2)) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <a href="{{ route('members.show', $member) }}" class="text-lg font-medium text-gray-900 hover:text-indigo-600">
                                {{ $member->name }}
                            </a>
                            <p class="text-sm text-gray-500">
                                {{ $member->email }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-3 gap-4 border-t border-gray-200 pt-4">
                        <div class="text-center">
                            <span class="text-lg font-semibold text-yellow-600">{{ $member->pending_tasks }}</span>
                            <p class="text-xs text-gray-500">En attente</p>
                        </div>
                        <div class="text-center">
                            <span class="text-lg font-semibold text-blue-600">{{ $member->in_progress_tasks }}</span>
                            <p class="text-xs text-gray-500">En cours</p>
                        </div>
                        <div class="text-center">
                            <span class="text-lg font-semibold text-green-600">{{ $member->completed_tasks }}</span>
                            <p class="text-xs text-gray-500">Terminées</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
