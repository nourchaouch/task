@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded shadow mt-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Project: {{ $project->name }}</h2>
    <p class="mb-2"><strong>Color:</strong> <span class="inline-block px-3 py-1 rounded text-white text-xs font-semibold" style="background:{{ $project->color }}; min-width: 70px; text-align:center;">{{ $project->color }}</span></p>
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
    <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded hover:bg-gray-300 transition">Back to Projects</a>
</div>
@endsection 