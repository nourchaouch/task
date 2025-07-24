<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Event;

class EventReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Rappel : événement à venir')
            ->line("L'événement \"{$this->event->title}\" est prévu pour le {$this->event->date->format('d/m/Y H:i')}.")
            ->action('Voir l\'événement', route('events.show', $this->event->id));
    }

    public function toArray($notifiable)
    {
        return [
            'event_id' => $this->event->id,
            'title' => $this->event->title,
            'date' => $this->event->date,
        ];
    }
} 