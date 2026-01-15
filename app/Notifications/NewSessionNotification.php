<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSessionNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        // ⚠️ IMPORTANT: Change 'mail' to 'database' for the Bell Icon to work
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     * This data will be saved in the 'notifications' table.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Session Available! 🎓',
            'message' => 'A new class has been posted. Check the Browse Classes section to enroll!',
            'link' => '/sessions' // Optional link
        ];
    }
}