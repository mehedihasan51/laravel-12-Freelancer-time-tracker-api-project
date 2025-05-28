<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DailyLimitReachedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $totalHours;

    public function __construct($totalHours)
    {
        $this->totalHours = $totalHours;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Daily Time Limit Reached')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line("You've logged a total of **{$this->totalHours} hours** today.")
                    ->line('Please review your time logs if necessary.')
                    ->action('View Time Logs', url('/time-logs'))
                    ->line('This is an automated message.');
    }
}
