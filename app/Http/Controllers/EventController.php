<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Project;
use App\Models\User;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index()
    {
        $events = Event::with('project')->get();
        return view('events.index', compact('events'));
    }
    

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        $projects = Project::all();
        return view('events.create', compact('projects'));
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'date' => 'required|date',
            'status' => 'nullable|string',
        ]);
        $event = Event::create($validated);
        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        $event->load('project');
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        $projects = Project::all();
        return view('events.edit', compact('event', 'projects'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'date' => 'required|date',
            'status' => 'nullable|string',
        ]);
        $event->update($validated);
        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }

    /**
     * Allow only event members to update the event status.
     */
    public function updateStatus(Request $request, Event $event)
    {
        $user = auth()->user();
        // Only event members can update the status
        if (!$event->members->contains($user->id)) {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,blocked,completed',
        ]);
        $event->status = $validated['status'];
        $event->save();
        return redirect()->back()->with('success', 'Event status updated.');
    }
}
