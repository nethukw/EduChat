<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewSessionNotification extends Notification
{
    use Queueable;

    /**
     * Store data required for the notification.
     */
    protected array $data;

    /**
     * Create a new notification instance.
     *
     * @param array $data
     *  Expected keys:
     *  - title   (string, optional)
     *  - message (string, optional)
     *  - link    (string|URL, optional)
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        // Stored in database for bell icon
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     * This data is stored in the `notifications` table.
     */
    public function toArray(object $notifiable): array
    {
        // Default link based on user role (safe & future-proof)
        $defaultLink = match ($notifiable->role) {
            0 => route('student.sessions'),          // Student → Browse classes
            1 => route('teacher.sessions.index'),    // Teacher → My sessions
            2 => route('advisor.dashboard'),         // Advisor → Dashboard
            3 => route('admin.sessions'),             // Admin → All sessions
            default => route('dashboard'),
        };

        return [
            'title'   => $this->data['title']   ?? 'New Notification! 🎓',
            'message' => $this->data['message'] ?? 'Check your dashboard for updates.',
            'link'    => $this->data['link']    ?? $defaultLink,
        ];
    }
}