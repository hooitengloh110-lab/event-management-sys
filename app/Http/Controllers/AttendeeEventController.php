<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Notifications\NewEventRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AttendeeEventController extends Controller
{
  public function index(Request $request)
  {
    Gate::authorize('viewAny', Event::class);

    $filters = [
      'deleted' => $request->boolean('deleted'),
      ...$request->only([
        'keyword',
        'category',
        'priceFrom',
        'priceTo',
        'capacity',
        'freeOnly',
        'dateFrom',
        'dateTo',
        'deleted',
        'by',
        'order'
      ])
    ];
    $filters['deleted'] = $request->boolean('deleted');
    $filters['freeOnly'] = $request->boolean('freeOnly');

    $event = Event::with('images', 'registrations', 'organiser')->where('status', 'published');

    $routeName = match ($request->user()->role) {
        'attendee' => 'attendee.event.index',
        default => 'event.index',
    };

    $notifications = $request->user()->notifications()
      ->orderByRaw('read_at IS NULL DESC')
      ->latest()
      ->take(5)
      ->get();

    return inertia('Attendee/Event/Index', [
      'filters' => $filters,
      'events' => $event
        ->filter($filters)
        ->paginate(6)
        ->withQueryString(),
      'indexRoute' => $routeName,
      'notificationsBell' => $notifications
    ]);
  }

  public function show(Event $event)
  {
    Gate::authorize('view', $event);

    $event->load(['images', 'organiser']);
    $event->loadCount([
      'registrations',
      'registrations as confirm_registrations_count' => function ($query) {
        $query->where('status', 'confirmed')->whereNull('deleted_at');
      },
      'registrations as cancelled_registrations_count' => function ($query) {
        $query->where('status', 'cancelled')->whereNull('deleted_at');
      },
    ]);

    return inertia(
      'Attendee/Event/Show',
      [
        'event' => $event,
      ]
    );
  }

  public function register(Request $request, Event $event)
  {
    $attendee = $request->user();

    $alreadyRegistered = Registration::where('attendee_id', $attendee->id)
        ->where('event_id', $event->id)
        ->where('status', '!=', 'cancelled')
        ->exists();

    if ($alreadyRegistered) {
      return back()->with('error', 'You have already registered for this event ('.$event->title.').');
    }

    $confirmedCount = $event->registrations()->where('status', '!=' ,'cancelled')->count();
    if ($confirmedCount >= $event->capacity) {
        return back()->with('error', 'This event ('.$event->title.') is fully booked.');
    }

    if (Carbon::now()->gt(Carbon::parse($event->start_datetime))) {
        return back()->with('error', 'Cannot register for an event that has already started.');
    }

    DB::transaction(function () use ($attendee, $event) {
        $registration = Registration::create([
            'attendee_id' => $attendee->id,
            'event_id' => $event->id,
            'status' => 'pending',
        ]);

        $event->organiser->notify(new NewEventRegistration($registration));
    });

    return back()->with('success', 'Successfully registered for the event!');
  }
}
