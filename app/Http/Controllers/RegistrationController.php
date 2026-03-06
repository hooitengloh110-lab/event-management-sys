<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Notifications\AttendeeRegistrationCancelled;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RegistrationController extends Controller
{
  public function cancel(Registration $registration)
  {
    Gate::authorize('cancel', $registration);

    if (!in_array($registration->status, ['pending', 'confirmed'])) {
      return back()->with('error', 'Cannot cancel this registration.');
    }

    if ($registration->status === 'confirmed') {
      $eventDate = Carbon::parse($registration->event->start_datetime);
      if (now()->diffInHours($eventDate, false) < 24) {
        return back()->with('error', 'Cannot cancel within 24 hours of event ('.$registration->event->title.').');
      }
    }

    $registration->update([
      'status' => 'cancelled'
    ]);

    $registration->event->organiser
      ->notify(new AttendeeRegistrationCancelled($registration));

    return back()->with('success', 'Registration cancelled.');
  }
}
