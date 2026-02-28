<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Review;
use App\Policies\EventPolicy;
use App\Policies\RegistrationPolicy;
use App\Policies\ReviewPolicy;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Event::class => EventPolicy::class,
        Registration::class => RegistrationPolicy::class,
        Review::class => ReviewPolicy::class,
    ];
    
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
