<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Notifications\EventCancelledNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

      $routeName = match ($request->user()->role) {
          'attendee' => 'attendee.event.index',
          default => 'event.index',
      };

      return inertia('Event/Index', [
        'filters' => $filters,
        'events' => Auth::user()
          ->events()
          // ->MostRecent()
          ->filter($filters)
          ->withCount('images')
          ->withCount('registrations')
          ->withCount([
            'registrations as cancelled_registrations_count' => function ($query) {
                $query->where('status', 'cancelled')->whereNull('deleted_at');
            }
          ])
          ->paginate(6)
          ->withQueryString(),
        'indexRoute' => $routeName,
      ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      Gate::authorize('create', Event::class);

        return Inertia::render('Event/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $event = $request->user()->events()->create($request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'category' => 'required|string|max:50',
        'start_datetime' => 'required|date',
        'end_datetime' => 'required|date|after_or_equal:start_datetime',
        'capacity' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0',
        'status' => 'required|in:draft,published',
        'images' => 'nullable|array|max:6',
        'images.*' => 'mimes:jpg,png,jpeg,webp|max:5000',
      ],
      [
        'images.*.mimes' => 'The file should be in one of the formats: jpg, png, jpeg, webp'
      ]));

      if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
          $path = $file->store('images', 'public');

          $event->images()->create([
            'filename' => $path,
          ]);
        }
      }

      return redirect()->route('organiser.dashboard')->with('success', 'Event was created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
      Gate::authorize('view', $event);

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
        'Event/Show',
        [
          'event' => $event,
        ]
      );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
      Gate::authorize('update', $event);

      return Inertia::render('Event/Edit', ['event' => $event]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
      if ($request->hasFile('images') && ($event->images()->count() + count($request->file('images')) > 6)) {
        return back()->withErrors([
            'images' => 'Total images cannot exceed 6.',
        ]);
      }

      $event = $event->update($request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'category' => 'required|string|max:50',
        'start_datetime' => 'required|date',
        'end_datetime' => 'required|date|after_or_equal:start_datetime',
        'capacity' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0',
        'status' => 'required|in:draft,published',
        'images' => 'nullable|array|max:6',
        'images.*' => 'mimes:jpg,png,jpeg,webp|max:5000',
      ],
      [
        'images.*.mimes' => 'The file should be in one of the formats: jpg, png, jpeg, webp'
      ]));

      if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
          $path = $file->store('images', 'public');

          $event->images()->create([
            'filename' => $path,
          ]);
        }
      }

      return redirect()->route('event.index')
        ->with('success', 'Event was updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
      Gate::authorize('delete', $event);

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
      $event->deleteOrFail();

      return redirect()->back()->with('success', 'Event was deleted!');
    }
}
