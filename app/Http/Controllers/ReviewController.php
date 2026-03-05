<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ReviewController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Event $event)
  {
    $event->load([
        'reviews.attendee:id,name',
    ]);

    return Inertia::render('Attendee/Review/Index', ['event' => $event, 'reviews' => $event->reviews]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(Event $event)
  {
    Gate::authorize("create", [Review::class, $event]);
    return Inertia::render('Attendee/Review/Create', ['event' => $event]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request, Event $event)
  {
    $user = $request->user();
    if (now()->lte($event->end_datetime)) {
      return back()->with('error', 'You can only review after the event ends.');
    }

    $registration = $event->registrations()
      ->where('attendee_id', $user->id)
      ->where('status', 'confirmed')
      ->first();

    if (!$registration) {
      return back()->with('error', 'You are not eligible to review this event.');
    }

    if ($event->reviews()->where('attendee_id', $user->id)->exists()) {
      return back()->with('error', 'You have already reviewed this event.');
    }

    $validated = $request->validate([
      'rating' => ['required', 'integer', 'between:1,5'],
      'comment' => ['required', 'string', 'max:1000'],
    ]);

    Review::create([
      'event_id' => $event->id,
      'attendee_id' => $user->id,
      'rating' => $validated['rating'],
      'comment' => $validated['comment'] ?? null,
    ]);

    return redirect()->route('attendee.dashboard')->with('success', 'Thank you for your review!');
  }

  /**
   * Display the specified resource.
   */
  public function show(Event $event, Review $review)
  {
    return Inertia::render('Attendee/Review/Show', ['event' => $event, 'review' => $review]);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Review $review)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Review $review)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Review $review)
  {
    //
  }
}
