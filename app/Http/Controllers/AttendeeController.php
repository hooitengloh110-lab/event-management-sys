<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendeeController extends Controller
{
  public function index(Request $request)
  {
    $registrations = $request->user()
        ->registrations()
        ->with('event')
        ->latest()
        ->paginate(5)
        ->withQueryString();

    return Inertia::render('Attendee/Dashboard', [
        'registrations' => $registrations
    ]);
    
  }
    
}
