<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['event', 'attendee', 'status']);

        // Eager-load event and attendee
        $registrations = Registration::with(['event:id,title', 'attendee:id,name,email'])
            ->filter($filters)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // For filter dropdowns
        $events = Event::select('id', 'title')->get();
        $attendees = User::where('role', 'attendee')->select('id', 'name')->get();

        return Inertia::render('Admin/Registration/Index', [
            'registrations' => $registrations,
            'events' => $events,
            'attendees' => $attendees,
            'filters' => $filters,
        ]);
    }
}
