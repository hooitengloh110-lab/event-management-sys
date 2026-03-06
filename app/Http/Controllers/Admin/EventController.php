<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Notifications\EventCancelledNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EventController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $events = Event::with('organiser:id,name')
      ->filter($request->only('organiser'))
      ->latest()
      ->paginate(10);

    $organisers = User::where('role', 'organiser')
      ->select('id', 'name')
      ->get();

    return Inertia::render('Admin/Event/Index', [
      'events' => $events,
      'organisers' => $organisers,
      'filters' => $request->only('organiser')
    ]);
  }

  public function show(Event $event)
    {
      $event->load(['images']);
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
        'Admin/Event/Show',
        [
          'event' => $event,
        ]
      );
    }

  public function cancel(Event $event)
  {
    $event->update([
      'status' => 'cancelled'
    ]);

    $event->registrations()
      ->update([
          'status' => 'cancelled'
      ]);

    $event->organiser->notify(new EventCancelledNotification($event));

    if ($event->registrations()->exists()) {
      $attendees = $event->registrations()->with('attendee')->get()->pluck('attendee');
      foreach ($attendees as $user) {
        $user->notify(new EventCancelledNotification($event));
      }
    }

    return back()->with('success', 'Event has been cancelled.');
  }

  public function destroy(Event $event)
  {
    if ($event->registrations()->exists()) {
      $attendees = $event->registrations()->with('attendee')->get()->pluck('attendee');
      foreach ($attendees as $user) {
        $user->notify(new EventCancelledNotification($event));
      }
    }

    foreach ($event->images as $image) {
      Storage::disk('public')->delete($image->filename);
    }
    $event->registrations()->delete();
    $event->images()->delete();
    $event->delete();

    return back()->with('success', 'Event deleted.');
  }
}
