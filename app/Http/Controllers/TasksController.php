<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;

class TasksController extends Controller
{

    public function create(Request $request)
    {
        $projects = \App\Models\Project::with(['members', 'manager'])->get();
        $users = collect();
        if ($request->has('project_id')) {
            $project = \App\Models\Project::with('manager')->find($request->input('project_id'));
            if ($project) {
                $users = $project->members;
            }
        }
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
            // 'status' => 'required|string', // Remove this line
        ]);
        $validated['created_by'] = auth()->id();
        $task = \App\Models\Task::create($validated);
        // Notify assigned user if set
        if ($task->assigned_to) {
            $user = \App\Models\User::find($task->assigned_to);
            if ($user) {
                $user->notify(new \App\Notifications\TaskReminder($task));
            }
        }
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit($id)
    {
        $task = \App\Models\Task::findOrFail($id);
        $projects = \App\Models\Project::with(['members', 'manager'])->get();
        $users = \App\Models\User::all();
        return view('tasks.create', compact('task', 'projects', 'users'));
    }

    public function update(Request $request, $id)
    {
        $task = \App\Models\Task::findOrFail($id);
        $user = auth()->user();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'nullable|integer',
            'due_date' => 'nullable|date',
            'status' => 'required|string',
        ]);
        $project = \App\Models\Project::find($validated['project_id']);
        if ($user && $project && $project->members->contains($user->id)) {
            if ($request->has('assign_self') && $request->input('assign_self') == '1') {
                $validated['assigned_to'] = $user->id;
            }
        }
        $oldAssignedTo = $task->assigned_to;
        $task->update($validated);
        // Notify only if assigned_to changed and is set
        if ($task->assigned_to && $task->assigned_to != $oldAssignedTo) {
            $assignedUser = \App\Models\User::find($task->assigned_to);
            if ($assignedUser) {
                $assignedUser->notify(new \App\Notifications\TaskReminder($task));
            }
        }
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

    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $user = auth()->user();
        // Only the assigned member can update the status
        if ($task->assigned_to !== $user->id) {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'status' => 'required|in:pending,completed',
        ]);
        $task->status = $validated['status'];
        $task->save();
        return redirect()->back()->with('success', 'Task status updated.');
    }

    /**
     * Display a listing of the tasks.
     */
    public function index()
    {
        $user = auth()->user();
        if ($user && $user->role === 'team_member') {
            $tasks = \App\Models\Task::with(['project', 'assignedTo'])->where('assigned_to', $user->id)->get();
        } elseif ($user && $user->role === 'project_manager') {
            $tasks = \App\Models\Task::with(['project', 'assignedTo'])
                ->whereHas('project', function($query) use ($user) {
                    $query->where('manager_id', $user->id);
                })->get();
        } else {
            $tasks = \App\Models\Task::with(['project', 'assignedTo'])->get();
        }
        
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
