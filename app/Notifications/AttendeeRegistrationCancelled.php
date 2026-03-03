<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttendeeRegistrationCancelled extends Notification
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
        ->subject('Attendee Cancellation - ' . $this->registration->event->title)
        ->greeting('Hello ' . $notifiable->name . ',')
        ->line("**{$this->registration->attendee->name}** has cancelled their registration for **{$this->registration->event->title}**.")
        ->line('Their seat is now available.')
        ->line('No action is required on your part unless you wish to follow up.');
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
          'attendee_name' => $this->registration->attendee->name,
          'title' => $this->registration->event->title,
          'status' => 'cancelled',
          'start_datetime' => $this->registration->event->start_datetime,
          'end_datetime' => $this->registration->event->end_datetime,
        ];
    }
}
