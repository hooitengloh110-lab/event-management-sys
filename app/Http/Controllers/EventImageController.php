<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EventImageController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event)
    {
      $event->load(['images']);
      return Inertia::render('Organiser/EventImage/Create',['event' => $event]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Event $event, Request $request)
    {
      if ($request->hasFile('images') && ($event->images()->count() + count($request->file('images')) > 6)) {
        return back()->withErrors([
            'images' => 'Total images cannot exceed 6.',
        ]);
      }

      if ($request->hasFile('images')) {
        $request->validate(
          ['images.*' => 'mimes:jpg,png,jpeg,webp|max:5000'],
          ['images.*.mimes' => 'The file should be in one of the formats: jpg, png, jpeg, webp']
        );

        foreach ($request->file('images') as $file) {
          $path = $file->store('images', 'public');

          $event->images()->save(new EventImage([
            'filename' => $path
          ]));
        }
      }

      return redirect()->back()->with('success', 'Images uploaded!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, EventImage $image)
    {
      Storage::disk('public')->delete($image->filename);
      $image->delete();

      return redirect()->back()->with('success', 'Image was deleted!');
    }
}
