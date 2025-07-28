<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Project;

class ProjectAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    public $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Vous avez été ajouté à un projet')
            ->line("Vous avez été ajouté au projet : {$this->project->name}.")
            ->action('Voir le projet', route('projects.show', $this->project->id));
    }

    public function toArray($notifiable)
    {
        return [
            'project_id' => $this->project->id,
            'name' => $this->project->name,
        ];
    }
} 