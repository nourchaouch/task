<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;

class TasksController extends Controller
{

    public function create()
    {
        $projects = \App\Models\Project::all();
        $users = \App\Models\User::all();
        return view('tasks.create', compact('projects', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'nullable|integer',
            'due_date' => 'nullable|date',
            'status' => 'required|string',
        ]);
        $validated['created_by'] = auth()->id();
        \App\Models\Task::create($validated);
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit($id)
    {
        $task = \App\Models\Task::findOrFail($id);
        $projects = \App\Models\Project::all();
        $users = \App\Models\User::all();
        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(Request $request, $id)
    {
        $task = \App\Models\Task::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'nullable|integer',
            'due_date' => 'nullable|date',
            'status' => 'required|string',
        ]);
        $task->update($validated);
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }


    public function delete($id)
    {
        // Find the relevant task instance
        $task = Task::find($id);

        if (!$task) {
            // If task not found, abort with 404 error
            abort(404, 'Task not found.');
        }

        // Soft delete the task
        $task->delete();

        // Redirect or show a message indicating successful deletion
        return redirect()->route('home.index');
    }


    public function filter(Request $request)
    {
        $filters = $request->only(['project', 'is_completed', 'search']);

        $tasks = Task::getAllTasksWithFilters($filters);

        return view('home.index', compact('tasks'));
    }


    public function toggleCompletion(Request $request, $id)
    {
        // Find the relevant task instance
        $task = Task::find($id);

        if (!$task) {
            // If task not found, return 404 error
            return response()->json(['error' => 'Task not found.'], 404);
        }

        // Toggle the is_completed value
        $task->is_completed = !$task->is_completed;
        $task->save();

        // Return the updated status
        return response()->json(['success' => true, 'is_completed' => $task->is_completed]);
    }


    public function updatePriority(Request $request)
    {
        $taskOrder = $request->input('taskOrder');
        foreach ($taskOrder as $index => $taskId) {
            $task = Task::find($taskId);
            if ($task) {
                $task->update(['priority' => $index + 1]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Display a listing of the tasks.
     */
    public function index()
    {
        $tasks = \App\Models\Task::with(['project', 'assignedTo'])->get();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Display the specified task.
     */
    public function show($id)
    {
        $task = \App\Models\Task::with(['project', 'assignedTo'])->findOrFail($id);
        return view('tasks.show', compact('task'));
    }
}
