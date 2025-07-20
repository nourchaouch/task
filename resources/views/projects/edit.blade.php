@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow mt-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Project</h2>
    <form action="{{ route('projects.update', $project) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Project Name</label>
            <input type="text" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="name" name="name" value="{{ $project->name }}" required>
        </div>
        <div>
            <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
            <input type="color" class="mt-1 block w-24 h-10 rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="color" name="color" value="{{ $project->color }}" title="Choose your color">
        </div>
        <div>
            <label for="manager_id" class="block text-sm font-medium text-gray-700">Project Manager</label>
            <select class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="manager_id" name="manager_id">
                <option value="">Select Manager</option>
                @foreach($managers as $manager)
                    <option value="{{ $manager->id }}" @if($project->manager_id == $manager->id) selected @endif>{{ $manager->name }} ({{ $manager->email }})</option>
                @endforeach
            </select>
        </div>
        <div class="flex space-x-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700 transition">Update</button>
            <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded hover:bg-gray-300 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection 