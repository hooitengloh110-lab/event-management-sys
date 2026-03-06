<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $admin = Auth::user();

      $stats = [
        'total_users' => User::count(),
        'events_this_month' => Event::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'published')
            ->count(),
        'registrations_this_month' => Registration::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereNull('deleted_at')
            ->where('status', '=', 'confirmed')
            ->count(),
        'revenue_this_month' => Registration::join('events', 'registrations.event_id', '=', 'events.id')
            ->where('registrations.status', 'confirmed')
            ->where('events.status', 'published')
            ->whereMonth('registrations.created_at', now()->month)
            ->whereYear('registrations.created_at', now()->year)
            ->whereNull('events.deleted_at')
            ->sum('events.price'),
      ];

      return Inertia::render('Admin/Dashboard',[
        'stats' => $stats
      ]);
    }

}
