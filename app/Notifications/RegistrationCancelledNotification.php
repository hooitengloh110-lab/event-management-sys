<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationCancelledNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Registration $registration)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
      return (new MailMessage)
        ->subject('Registration Cancelled')
        ->greeting('Hello ' . $notifiable->name . ',')
        ->line("Your registration for '{$this->registration->event->title}' has been cancelled.")
        ->line('If this was unexpected, please contact the organiser.')
        ->action('Browse Events', route('event.index'))
        ->line('We hope to see you at another event soon.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
      return [
        'event_id' => $this->registration->event->id,
        'registration_id' => $this->registration->id,
        'title' => $this->registration->event->title,
        'status' => 'cancelled',
        'start_datetime' => $this->registration->event->start_datetime,
        'end_datetime' => $this->registration->event->end_datetime,
      ];
    }
}
