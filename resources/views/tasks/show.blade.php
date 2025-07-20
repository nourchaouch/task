@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow mt-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Task: {{ $task->title }}</h2>
    <p class="mb-2"><strong>Project:</strong> <span class="text-gray-700">{{ $task->project->name ?? '-' }}</span></p>
    <p class="mb-2"><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs font-semibold
        @if($task->status == 'completed') bg-green-100 text-green-800
        @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800
        @else bg-yellow-100 text-yellow-800
        @endif">{{ ucfirst($task->status) }}</span></p>
    <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded hover:bg-gray-300 transition">Back to Tasks</a>
</div>
@endsection 