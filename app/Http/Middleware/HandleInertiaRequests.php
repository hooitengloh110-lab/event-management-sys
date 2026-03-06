<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                // 'user' => $request->user(),
                // 'notificationCount' => $request->user()->unreadNotifications()->count()
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'role' => $request->user()->role,
                    'notificationCount' => $request->user()->unreadNotifications()->count(),
                    'notificationsBell' => fn () => $request->user()
                      ? $request->user()->notifications()
                          ->orderByRaw('read_at IS NULL DESC')
                          ->latest()
                          ->take(5)
                          ->get()
                      : [],
                ] : null
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'routeDifferentiate' => $request->user()
                ? match($request->user()->role) {
                    'attendee'  => 'attendee.event.index',
                    'organiser' => 'organiser.event.index',
                    'admin'     => 'admin.event.index',
                    default     => 'public.event.index',
                }
                : 'public.event.index',
        ];
    }
}
