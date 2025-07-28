<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Task;

class CalendarController extends Controller
{
    public function data()
    {
        $events = Event::all()->map(function($event) {
            return [
                'title' => $event->title,
                'start' => $event->start_date,
                'end'   => $event->end_date,
                'color' => '#38b2ac', // turquoise
                'url'   => route('events.show', $event),
            ];
        });

        $tasks = Task::all()->map(function($task) {
            return [
                'title' => $task->title,
                'start' => $task->due_date,
                'end'   => $task->due_date,
                'color' => '#f6ad55', // orange
                'url'   => route('tasks.show', $task),
            ];
        });

        return response()->json($events->merge($tasks));
    }
} 