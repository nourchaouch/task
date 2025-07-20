<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('rolemiddleware:team_member')->only('memberDashboard');
        $this->middleware('rolemiddleware:project_manager')->only('managerDashboard');
    }

    public function memberDashboard()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')
                    ->with('error', 'Veuillez vous connecter pour accéder à votre tableau de bord.');
            }

            // Get tasks with error handling
            try {
                $tasks = Task::where('assigned_to', $user->id)
                    ->with('project')
                    ->orderBy('due_date', 'asc')
                    ->take(5)
                    ->get();
            } catch (\Exception $e) {
                \Log::error('Error fetching tasks: ' . $e->getMessage());
                $tasks = collect();
            }

            // Get task statistics with error handling
            try {
                $taskStats = [
                    'pending' => Task::where('assigned_to', $user->id)
                        ->where('status', 'pending')
                        ->count(),
                    'in_progress' => Task::where('assigned_to', $user->id)
                        ->where('status', 'in_progress')
                        ->count(),
                    'completed' => Task::where('assigned_to', $user->id)
                        ->where('status', 'completed')
                        ->count(),
                ];
            } catch (\Exception $e) {
                \Log::error('Error fetching task stats: ' . $e->getMessage());
                $taskStats = [
                    'pending' => 0,
                    'in_progress' => 0,
                    'completed' => 0
                ];
            }

            // Get projects with error handling
            try {
                $projects = Project::whereHas('tasks', function($query) use ($user) {
                    $query->where('assigned_to', $user->id);
                })->take(3)->get();
            } catch (\Exception $e) {
                \Log::error('Error fetching projects: ' . $e->getMessage());
                $projects = collect();
            }

            return view('dashboard.member', compact('tasks', 'projects', 'taskStats'));

        } catch (\Exception $e) {
            \Log::error('Error in member dashboard: ' . $e->getMessage());
            return redirect()->route('home.index')
                ->with('error', 'Une erreur est survenue. Veuillez réessayer plus tard.');
        }
    }

    public function managerDashboard()
    {
        $user = Auth::user();
        // Get projects count
        $projectsCount = Project::count();
        
        // Get tasks count and statistics
        $tasksCount = Task::count();
        $tasksStats = [
            'pending' => Task::where('status', 'pending')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'total' => $tasksCount
        ];
        
        // Get recent tasks with related data
        $recentTasks = Task::with(['assignedTo', 'project'])
                          ->latest()
                          ->take(5)
                          ->get();
        
        // Get recent projects with their tasks
        $recentProjects = Project::with(['tasks'])
                                ->latest()
                                ->take(3)
                                ->get();
        
        return view('dashboard.manager', compact(
            'user',
            'projectsCount',
            'tasksCount',
            'tasksStats',
            'recentTasks',
            'recentProjects'
        ));
    }
}
