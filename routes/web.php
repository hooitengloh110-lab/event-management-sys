<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\EventImageController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationSeenController;
use App\Http\Controllers\OrganiserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
  return Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
  ]);
});

Route::get('/dashboard', function () {
  return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'role:admin,organiser'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('notification', NotificationController::class)
  ->middleware('auth')
  ->only(['index']);

Route::put('notification/{notification}/seen', NotificationSeenController::class)
  ->middleware('auth')->name('notification.seen');

Route::middleware(['auth', 'verified'])->group(function () {
  Route::resource('event', EventController::class)->middleware('role:organiser');

  Route::resource('event.image', EventImageController::class)
    ->only(['create', 'store', 'destroy']);

  Route::get('event/{event}/registrations', [EventRegistrationController::class, 'index'])->name('event.registration.index');
  Route::patch('registration/{registration}/confirm', [EventRegistrationController::class, 'confirm'])->name('registration.confirm');
  Route::patch('registration/{registration}/cancel', [EventRegistrationController::class, 'cancel'])->name('registration.cancel');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:admin'])
  ->prefix('admin')
  ->name('admin.')
  ->group(function () {
    Route::get('/dashboard', function () {
      return Inertia::render('Admin/Dashboard');
    })->name('dashboard');
  });

/*
|--------------------------------------------------------------------------
| Organiser
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:organiser'])
  ->prefix('organiser')
  ->name('organiser.')
  ->group(function () {
    Route::get('/dashboard', [OrganiserController::class, 'index'])->name('dashboard');
  });

/*
|--------------------------------------------------------------------------
| Attendee
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:attendee'])
  ->prefix('attendee')
  ->name('attendee.')
  ->group(function () {
    Route::get('/dashboard', function () {
      return Inertia::render('Attendee/Dashboard');
    })->name('dashboard');
  });

require __DIR__ . '/auth.php';
