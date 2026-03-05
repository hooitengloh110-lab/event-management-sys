<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        $role = $request->user()->role;
        if (in_array($role, ['organiser', 'admin'])) {
          $goTo = 'organiser.dashboard';
        } else {
          $goTo = 'attendee.dashboard';
        }

        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route($goTo, absolute: false))
                    : Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
    }
}
