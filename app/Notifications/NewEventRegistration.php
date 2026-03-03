<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEventRegistration extends Notification
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
            ->subject('New Event Registration')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($this->registration->attendee->name . ' has registered for your event:')
            ->line('Event: ' . $this->registration->event->title)
            ->action('View Registrations', url('/organiser/event/' . $this->registration->event->id . '/registrations'))
            ->line('Thank you for using our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'registration_id' => $this->registration->id,
            'event_id' => $this->registration->event->id,
            'event_title' => $this->registration->event->title,
            'attendee_name' => $this->registration->attendee->name,
            'message' => $this->registration->attendee->name .
                ' has registered for your event "' .
                $this->registration->event->title . '".',
        ];
    }
}
