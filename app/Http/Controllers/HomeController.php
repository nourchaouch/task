<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get statistics for the landing page
        $projectsCount = Project::count();
        $tasksCount = Task::whereNotIn('status', ['completed'])->count(); // Only count active tasks
        $teamMembersCount = User::count();

        return view('home.index', compact('projectsCount', 'tasksCount', 'teamMembersCount'));
    }
}
