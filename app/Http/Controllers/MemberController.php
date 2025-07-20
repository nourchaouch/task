<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('rolemiddleware:team_member');
    }

    public function index()
    {
        $assignedTasks = Task::where('assigned_to', Auth::id())->get();
        $completedTasks = Task::where('assigned_to', Auth::id())->where('status', 'completed')->count();
        $pendingTasks = Task::where('assigned_to', Auth::id())->where('status', 'pending')->count();
        $projects = Project::whereHas('tasks', function($query) {
            $query->where('assigned_to', Auth::id());
        })->get();

        return view('member.dashboard', compact('assignedTasks', 'completedTasks', 'pendingTasks', 'projects'));
    }
}
