<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EnrollmentSuccess extends Notification
{
    use Queueable;

    protected $enrollData;

    /**
     * Create a new notification instance.
     * Retrieve class name and other details from $enrollData
     */
    public function __construct($enrollData)
    {
        $this->enrollData = $enrollData;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
       // Use 'database' instead of 'mail' to show this on the Bell Icon
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Enrollment Successful! 🎉',
            'message' => 'You have successfully enrolled in the "' . $this->enrollData['session_title'] . '" session.',
            'link' => '/sessions/' . $this->enrollData['session_id'],
        ];
    }
}