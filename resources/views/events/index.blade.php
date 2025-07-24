@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Events</h2>
        @php $user = Auth::user(); @endphp
        @if($user && $user->role === 'project_manager')
            <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700 transition">Create Event</a>
        @elseif($user && $user->role === 'team_member')
            <span class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-500 text-sm font-medium rounded cursor-not-allowed">View Only</span>
        @endif
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($events as $event)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $event->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $event->date ? \Carbon\Carbon::parse($event->date)->format('Y-m-d H:i') : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $event->project->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($event->status === 'completed') bg-green-100 text-green-800
                                @elseif($event->status === 'in_progress') bg-blue-100 text-blue-800
                                @elseif($event->status === 'blocked') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($event->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                            <a href="{{ route('events.show', $event) }}" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded hover:bg-blue-200 transition">View</a>
                            @if($user && $user->role === 'project_manager')
                                <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded hover:bg-yellow-200 transition">Edit</a>
                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 text-xs font-medium rounded hover:bg-red-200 transition" onclick="return confirm('Are you sure you want to delete this event?')">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No events found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 