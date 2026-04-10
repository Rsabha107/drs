<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnnouncementCenter extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    protected $announcement;
    public function __construct($announcement)
    {
        //
        $this->announcement = $announcement;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject($this->announcement['subject'])
                    ->from('tracki.printemps@gmail.com', 'PD Support')
                    ->greeting($this->announcement['greeting'])
                    ->line($this->announcement['body'])
                    ->line($this->announcement['startdate'])
                    ->line($this->announcement['duedate'])
                    ->line($this->announcement['description'])
                    ->action($this->announcement['actiontext'], $this->announcement['actionurl'])
                    ->line($this->announcement['lastline']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
