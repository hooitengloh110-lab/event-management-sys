<?php

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
})->middleware(['auth', 'verified','role:admin,organiser'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
        Route::get('/dashboard', function () {
            return Inertia::render('Organiser/Dashboard');
        })->name('dashboard');
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

require __DIR__.'/auth.php';
