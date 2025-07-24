<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\Event;
use App\Notifications\TaskReminder;
use App\Notifications\EventReminder;
use Carbon\Carbon;

class SendReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Envoie des rappels pour les tâches et événements à venir';

    public function handle()
    {
        $now = Carbon::now();
        $in24h = $now->copy()->addDay();

        // Tâches à venir dans 24h
        $tasks = Task::where('due_date', '>=', $now)
            ->where('due_date', '<=', $in24h)
            ->where('status', '!=', 'completed')
            ->get();

        foreach ($tasks as $task) {
            if ($task->assignedTo) {
                $task->assignedTo->notify(new TaskReminder($task));
            }
        }

        // Événements à venir dans 24h
        $events = Event::where('date', '>=', $now)
            ->where('date', '<=', $in24h)
            ->where('status', '!=', 'completed')
            ->get();

        foreach ($events as $event) {
            foreach ($event->members as $member) {
                $member->notify(new EventReminder($event));
            }
        }

        $this->info('Rappels envoyés avec succès.');
    }
} 