@extends('layouts.app')
@section('content')
@php
    $authUser = auth()->user();
    $isEdit = isset($task);
@endphp
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow mt-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">
        {{ isset($task) ? 'Edit Task' : 'Create Task' }}
    </h2>
    <form action="{{ isset($task) ? route('tasks.update', $task) : route('tasks.store') }}" method="POST" class="space-y-4">
        @csrf
        @if(isset($task))
            @method('PUT')
        @endif
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Task Title</label>
            <input type="text" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="title" name="title" value="{{ old('title', $task->title ?? '') }}" required>
        </div>
        <div>
            <label for="project_id" class="block text-sm font-medium text-gray-700">Project</label>
            <select class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="project_id" name="project_id" required>
                <option value="">Select Project</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" @if(old('project_id', $task->project_id ?? '') == $project->id) selected @endif>{{ $project->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="assigned_to" class="block text-sm font-medium text-gray-700">Assign To</label>
            <select class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="assigned_to" name="assigned_to">
                <option value="">Unassigned</option>
                @foreach($projects as $project)
                    @if(old('project_id', $task->project_id ?? '') == $project->id)
                        @php
                            $memberIds = $project->members->pluck('id')->toArray();
                            $selectedAssigned = old('assigned_to', $task->assigned_to ?? '');
                        @endphp
                        @foreach($project->members as $user)
                            <option value="{{ $user->id }}" @if($selectedAssigned == $user->id) selected @endif>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                        @if($project->manager && !in_array($project->manager->id, $memberIds))
                            <option value="{{ $project->manager->id }}" @if($selectedAssigned == $project->manager->id) selected @endif>{{ $project->manager->name }} ({{ $project->manager->email }}) [Manager]</option>
                        @endif
                    @endif
                @endforeach
            </select>
        </div>
        <div>
            <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
            <select class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="priority" name="priority" required>
                @php
                    $priorityOptions = [0 => 'Low', 1 => 'Medium', 2 => 'High', 3 => 'Critical'];
                    $selectedPriority = old('priority', $task->priority ?? 0);
                @endphp
                @foreach($priorityOptions as $value => $label)
                    <option value="{{ $value }}" @if($selectedPriority == $value) selected @endif>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
            <input type="date" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="due_date" name="due_date" value="{{ old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d') : '') }}">
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" id="status" name="status" required>
                <option value="pending" @if(old('status', $task->status ?? 'pending') == 'pending') selected @endif>Pending</option>
                <option value="in_progress" @if(old('status', $task->status ?? '') == 'in_progress') selected @endif>In Progress</option>
                <option value="completed" @if(old('status', $task->status ?? '') == 'completed') selected @endif>Completed</option>
            </select>
        </div>
        <div class="flex space-x-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700 transition">
                {{ isset($task) ? 'Update' : 'Create' }}
            </button>
            <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded hover:bg-gray-300 transition">Cancel</a>
        </div>
    </form>
</div>
@push('scripts')
<script>
    // Build a mapping of project_id => [members]
    const projectMembers = {};
    @foreach($projects as $project)
        projectMembers[{{ $project->id }}] = [
            @foreach($project->members as $member)
                { id: {{ $member->id }}, name: "{{ addslashes($member->name) }}", email: "{{ addslashes($member->email) }}" },
            @endforeach
            @if($project->manager && !$project->members->pluck('id')->contains($project->manager->id))
                { id: {{ $project->manager->id }}, name: "{{ addslashes($project->manager->name) }}", email: "{{ addslashes($project->manager->email) }}", isManager: true },
            @endif
        ];
    @endforeach

    const projectSelect = document.getElementById('project_id');
    const assignedToSelect = document.getElementById('assigned_to');
    const selectedAssigned = "{{ old('assigned_to', $task->assigned_to ?? '') }}";

    function updateMembers() {
        const projectId = projectSelect.value;
        // Remove all except the first option
        while (assignedToSelect.options.length > 1) {
            assignedToSelect.remove(1);
        }
        if (projectMembers[projectId]) {
            projectMembers[projectId].forEach(member => {
                const opt = document.createElement('option');
                opt.value = member.id;
                opt.textContent = member.name + ' (' + member.email + ')'+(member.isManager ? ' [Manager]' : '');
                if (String(member.id) === selectedAssigned) {
                    opt.selected = true;
                }
                assignedToSelect.appendChild(opt);
            });
        }
    }
    projectSelect.addEventListener('change', updateMembers);
    // Call once on page load if a project is preselected
    updateMembers();
</script>
@endpush
@endsection 