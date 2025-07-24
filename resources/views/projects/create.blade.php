@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow mt-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Create Project</h2>
    <form action="{{ route('projects.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Project Name</label>
            <input type="text" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="name" name="name" required>
        </div>
        <div>
            <label for="members" class="block text-sm font-medium text-gray-700">Project Members</label>
            <select name="members[]" id="members" multiple class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @foreach($teamMembers as $member)
                    <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->email }})</option>
                @endforeach
            </select>
            <small class="text-gray-500">Hold Ctrl (Windows) or Command (Mac) to select multiple members.</small>
        </div>
        <div class="flex space-x-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700 transition">Create</button>
            <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded hover:bg-gray-300 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection 