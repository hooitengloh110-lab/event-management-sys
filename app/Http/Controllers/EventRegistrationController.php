<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Notifications\RegistrationCancelledNotification;
use App\Notifications\RegistrationConfirmedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class EventRegistrationController extends Controller
{
  public function index(Event $event) {
    Gate::authorize('view', $event);

    $event->load(['images']);

    $event->loadCount([
      'registrations as confirm_registrations_count' => function ($query) {
          $query->where('status', 'confirmed')->whereNull('deleted_at');
        },
      'registrations as cancelled_registrations_count' => function ($query) {
        $query->where('status', 'cancelled')->whereNull('deleted_at');
      },
    ]);
    $registrations = $event->registrations()
        ->with('attendee')
        ->latest()
        ->paginate(5)
        ->withQueryString();

    return Inertia::render('Organiser/EventRegistration', [
      'event' => $event,
      'registrations' => $registrations,
    ]);
  }

  public function confirm(Registration $registration)
  {
    Gate::authorize('update', $registration);

    $registration->update([
        'status' => 'confirmed'
    ]);

    $registration->attendee
        ->notify(new RegistrationConfirmedNotification($registration));

    return back()->with('success', 'Registration confirmed.');
  }

  public function cancel(Registration $registration)
  {
    Gate::authorize('cancel', $registration);

    $registration->update([
        'status' => 'cancelled'
    ]);

    $registration->attendee
        ->notify(new RegistrationCancelledNotification($registration));

    return back()->with('success', 'Registration cancelled.');
  }

}
