@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Task: {{ $task->name }}</h2>
    <p><strong>Project:</strong> {{ $task->project->name ?? '-' }}</p>
    <p><strong>Status:</strong> <span class="badge bg-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'warning' : 'secondary') }}">{{ ucfirst($task->status) }}</span></p>
    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back to Tasks</a>
</div>
@endsection 