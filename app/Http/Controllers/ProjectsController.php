<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectsController extends Controller
{

    /**
     * Display a listing of the projects.
     */
    public function index()
    {
        $user = auth()->user();
        if ($user && $user->role === 'team_member') {
            $projects = $user->memberProjects()->with(['members', 'tasks'])->get();
        } elseif ($user && $user->role === 'project_manager') {
            $projects = \App\Models\Project::with(['members', 'tasks', 'manager'])->where('manager_id', $user->id)->get();
        } else {
            $projects = \App\Models\Project::with(['members', 'tasks', 'manager'])->get();
        }
        
        // Test: Create a notification if user has no notifications
        if ($user && $user->notifications()->count() === 0) {
            $user->notify(new \App\Notifications\TaskReminder(\App\Models\Task::first() ?? new \App\Models\Task(['title' => 'Test Task'])));
        }
        
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        $teamMembers = \App\Models\User::where('role', 'team_member')->get();
        return view('projects.create', compact('teamMembers'));
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);
        $user = auth()->user();
        if ($user && $user->role === 'project_manager') {
            $validated['manager_id'] = $user->id;
        }
        $project = \App\Models\Project::create($validated);
        $memberIds = $validated['members'] ?? [];
        if ($user && !in_array($user->id, $memberIds)) {
            $memberIds[] = $user->id;
        }
        $project->members()->sync($memberIds);
        // Notify each member
        foreach ($memberIds as $memberId) {
            $member = \App\Models\User::find($memberId);
            if ($member) {
                $member->notify(new \App\Notifications\ProjectAssigned($project));
            }
        }
        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project.
     */
    public function show(\App\Models\Project $project)
    {
        // Removed manager/member authorization check to allow all authenticated users to view any project
        $project->load(['tasks', 'members']);
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(\App\Models\Project $project)
    {
        $managers = \App\Models\User::where('role', 'project_manager')->get();
        $teamMembers = \App\Models\User::where('role', 'team_member')->get();
        $selectedMembers = $project->members()->pluck('users.id')->toArray();
        return view('projects.edit', compact('project', 'managers', 'teamMembers', 'selectedMembers'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(\Illuminate\Http\Request $request, \App\Models\Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'manager_id' => 'nullable|exists:users,id',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);
        $project->update($validated);
        // Sync project members if provided
        if (isset($validated['members'])) {
            // Get current and new member IDs
            $currentMembers = $project->members()->pluck('users.id')->toArray();
            $newMembers = $validated['members'];
            $project->members()->sync($newMembers);
            // Notify only newly added members
            $addedMembers = array_diff($newMembers, $currentMembers);
            foreach ($addedMembers as $memberId) {
                $member = \App\Models\User::find($memberId);
                if ($member) {
                    $member->notify(new \App\Notifications\ProjectAssigned($project));
                }
            }
        }
        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(\App\Models\Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    public function join($projectId)
    {
        $user = auth()->user();
        $project = \App\Models\Project::findOrFail($projectId);
        if ($user && !$project->members->contains($user->id)) {
            $project->members()->attach($user->id);
            return back()->with('success', 'You have joined the project.');
        }
        return back()->with('info', 'You are already a member of this project.');
    }
}
