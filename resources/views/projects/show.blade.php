@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Project: {{ $project->name }}</h2>
    <p><strong>Color:</strong> <span style="background:{{ $project->color }};padding:4px 12px;border-radius:4px;color:#fff;">{{ $project->color }}</span></p>
    <hr>
    <h4>Tasks</h4>
    @if(isset($project->tasks) && $project->tasks->count())
        <ul class="list-group mb-3">
            @foreach($project->tasks as $task)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $task->name }}
                    <span class="badge bg-secondary">{{ ucfirst($task->status) }}</span>
                </li>
            @endforeach
        </ul>
    @else
        <p>No tasks for this project.</p>
    @endif
    <a href="{{ route('projects.index') }}" class="btn btn-secondary">Back to Projects</a>
</div>
@endsection 