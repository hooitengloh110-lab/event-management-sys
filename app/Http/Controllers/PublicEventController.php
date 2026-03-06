<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicEventController extends Controller
{
  public function index(Request $request)
  {
    $filters = [
      ...$request->only([
        'keyword',
        'category',
        'priceFrom',
        'priceTo',
        'capacity',
        'freeOnly',
        'dateFrom',
        'dateTo',
        'by',
        'order',
        'sort',
        'date'
      ])
    ];

    $filters['freeOnly'] = $request->boolean('freeOnly');

    $events = Event::query()
      ->where('status', 'published')
      ->where('start_datetime', '>=', now())
      ->with(['organiser:id,name',
        'images' => function ($query) {
            $query->where('is_cover', true);
        }
      ])
      ->withCount(['registrations' => function ($query) {
        $query->where('status', '!=', 'cancelled');
      }])
      ->PublicFilter($filters)->paginate(10)->withQueryString();

    // Get categories for filter
    $categories = Event::select('category')
      ->where('status', 'published')
      ->distinct()
      ->pluck('category');

    return Inertia::render('Public/Event/Index', [
      'events' => $events,
      'categories' => $categories,
      'filters' => $filters,
    ]);
  }

  public function show(Event $event)
  {
    if ($event->status !== 'published') {
      abort(404);
    }

    $event->load([
      'organiser:id,name',
      'images',
      'reviews' => function ($query) {
        $query->with('user:id,name')
          ->latest()
          ->take(5);
      }
    ]);

    $event->loadCount([
      'registrations',
        'registrations as confirm_registrations_count' => function ($query) {
          $query->where('status', 'confirmed')->whereNull('deleted_at');
        },
        'registrations as cancelled_registrations_count' => function ($query) {
          $query->where('status', 'cancelled')->whereNull('deleted_at');
        },
    ]);

    $event->loadAvg('reviews', 'rating');

    return Inertia::render('Public/Event/Show', [
      'event' => $event,
    ]);
  }
}
