@php use Illuminate\Support\Facades\Auth; @endphp
@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded shadow mt-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Event: {{ $event->title }}</h2>
    <p class="mb-2"><strong>Description:</strong> {{ $event->description }}</p>
    <p class="mb-2"><strong>Date:</strong> {{ $event->date ? \Carbon\Carbon::parse($event->date)->format('Y-m-d H:i') : '-' }}</p>
    <p class="mb-2"><strong>Status:</strong> {{ $event->status }}</p>
    @php $user = Auth::user(); @endphp
    @if($user && $event->members->contains($user->id))
        <form action="{{ route('events.updateStatus', $event) }}" method="POST" class="mb-4">
            @csrf
            @method('PATCH')
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Update Status:</label>
            <select name="status" id="status" class="border rounded px-2 py-1 text-sm mb-2">
                <option value="todo" @if($event->status == 'todo') selected @endif>À faire</option>
                <option value="in_progress" @if($event->status == 'in_progress') selected @endif>En cours</option>
                <option value="blocked" @if($event->status == 'blocked') selected @endif>Bloqué</option>
                <option value="completed" @if($event->status == 'completed') selected @endif>Terminé</option>
            </select>
            <button type="submit" class="ml-2 px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition">Update</button>
        </form>
    @endif
    <p class="mb-2"><strong>Project:</strong> {{ $event->project->name ?? '-' }}</p>
    <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded hover:bg-gray-300 transition">Back to Events</a>
</div>
@endsection 