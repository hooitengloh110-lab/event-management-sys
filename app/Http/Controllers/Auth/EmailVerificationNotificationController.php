<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        $role = $request->user()->role;
        if (in_array($role, ['organiser', 'admin'])) {
          $goTo = 'organiser.dashboard';
        } else {
          $goTo = 'attendee.dashboard';
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route($goTo, absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
