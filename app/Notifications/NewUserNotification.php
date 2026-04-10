<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserNotification extends Notification
// class NewUserNotification extends Notification implements ShouldQueue
{
    // use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $user_detail;
    public function __construct($user_detail)
    {
        //
        $this->user_detail = $user_detail;
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
                    ->greeting('Hello '.$this->user_detail['name'])
                    ->subject('Welcome to our application')
                    ->line('you are registered successfully.')
                    ->line('Your email is: '.$this->user_detail['email'])
                    ->action('Notification Action', "#")
                    ->line('Thank you for using our application!');
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
