<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ResponsableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:project_manager');
    }

    public function index()
    {
        $projects = Project::where('user_id', Auth::id())->get();
        $totalTasks = Task::whereIn('project_id', $projects->pluck('id'))->count();
        $completedTasks = Task::whereIn('project_id', $projects->pluck('id'))->where('status', 'completed')->count();
        $teamMembers = User::where('role', 'team_member')->count();

        return view('responsable.dashboard', compact('projects', 'totalTasks', 'completedTasks', 'teamMembers'));
    }
}
