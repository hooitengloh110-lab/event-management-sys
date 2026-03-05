<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrganiserController extends Controller
{
  public function index()
  {
    $organiser = Auth::user();

    // Total Events
    $events = $organiser->events()
      ->withCount('registrations')
      ->get();

    // Upcoming Events
    $upcomingEvents = $organiser->events()
      ->withCount('registrations')
      ->where('start_datetime', '>=', now())
      ->where('status', 'published')
      ->orderBy('start_datetime', 'asc')
      ->paginate(5)
      ->withQueryString();

    $upcomingEvents->getCollection()->transform(function ($event) {
      $event->revenue = $event->price * $event->registrations_count;
      return $event;
    });

    return Inertia::render('Organiser/Dashboard', [
      'stats' => [
        'total_events' => $events->count(),
        'total_registrations' => $events->sum('registrations_count'),
        'total_revenue' => $events->sum(function ($event) {
          return $event->price * $event->registrations_count;
        }),
      ],
      'upcomingEvents' => $upcomingEvents,
    ]);
  }
}
