<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendeeController extends Controller
{
  public function index(Request $request)
  {
    $user = $request->user();
    $registrations = $user->registrations()
        ->with([
          'event' => function ($query) {
            $query->withCount([
                'registrations',
                'registrations as cancelled_registrations_count' => function ($q) {
                    $q->where('status', 'cancelled')->whereNull('deleted_at');
                }
            ]);
          }
        ])
        ->latest()
        ->paginate(6)
        ->withQueryString();

    $registrations->getCollection()->transform(function ($registration) {
            $endDateLocal = Carbon::parse($registration->event->end_datetime)
                ->setTimezone('Asia/Kuala_Lumpur');
            $registration->event->isPast = Carbon::now('UTC')->gt($endDateLocal);
        return $registration;
    });

    return Inertia::render('Attendee/Dashboard', [
        'registrations' => $registrations,
    ]);
    
  }
    
}
