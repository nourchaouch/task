@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Projects</h2>
        @php $user = Auth::user(); @endphp
        @if($user && $user->role === 'project_manager')
            <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700 transition">Create Project</a>
        @elseif($user && $user->role === 'team_member')
            <span class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-500 text-sm font-medium rounded cursor-not-allowed">View Only</span>
        @endif
    </div>
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach($projects as $project)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $project->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                            <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded hover:bg-blue-200 transition">View</a>
                            @if($user && $user->role === 'project_manager')
                                <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded hover:bg-yellow-200 transition">Edit</a>
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 text-xs font-medium rounded hover:bg-red-200 transition" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="bg-gray-50 px-6 py-2">
                            <div class="mb-2">
                                <strong>Members:</strong>
                                @if($project->members && $project->members->count())
                                    @foreach($project->members as $member)
                                        <span class="inline-block bg-gray-100 text-gray-800 rounded px-2 py-1 text-xs mr-1">{{ $member->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-xs text-gray-400">No members</span>
                                @endif
                            </div>
                            <div class="mb-2">
                                <strong>Tasks:</strong>
                                @if($project->tasks && $project->tasks->count())
                                    @foreach($project->tasks as $task)
                                        <span class="inline-block bg-blue-100 text-blue-800 rounded px-2 py-1 text-xs mr-1">{{ $task->title }} ({{ $task->status }})</span>
                                    @endforeach
                                @else
                                    <span class="text-xs text-gray-400">No tasks</span>
                                @endif
                            </div>
                            <div>
                                <strong>Events:</strong>
                                @if($project->events && $project->events->count())
                                    @foreach($project->events as $event)
                                        <span class="inline-block bg-green-100 text-green-800 rounded px-2 py-1 text-xs mr-1">{{ $event->title }}
                                            @if($event->members && $event->members->count())
                                                <span class="text-xs text-gray-700">[Members:
                                                    @foreach($event->members as $emember)
                                                        {{ $emember->name }}@if(!$loop->last),@endif
                                                    @endforeach
                                                ]</span>
                                            @endif
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-xs text-gray-400">No events</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection 