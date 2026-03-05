<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    public function index(Request $request): Response
    {
      $notifications = $request->user()->notifications()->latest();

        return Inertia::render(
            'Notification/Index',
            [
                'notifications' => $notifications->paginate(20),
                'notificationsBell' => $notifications->paginate(5)
            ]
        );
    }
}
