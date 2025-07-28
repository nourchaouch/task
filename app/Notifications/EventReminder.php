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
            ->line("L'événement \"{$this->event->title}\" est prévu du {$this->event->start_date->format('d/m/Y H:i')} au {$this->event->end_date->format('d/m/Y H:i')}.")
            ->action('Voir l\'événement', route('events.show', $this->event));
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->event->title,
            'start_date' => $this->event->start_date,
            'end_date' => $this->event->end_date,
        ];
    }
} 