@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded shadow mt-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Project: {{ $project->name }}</h2>
    <div class="mb-4">
        <strong>Members:</strong>
        @if($project->members && $project->members->count())
            @foreach($project->members as $member)
                <span class="inline-block bg-gray-100 text-gray-800 rounded px-2 py-1 text-xs mr-1">{{ $member->name }}</span>
            @endforeach
        @else
            <span class="text-xs text-gray-400">No members</span>
        @endif
    </div>
    <hr class="my-4">
    <h4 class="text-lg font-semibold text-gray-700 mb-2">Tasks</h4>
    @if(isset($project->tasks) && $project->tasks->count())
        <ul class="divide-y divide-gray-100 mb-3">
            @foreach($project->tasks as $task)
                <li class="flex justify-between items-center py-2">
                    <span>{{ $task->title }}</span>
                    <span class="px-2 py-1 rounded text-xs font-semibold
                        @if($task->status == 'completed') bg-green-100 text-green-800
                        @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800
                        @else bg-yellow-100 text-yellow-800
                        @endif">{{ ucfirst($task->status) }}</span>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-500">No tasks for this project.</p>
    @endif
    <hr class="my-4">
    <h4 class="text-lg font-semibold text-gray-700 mb-2">Events</h4>
    @if(isset($project->events) && $project->events->count())
        <ul class="divide-y divide-gray-100 mb-3">
            @foreach($project->events as $event)
                <li class="flex justify-between items-center py-2">
                    <span>{{ $event->title }} ({{ $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('Y-m-d H:i') : '-' }} - {{ $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d H:i') : '-' }})</span>
                    <span class="px-2 py-1 rounded text-xs font-semibold
                        @if($event->status == 'completed') bg-green-100 text-green-800
                        @elseif($event->status == 'in_progress') bg-blue-100 text-blue-800
                        @else bg-yellow-100 text-yellow-800
                        @endif">{{ ucfirst($event->status) }}</span>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-500">No events for this project.</p>
    @endif
    <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded hover:bg-gray-300 transition">Back to Projects</a>
</div>
@endsection 