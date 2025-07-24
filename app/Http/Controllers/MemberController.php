<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Only apply team_member middleware to methods that require it, not index
        // $this->middleware('rolemiddleware:team_member');
    }

    public function index()
    {
        $user = auth()->user();
        if ($user && $user->role === 'project_manager') {
            // Get all projects managed by this user
            $projectIds = $user->managedProjects()->pluck('id');
            // Get members assigned to these projects
            $members = \App\Models\User::where('role', 'team_member')
                ->whereHas('memberProjects', function($q) use ($projectIds) {
                    $q->whereIn('projects.id', $projectIds);
                })
                ->with(['memberProjects' => function($q) use ($projectIds) {
                    $q->whereIn('projects.id', $projectIds);
                }])
                ->get();
        } else {
            $members = \App\Models\User::with('memberProjects')->where('role', 'team_member')->get();
        }
        return view('members.index', compact('members'));
    }
}
