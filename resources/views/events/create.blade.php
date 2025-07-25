@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow mt-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Create Event</h2>
    <form action="{{ route('events.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Event Title</label>
            <input type="text" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="title" name="title" required>
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="description" name="description"></textarea>
        </div>
        <div>
            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
            <input type="datetime-local" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="date" name="date" required>
        </div>
        <div>
            <label for="project_id" class="block text-sm font-medium text-gray-700">Project</label>
            <select class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="project_id" name="project_id" required>
                <option value="">Select Project</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="status" name="status" required>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <div class="flex space-x-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700 transition">Create</button>
            <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded hover:bg-gray-300 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection 