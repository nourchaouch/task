@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Create Project</h2>
    <form action="{{ route('projects.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Project Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="color" class="form-label">Color</label>
            <input type="color" class="form-control form-control-color" id="color" name="color" value="#563d7c" title="Choose your color">
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 