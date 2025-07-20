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
        $projects = \App\Models\Project::all();
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        $managers = \App\Models\User::where('role', 'project_manager')->get();
        return view('projects.create', compact('managers'));
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:50',
            'manager_id' => 'nullable|exists:users,id',
        ]);
        \App\Models\Project::create($validated);
        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project.
     */
    public function show(\App\Models\Project $project)
    {
        $project->load('tasks');
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(\App\Models\Project $project)
    {
        $managers = \App\Models\User::where('role', 'project_manager')->get();
        return view('projects.edit', compact('project', 'managers'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(\Illuminate\Http\Request $request, \App\Models\Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:50',
            'manager_id' => 'nullable|exists:users,id',
        ]);
        $project->update($validated);
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
}
