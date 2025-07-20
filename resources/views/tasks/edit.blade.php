@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Edit Task</h2>
    <form action="{{ route('tasks.update', $task) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Task Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $task->name }}" required>
        </div>
        <div class="mb-3">
            <label for="project_id" class="form-label">Project</label>
            <select class="form-select" id="project_id" name="project_id" required>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" @if($task->project_id == $project->id) selected @endif>{{ $project->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="pending" @if($task->status == 'pending') selected @endif>Pending</option>
                <option value="in_progress" @if($task->status == 'in_progress') selected @endif>In Progress</option>
                <option value="completed" @if($task->status == 'completed') selected @endif>Completed</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 