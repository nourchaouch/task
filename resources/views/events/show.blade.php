@php use Illuminate\Support\Facades\Auth; @endphp
@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded shadow mt-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Event: {{ $event->title }}</h2>
    <p class="mb-2"><strong>Description:</strong> {{ $event->description }}</p>
    <p class="mb-2"><strong>Start Date:</strong> <span class="text-gray-700">{{ $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('Y-m-d H:i') : '-' }}</span></p>
    <p class="mb-2"><strong>End Date:</strong> <span class="text-gray-700">{{ $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d H:i') : '-' }}</span></p>
    <p class="mb-2"><strong>Status:</strong> {{ $event->status }}</p>
    <p class="mb-2"><strong>Project:</strong> {{ $event->project->name ?? '-' }}</p>
    <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded hover:bg-gray-300 transition">Back to Events</a>
</div>
@endsection 